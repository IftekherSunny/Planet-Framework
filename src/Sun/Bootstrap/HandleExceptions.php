<?php

namespace Sun\Bootstrap;

use Sun\Contracts\Http\Response;
use Sun\Contracts\Application as App;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Console\Application as ConsoleApplication;

class HandleExceptions
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * @var \Sun\Contracts\Http\Response
     */
    protected $response;

    /**
     * @var \Sun\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new instance
     *
     * @param \Sun\Contracts\Application           $app
     * @param \Sun\Contracts\Http\Response         $response
     * @param \Sun\Contracts\Filesystem\Filesystem $filesystem
     */
    public function __construct(App $app, Response $response, Filesystem $filesystem)
    {
        $this->app = $app;

        $this->response = $response;

        $this->filesystem = $filesystem;
    }

    /**
     * Bootstrap error & exception handler
     */
    public function bootstrap()
    {
        error_reporting(-1);

        set_error_handler([$this, 'errorHandler']);

        set_exception_handler([$this, 'exceptionHandler']);

        register_shutdown_function([$this, 'shutdownHandler']);

        ini_set("display_errors", 0);
    }

    /**
     * To handle application error
     */
    public function errorHandler()
    {
        ErrorHandler::register();
    }

    /**
     * To handle application exception
     *
     * @param $message
     */
    public function exceptionHandler($message)
    {
        $errorCode = $message->getCode()?: 404;

        if($this->customErrorViewExist($errorCode)) {
            $this->response->send(
                view("errors.{$errorCode}")
            );
        }

        error_log("planet.ERROR ".$message->getMessage() . "\nStack trace:\n" . $message->getTraceAsString());

        if(php_sapi_name() == 'cli') {
            (new ConsoleApplication)->renderException($message, new ConsoleOutput);
        } else {
            $debugMode = filter_var($this->app->config->getApp('debug'), FILTER_VALIDATE_BOOLEAN);

            $exceptionHandler = new ExceptionHandler($debugMode);

            $exceptionHandler->sendPhpResponse($message);
        }
    }

    /**
     * Shutdown handler executed after script execution finishes
     */
    public function shutdownHandler()
    {
        $error = error_get_last();

        if(!is_null($error)) {
            $this->exceptionHandler(new FatalErrorException($error['message'], $error['type'], 0, $error['file'], $error['line'], 0));
        }
    }

    /**
     * Generate the view file path for the given error code.
     *
     * @param $errorCode
     *
     * @return string
     */
    protected function generatePath($errorCode)
    {
        return $this->app->app_path() . "/Views/errors/{$errorCode}.blade.php";
    }

    /**
     * Check existence of the custom error view.
     *
     * @param $errorCode
     *
     * @return bool
     */
    private function customErrorViewExist($errorCode)
    {
        return $this->filesystem->exists($this->generatePath($errorCode));
    }
}