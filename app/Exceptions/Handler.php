<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $exception) {
            if (!$exception instanceof NotFoundHttpException && !$exception instanceof ValidationException)
            {
//            $message = $exception->getMessage() .' Code: '.$exception->getCode() . ' File: '. $exception->getFile() . ' Line:'. $exception->getLine();
//            Log::channel('telegram')->critical($message);
            }
        });
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if (strpos($request->url(), '/api/') !== false || strpos($request->url(), '/web/') !== false) {
            \Log::debug('API Request Exception - '.$request->url().' - '.$exception->getMessage().(!empty($request->all()) ? ' - '.json_encode($request->except(['password'])) : ''));

            if ($exception instanceof AuthorizationException) {
                return $this->setStatusCode(403)->respondWithError($exception->getMessage());
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->setStatusCode(403)->respondWithError('Please check HTTP Request Method. - MethodNotAllowedHttpException');
            }

            if ($exception instanceof AuthenticationException) {
                return $this->setStatusCode(401)->respondWithError('Unauthenticated');
            }

            if ($exception instanceof NotFoundHttpException) {
                return $this->setStatusCode(403)->respondWithError('Please check your URL to make sure request is formatted properly. - NotFoundHttpException');
            }



            if ($exception instanceof GeneralException) {
                return $this->setStatusCode(403)->respondWithError($exception->getMessage());
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->setStatusCode(403)->respondWithError(api('The requested item is not available'));
            }

            if ($exception instanceof ValidationException) {
                \Log::debug('API Validation Exception - '.json_encode($exception->validator->messages()));
                $error = "";
                if ($exception->validator->fails()) {
                    $messages = $exception->validator->messages()->toArray();
                    foreach($messages as $key => $message){
                        $error = $message[0];
                        break;
                    }
                }
                return $this->setStatusCode(422)->respondWithError($error);
            }

            /*
            * Redirect if token mismatch error
            * Usually because user stayed on the same screen too long and their session expired
            */
            if ($exception instanceof UnauthorizedHttpException) {
                switch (get_class($exception->getPrevious())) {
                    case \App\Exceptions\Handler::class:
                        return $this->setStatusCode($exception->getStatusCode())->respondWithError('Token has not been provided.');
                }
            }else{
                return $this->setStatusCode(500)->respondWithError($exception->getMessage());
            }

        }

        /*
         * Redirect if token mismatch error
         * Usually because user stayed on the same screen too long and their session expired
         */
        if ($exception instanceof TokenMismatchException) {
            if (strpos($request->url(), '/api/') !== false){
                return $this->setStatusCode(401)->respondWithError('Unauthenticated');
            }

            if (strpos($request->url(), '/manager/') == true)
            {
                $login = '/manager/login';
            }else if (strpos($request->url(), '/school/') == true)
            {
                $login = '/school/login';
            }else if (strpos($request->url(), '/teacher/') == true)
            {
                $login = '/teacher/login';
            }else{
                $login = '/login';
            }
            return redirect()->guest($login);
        }

        /*
         * All instances of GeneralException redirect back with a flash message to show a bootstrap alert-error
         */
        if ($exception instanceof GeneralException) {
            session()->flash('dontHide', $exception->dontHide);

            return redirect()->back()->withInput()->withFlashDanger($exception->getMessage());
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            if (strpos($request->url(), '/api/') !== false){
                return response()->json(['User have not permission for this page access.']);
            }else{
                return redirect()->route('manager.home')->with('message', 'User have not permission for this page access.')->with('m-class', 'error');
            }

        }
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if (strpos($request->url(), '/api/') !== false){
            return $this->setStatusCode(401)->respondWithError('Unauthenticated');
        }

        $guard = array_get($exception->guards(), 0);
        if (strpos($request->url(), '/api/') !== false){
            return $this->setStatusCode(401)->respondWithError('Unauthenticated');
        }

        if ($guard == 'manager')
        {
            $login = '/manager/login';
        }else if ($guard == 'school')
        {
            $login = '/school/login';
        }else if ($guard == 'supervisor')
        {
            $login = '/supervisor/login';
        }else if ($guard == 'teacher')
        {
            $login = '/teacher/login';
        }else{
            $login = '/login';
        }
        return redirect()->guest($login);


    }

    /**
     * get the status code.
     *
     * @return statuscode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * set the status code.
     *
     * @param [type] $statusCode [description]
     *
     * @return statuscode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * respond with error.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message)
    {
        return $this->respond([
            'success' => false,
            'status' => $this->getStatusCode(),
            'message' => $message,
        ]);
    }

    /**
     * Respond.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
