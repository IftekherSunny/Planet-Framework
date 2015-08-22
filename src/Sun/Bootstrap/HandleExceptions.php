<?php

namespace Sun\Bootstrap;

use Sun\Contracts\Application as App;
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
     * @var \Sun\Contracts\Log\Log
     */
    protected $log;

    /**
     * Create a new instance
     *
     * @param \Sun\Contracts\Application $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
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
}