<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateRequest;
use App\Models\User\User;
use App\Services\OtpService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    use ApiResponse;
    private $otpService;
    public function __construct() {
        $this->otpService = new OtpService();
    }
    public function authMe(Request $request)
    {
        $user = $request->user();
        return $this->success($user, 'User profile retrieved successfully.');
    }

    public function changeGeneral(UpdateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $user->update($data);

        return $this->success($user, 'User profile updated successfully.');
    }

    public function changeEmail(Request $request){
        $user = $request->user();
        $request->validate([
            'email' => "required|email|unique:users,email,{$user->id}",
        ]);
        if($user->email === $request->email){
            return $this->error('The new email is the same as the current one.', 422);
        }
        elseif(User::where('email', $request->email)->exists()){
            return $this->error('The email is already exists.', 422);
        }
        $result = $this->otpService->send($request->email,'change_email');
        if($result->getStatusCode() !== 200) {
            return $result;
        }
        return $this->success(null, 'OTP sent to new email successfully.');
    }

    public function changePhone(Request $request){
        $user = $request->user();
            $request->validate([
            'phone' => [
                'required',
                'string',
                'min:11',
                'max:255',
                'regex:/^(\+98|0)?9\d{9}$/',
                "unique:users,phone,{$user->id}"
            ],
        ]);
        if($user->phone === $request->phone){
            return $this->error('The new phone number is the same as the current one.', 422);
        }
        elseif(User::where('phone', $request->phone)->exists()){
            return $this->error('The phone number is already exists.', 422);
        }
        $result = $this->otpService->send($request->phone,'change_phone');
        if($result->getStatusCode() !== 200) {
            return $result;
        }
        return $this->success(null, 'OTP sent to new phone number successfully.');


    }

    public function verifyPhone(Request $request){
        $user = $request->user();
        $request->validate([
            'phone' => [
                'required',
                'string',
                'min:11',
                'max:255',
                'regex:/^(\+98|0)?9\d{9}$/',
                "unique:users,phone,{$user->id}"
            ],
            'code' => 'required|string',
        ]);
        $result = $this->otpService->verify($request->input('phone'), $request->input('code'), 'change_phone');
        if($result->getStatusCode() !== 200) {
            return $result;
        }
        $user->phone = $request->input('phone');
        $user->save();
        return $this->success($user, 'Phone number updated successfully.');
    }

    public function verifyEmail(Request $request){
        $user = $request->user();
        $request->validate([
            'email' => "required|email|unique:users,email,{$user->id}",
            'code' => 'required|string',
        ]);
        $result = $this->otpService->verify($request->input('email'), $request->input('code'), 'change_email');
        if($result->getStatusCode() !== 200) {
            return $result;
        }
        if($user->email){
            $user->email_verified_at = now();
        }
        $user->email = $request->input('email');
        $user->save();
        return $this->success($user, 'Email updated successfully.');
    }

    
    public function changePassword(Request $request){
        $user = $request->user();
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);
        if (!password_verify($request->input('old_password'), $user->password)) {
            return $this->error('Old password is incorrect.', 422);
        }
        $user->password = $request->input('new_password');
        $user->save();
                return $this->success(null, 'Password updated successfully.');
    }



}
