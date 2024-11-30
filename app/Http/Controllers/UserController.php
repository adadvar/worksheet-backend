<?php

namespace App\Http\Controllers;


use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UserUnregisterRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserGetRequest;
use App\Http\Requests\User\UserListRequest;
use App\Http\Requests\User\UserMeRequest;
use App\Http\Requests\User\UserPasswordVerifyRequest;
use App\Http\Requests\User\UserResetPasswordRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
  const CHANGE_EMAIL_CACHE_KEY = 'change.email.for.user.';


  public function changeEmail(ChangeEmailRequest $request)
  {
    try {
      $email = $request->email;
      $userId = auth()->id();
      $user = auth()->user();
      $code = random_verification_code();
      $expireDate = now()->addMinutes(config('auth.change_email_cache_expiration', 1440));
      Cache::put(self::CHANGE_EMAIL_CACHE_KEY . $userId, compact('email', 'code'), $expireDate);
      if (!env('APP_DEBUG', true)) {
        Mail::to($user)->send(new VerificationCodeMail($code));
      }
      Log::info('SEND_CHANGE_EMAIL_CODE', compact('code'));
      return response([
        'message' => 'یک ایمیل برای شما ارسال شده است لطفا آن را بررسی نمایید'
      ], 200);
    } catch (Exception $e) {
      Log::error($e);
      return response([
        'message' => 'یک خطایی رخ داده است و سرور قادر به ارسال کد فعال سازی نمی باشد'
      ], 500);
    }
  }

  public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
  {
    $userId = auth()->id();
    $cacheKey = self::CHANGE_EMAIL_CACHE_KEY . $userId;
    $cache = Cache::get($cacheKey);

    if (empty($cache) || $cache['code'] != $request->code) {
      return response([
        'message' => 'درخواست نامعتبر'
      ], 400);
    }

    $user = auth()->user();
    $user->email = $cache['email'];
    $user->save();
    Cache::forget($cacheKey);
    return response([
      'email' => $user->email,
      'message' => 'ایمیل با موفقیت تغییر یافت'
    ], 200);
  }

  public function changePassword(Request $request)
  {

    try {
      $user = auth()->user();
      if (!Hash::check($request->old_password, $user->password)) {
        return response(['message' => 'رمز وارد شده مطابقت ندارد'], 400);
      }

      $user->password = bcrypt(($request->password));
      $user->save();

      return response([
        'message' => 'پسورد با موفقیت تغییر یافت!'
      ], 200);
    } catch (Exception $e) {
      Log::error($e);
      return response(['message' => 'خطایی رخ داده است '], 500);
    }
  }

  public function logout(Request $request)
  {
    try {
      $request->user()->tokens()->delete();

      return response(['message' => 'باموفقیت خارج شدید'], Response::HTTP_OK);
    } catch (Exception $e) {
      Log::error($e);
    }

    return response(['message' => 'خروج ناموفق بود'], Response::HTTP_BAD_REQUEST);
  }

  public function unregister(UserUnregisterRequest $request)
  {
    try {
      DB::beginTransaction();
      $request->user()->delete();
      DB::commit();
      return response(['message' => 'با موفقیت لغو ثبت نام شد'], 200);
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e);
      return response(['message' => 'خطایی رخ داده است'], 500);
    }
  }

  public function delete(UserDeleteRequest $request)
  {
    try {
      DB::beginTransaction();

      $request->user->delete();
      DB::table('personal_access_tokens')
        ->where('tokenable_id', $request->user->id)
        ->where('tokenable_type', User::class)
        ->delete();
      DB::commit();

      return response(['message' => 'کاربر با موفقیت حذف شد'], 200);
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e);
      return response(['message' => 'خطایی رخ داده است'], 500);
    }
  }

  public function me(UserMeRequest $request)
  {
    $user = auth()->user();

    return $user;
  }

  public function get(UserGetRequest $r)
  {

    return $r->user;
  }

  public function list(UserListRequest $request)
  {
    $query = User::query();
    if ($request->q) {
      $query->where('name', 'LIKE', '%' . $request->q . '%');
    }
    return $query->paginate($request->per_page ?? 9);
  }

  public function update(UserUpdateRequest $request)
  {
    $request->user->update($request->validated());
    if ($request->has('role_id'))
      $request->user->roles()->sync($request->input('role_id'));
    $request->user->load('roles');

    return response([
      'message' => 'کاربر با موفقیت بروزرسانی شد'
    ], 200);
  }

  public function resetPassword(UserResetPasswordRequest $request)
  {
    $request->user->update(['password' => env('REQUEST_PASSWORD_DEFAULT', bcrypt('111111'))]);
    // return response(null, Response::HTTP_ACCEPTED);
    return response([
      'message' => 'پسورد با موفقیت بازنشانی شد'
    ], 201);
  }

  public function passwordVerify(UserPasswordVerifyRequest $r)
  {
    try {
      $user = User::where('email', $r->username)->orWhere('mobile', to_valid_mobile_number($r->username))->first();

      if (!$user || $r->code !== $user->verify_code) {
        return response([
          'message' => 'نام کاربری یا کد یکبارمصرف اشتباه می باشد.'
        ], 401);
      }

      $user->update(['password' => bcrypt($r->password), 'verify_code' => null]);
      return response([
        'message' => 'پسورد با موفقیت تغییر کرد'
      ], 201);
    } catch (Exception $exception) {

      Log::error($exception);
      return response(
        ['message' => 'خطایی رخ داده است'],
        Response::HTTP_INTERNAL_SERVER_ERROR
      );
    }
  }
}
