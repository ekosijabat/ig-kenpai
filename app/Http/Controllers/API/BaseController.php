<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller {

    /**
     * success response method
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message = '') {
        $response = [
            'status'    => 200,
            'success'   => true,
            'data'      => $result,
            'message'   => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessage = [], $code = 404) {
        $response = [
            'status'    => $code,
            'success'   => false,
            'message'   => $error,
        ];

        if (!empty($errorMessage)) {
            $response['data'] = $errorMessage;
        }

        return response()->json($response, $code);
    }
}
