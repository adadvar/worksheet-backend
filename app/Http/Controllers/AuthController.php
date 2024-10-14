<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\LoginNewUserRequest;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function login(LoginNewUserRequest $r)
    {
        try {
            $user = User::where('email', $r->username)->orWhere('mobile', to_valid_mobile_number($r->username))->first();

            if (!$user || !Hash::check($r->password, $user->password)) {
                return response([
                    'message' => 'نام کاربری یا رمز عبور اشتباه می باشد.'
                ], 401);
            }

            $token = $user->createToken('myapptoken')->plainTextToken;

            return response(['user' => $user, 'token' => $token], 201);
        } catch (Exception $exception) {
            Log::error($exception);
            return response(
                ['message' => 'خطایی رخ داده است'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function register(RegisterNewUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $field = $request->getFieldName();
            $value = $request->getFieldValue();
            // اگر کاربر از قبل ثبت نام کرده باشد باید روال ثبت نام را قطع کنیم
            if ($user = User::where($field, $value)->first()) {
                // اگر کاربر من ازقبل ثبت نام خودش رو کامل کرده باشه باید بهش خطا بدم
                if ($user->verified_at) {
                    throw new UserAlreadyRegisteredException('شما قبلا ثبت نام کرده اید');
                }

                return response(['message' => 'کد فعالسازی قبلا برای شما ارسال شده'], 200);
            }

            $code = random_verification_code();
            $user = User::create([
                $field => $value,
                'verify_code' => $code,
            ]);

            Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);

            if (!env('APP_DEBUG', true)) {
                if ($request->getFieldName() === 'email') {
                    Mail::to($user)->send(new VerificationCodeMail($code));
                } else {
                    // \Kavenegar::Send(config('kavenegar.sender'), $value, 'کد فعالسازی ' . $code);
                }
            }

            DB::commit();
            return response(['message' => 'کاربر ثبت موقت شد'], 200);
        } catch (Exception $exception) {
            Db::rollBack();

            if ($exception instanceof UserAlreadyRegisteredException) {
                throw $exception;
            }

            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();
        $code = $request->code;
        $user = User::where(['verify_code' => $code, $field => $value])->first();

        if (empty($user)) {
            // throw new ModelNotFoundException('کاربر یافت نشد');
            return response([
                'message' => 'کاربر یافت نشد'
            ], 404);
        }

        $value = $request->input($field);
        $user->verify_code = null;
        $user->verified_at = now();
        $user->password = bcrypt($value);
        $user->save();

        return response($user, 200);
    }

    public function resendVerificationCode(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->whereNull('verified_at')->first();

        if (!empty($user)) {
            $dateDiff = now()->diffInMinutes($user->updated_at);

            if ($dateDiff > config('auth.resend_verification_code_time_diff', 60)) {
                $user->verify_code = random_verification_code();
                $user->save();
            }


            Log::info('RESEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $user->verify_code]);
            if ($field === 'email') {
                Mail::to($user)->send(new VerificationCodeMail($user->verify_code));
            } else {
                // \Kavenegar::Send(config('kavenegar.sender'), $value, 'کد فعالسازی ' . $code);
            }

            return response([
                'message' => 'کد مجددا برای شما ارسال گردید.'
            ], 200);
        }

        // throw new ModelNotFoundException('کاربر پیدا نشد یا از قبل ثبت نام کرده است');
        return response([
            'message' => 'کاربر یافت نشد یا از قبل ثبت نام کرده است'
        ], 404);
    }
}
