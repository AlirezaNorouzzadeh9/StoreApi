<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * ساختار پاسخ موفقیت‌آمیز
     */
    protected function success($data = null, $message, $code = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function error($message, $code, $errors = null)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors,
        ], $code);
    }
}
