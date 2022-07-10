<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendResponse($result, $message = 'success',$code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'status' => $code,
        ];
        return response()->json($response, 200);
    }

    protected function sendError($error, $code = 200, $errorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => $error,
            'status' => $code,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    protected function redirectWith($back = false, string $route = null, string $message, $status = 'success'): \Illuminate\Http\RedirectResponse
    {
        if ($back)
            return redirect()->back()->with('message', $message)->with('m-class', $status);

        return redirect()->route($route)->with('message', $message)->with('m-class', $status);
    }
}
