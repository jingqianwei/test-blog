<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Mail;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * 报告或记录异常
     * Report or log an exception.
     *此处是发送异常给 Sentry、Bugsnag 等外部服务的好位置。
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if(empty(env('APP_DEBUG')) && $exception->getMessage() && $exception->getCode() != -1) {
            $raw = '';
            if ('cli' !== PHP_SAPI) {
                ob_start();
                dump(\Request::server());
                $raw = ob_get_contents();
                ob_end_clean();
            }

            Mail::raw('', function ($m) use ($exception, $raw) {
                $exceptionHandler = new \Symfony\Component\Debug\ExceptionHandler();
                $content = $exceptionHandler->getHtml($exception);
                $m->setBody($content . $raw, 'text/html');

                if (config('app.name')) {
                    $errName = config('app.name') . '_' . config('app.env');
                } else {
                    $errName = config('app.env');
                }
                $m->subject('System Error--->' . $errName);
                $m->to('chinwe.jing@etocrm.com');
            });
        }
        parent::report($exception);
    }

    /**
     * 将异常转换为 HTTP 响应。
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (config('app.debug')) { //本地开发环境，输出错误
            return parent::render($request, $exception);
        }

        if ($exception instanceof CustomException) {
            return $exception->render($request, $exception);
        }
    }
}
