<?php

namespace App\Traits;

trait ApiResponse
{

    function success_response($data, $message = "Success", $code = 200)
    {
        return response()->json(
            data: [
                'status' => true,
                'code' => $code,
                'message' => __($message),
                'data' => $data
            ],
            status: $code,
        );
    }

    function failed_response($data = null, $message = "Failed", $code = 400)
    {
        return response()->json(
            data: [
                'status' => false,
                'code' => $code,
                'message' => __($message),
                'data' => $data
            ],
            status: 200,
        );
    }
}
