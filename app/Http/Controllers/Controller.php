<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function ResponseJson($status, $message, $result, $code)
    {

        return response()->json([
            'error' => $status,
            'msg' => $message,
            'data' => $result
        ], $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendError($status, $errorMessages, $error = [], $code = 404)
    {

        $response = [
            'success' => false,
            'message' => $errorMessages,
        ];
        if (!empty($error)) {
            $response['data'] = $error;
        }
        return response()->json($response, $code);
    }
}
