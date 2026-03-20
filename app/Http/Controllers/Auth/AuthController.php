<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\User;
use App\Services\OtpService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    use ApiResponse;
    private $otpService;
    public function __construct()
    {
        $this->otpService = new OtpService();
    }
    public function auth(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'string',
                'min:11',
                'max:255',
                'regex:/^(\+98|0)?9\d{9}$/'
            ],
        ]);

        $phone = $request->input('phone');
        $result = $this->otpService->send($phone, 'auth');
        return $result;
    }

    public function verifyAuth(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'string',
                'min:11',
                'max:255',
                'regex:/^(\+98|0)?9\d{9}$/'
            ],
            'code' => 'required|string|size:6'
        ]);

        $phone = $request->input('phone');
        $code = $request->input('code');
        $result = $this->otpService->verify($phone, $code, 'auth');
        if ($result->getStatusCode() !== 200) {
            return $result;
        }
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            $user = User::create(['phone' => $phone, 'phone_verified_at' => now(), 'created_at' => now(), 'updated_at' => now()]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->success(['token' => $token], 'User authenticated successfully.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'string',
                'min:11',
                'max:255',
                'regex:/^(\+98|0)?9\d{9}$/'
            ],
        ]);

        $phone = $request->input('phone');
        $result = $this->otpService->send($phone, 'reset_password');
        return $result;
    }

    public function verifyResetPassword(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'string',
                'min:11',
                'max:255',
                'regex:/^(\+98|0)?9\d{9}$/'
            ],
            'code' => 'required|string|size:6'
        ]);

        $phone = $request->input('phone');
        $code = $request->input('code');
        $result = $this->otpService->verify($phone, $code, 'reset_password');
        if ($result->getStatusCode() !== 200) {
            return $result;
        }
        $token = Hash::make(Str::random(60));
        DB::table('password_reset_tokens')->updateOrInsert(
            ['phone' => $phone],
            [
                'token' => $token,
                'expire_at' => now()->addMinutes(5),
                'created_at' => now()
            ]
        );
        return $this->success(['token' => $token], 'OTP verified successfully. You can now reset your password.');
    }

    public function confirmResetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8'],
            'reset_token' => 'required|string|exists:password_reset_tokens,token',
        ]);
        $data = DB::table('password_reset_tokens')->where('token', $request->input('reset_token'))->first();
        if (!$data || now()->greaterThan($data->expire_at)) {
            return $this->error('Token Expired Or Invalid', 400);
        }
        $user = User::where('phone', $data->phone)->first();
        $user->password = $request->input('password');
        $user->save();
        DB::table('password_reset_tokens')->where('token', $request->input('reset_token'))->delete();

        return $this->success(null, 'Password changed successfully.');
    }
}
