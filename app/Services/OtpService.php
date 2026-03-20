<?php

namespace App\Services;

use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Create a new class instance.
     */

    use ApiResponse;
    public function __construct() {}
    public function send($identifier, $type)
    {
        $lastOtp = DB::table('otps')->where('identifier', $identifier)->first();
        if ($lastOtp && now()->lessThan($lastOtp->expire_at)) {
            $remaining = now()->diffInSeconds($lastOtp->expire_at);
            return $this->error("Please wait {$remaining} seconds before requesting a new OTP.", 429);
        }
        $code = rand(100000, 999999);
        DB::table('otps')->updateOrInsert(
            ['identifier' => $identifier],
            ['code' => $code, 'type' => $type, 'expire_at' => now()->addMinutes(2), 'attempts' => 0, 'updated_at' => now(),'created_at' => now()]
        );
        Log::info("Sending OTP {$code} to {$identifier} via {$type}");
        return $this->success(null, 'OTP sent successfully.');
    }

    public function verify($identifier, $code,$type)
    {
        $record = DB::table('otps')
            ->where('identifier', $identifier)
            ->where('type', $type)
            ->first();
        if (!$record || now()->greaterThan($record->expire_at)) {
            return $this->error('کد معتبری یافت نشد یا منقضی شده است.', 404);
        }
        if ($record->attempts >= 3) {
            DB::table('otps')->where('identifier', $identifier)->delete();
            return $this->error('تعداد تلاش‌های شما بیش از حد مجاز بود. لطفا دوباره درخواست کد بدهید.', 429);
        }
        if ($record->code !== $code) {
            DB::table('otps')->where('identifier', $identifier)->increment('attempts');
            return $this->error("کد وارد شده اشتباه است", 400);
        }
        DB::table('otps')->where('identifier', $identifier)->delete();
        return $this->success(null, 'کد با موفقیت تایید شد.');
    }
}
