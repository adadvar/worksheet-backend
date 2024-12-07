<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\LoginNewUserRequest;
use App\Http\Requests\Auth\LoginVerifyNewUserRequest;
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
use Ipe\Sdk\Facades\SmsIr;

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

            if (!$user->cart) $user->cart()->create();

            $token = $user->createToken('myapptoken')->plainTextToken;

            return response(['user' => $user, 'token' => $token, 'message' => 'با موفقیت وارد شدید'], 200);
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
            $code = null;
            if ($user = User::where($field, $value)->first()) {
                if ($user->verified_at) {
                    throw new UserAlreadyRegisteredException('شما قبلا ثبت نام کرده اید');
                }
                // return response(['message' => 'کد فعالسازی قبلا برای شما ارسال شده'], 400);
                $dateDiff = now()->diffInSeconds($user->updated_at);

                if ($dateDiff > config('auth.resend_verification_code_time_diff', 60)) {
                    $user->verify_code = random_verification_code();
                    $user->save();
                } else {
                    return response([
                        'message' => 'لطفا بعد از گذشت یک دقیقه تلاش کنید'
                    ], 500);
                }
            } else {
                $code = random_verification_code();
                $user = User::create([
                    $field => $value,
                    'verify_code' => $code,
                ]);
            }

            Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);

            if ($request->getFieldName() === 'email') {
                Mail::to($user)->send(new VerificationCodeMail($code));
            } else {
                $this->sendSMS($value, $user->verify_code);
            }

            DB::commit();
            return response(['message' => 'کد تایید ارسال شد'], 200);
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
        $password = $request->password;
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
        $user->password = bcrypt($password);
        $user->save();

        //TODO: change it comming from user
        $user->roles()->sync(2);
        return response(['user' => $user, 'message' => 'با موفقیت ثبت نام شدید'], 200);
    }

    public function resendVerificationCode(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->whereNull('verified_at')->first();

        if (!empty($user)) {
            $dateDiff = now()->diffInSeconds($user->updated_at);

            if ($dateDiff > config('auth.resend_verification_code_time_diff', 60)) {
                $user->verify_code = random_verification_code();
                $user->save();
            } else {
                return response([
                    'message' => 'لطفا بعد از گذشت یک دقیقه تلاش کنید'
                ], 500);
            }


            Log::info('RESEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $user->verify_code]);
            if ($field === 'email') {
                Mail::to($user)->send(new VerificationCodeMail($user->verify_code));
            } else {
                // \Kavenegar::Send(config('kavenegar.sender'), $value, 'کد فعالسازی ' . $code);

                $this->sendSMS($value, $user->verify_code);
            }

            return response([
                'message' => 'رمز یکبارمصرف برای شما ارسال گردید'
            ], 200);
        }

        // throw new ModelNotFoundException('کاربر پیدا نشد یا از قبل ثبت نام کرده است');
        return response([
            'message' => 'کاربر یافت نشد یا از قبل ثبت نام کرده است'
        ], 404);
    }

    public function sendOtp(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->whereNotNull('verified_at')->first();

        if (!empty($user)) {
            $dateDiff = now()->diffInSeconds($user->updated_at);

            if ($dateDiff > config('auth.resend_verification_code_time_diff', 60)) {
                $user->verify_code = random_verification_code();
                $user->save();
            } else {
                return response([
                    'message' => 'لطفا بعد از گذشت یک دقیقه تلاش کنید'
                ], 500);
            }


            Log::info('RESEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $user->verify_code]);
            if ($field === 'email') {
                Mail::to($user)->send(new VerificationCodeMail($user->verify_code));
            } else {
                // \Kavenegar::Send(config('kavenegar.sender'), $value, 'کد فعالسازی ' . $code);
                $this->sendSMS($value, $user->verify_code);
            }

            return response([
                'message' => 'رمز یکبارمصرف برای شما ارسال گردید'
            ], 200);
        }

        // throw new ModelNotFoundException('کاربر پیدا نشد یا از قبل ثبت نام کرده است');
        return response([
            'message' => 'کاربر یافت نشد یا از قبل ثبت نام کرده است'
        ], 404);
    }

    public function loginVerify(LoginVerifyNewUserRequest $r)
    {
        try {
            $user = User::where('email', $r->username)->orWhere('mobile', to_valid_mobile_number($r->username))->first();

            if (!$user || $r->code !== $user->verify_code) {
                return response([
                    'message' => 'نام کاربری یا کد یکبارمصرف اشتباه می باشد.'
                ], 401);
            }

            $user->verify_code = null;
            $user->save();

            if (!$user->cart) $user->cart()->create();

            $token = $user->createToken('myapptoken')->plainTextToken;

            return response(['user' => $user, 'token' => $token, 'message' => 'با موفقیت وارد شدید'], 200);
        } catch (Exception $exception) {
            Log::error($exception);
            return response(
                ['message' => 'خطایی رخ داده است'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    private function sendSMS($mobile, $code)
    {
        // return;
        try {
            $parameters = [
                [
                    "name" => "Code",
                    "value" => $code
                ]
            ];
            $templateId = 751976;
            $response = SmsIr::verifySend($mobile, $templateId, $parameters);
        } catch (Exception $exception) {
            return response(
                ['message' => 'خطایی رخ داده است'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
