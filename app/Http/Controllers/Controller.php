<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $validationRules = [];
    protected $validationMessages = [];

    const ADDMESSAGE = "تم الإضافة بنجاح";
    const EDITMESSAGE = "تم التعديل بنجاح";
    const DELETEMESSAGE = "تم الحذف بنجاح";

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

    protected function uploadFile($file,$path = '', $with_date = true){
        $fileName = $file->getClientOriginalName();
        $file_exe = $file->getClientOriginalExtension();
        $new_name = uniqid().'.'.$file_exe;
        if ($with_date){
            $directory = 'uploads'.'/'.$path.'/'.date("Y").'/'.date("m").'/'.date("d");
        }else{
            $directory = 'uploads'.'/'.$path;
        }
        $destination = public_path($directory);
        $file->move($destination , $new_name);
        return $directory.'/'.$new_name;
    }
//    protected function uploadImage($file,$path = ''){
//        $fileName = $file->getClientOriginalName();
//        $file_exe = $file->getClientOriginalExtension();
//        $new_name = uniqid().'.'.$file_exe;
//        $directory = 'uploads'.'/'.$path;
//        $destination = public_path($directory);
//        $file->move($destination , $new_name);
//        return $directory.'/'.$new_name;
//    }
}
