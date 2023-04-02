<?php

namespace Core\Kernel;

use Throwable;

class HandleExceptions
{
    protected $ignore = [];

    public function init()
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
    }

    // https://www.php.net/manual/en/function.restore-error-handler.php
    public function handleError($code, $message, $file = '', $line = 0)
    {
        //app('response')->setContent('<h1>' . $message . '</h1>')->setCode(500)->send();
        app('log')->error($message . ' at ' . $file . ':' . $line);
    }

    public function handleException(Throwable $e)
    {
        if (method_exists($e, 'render')) {
            app('response')->setContent(
                $e->render()
            )->send();
        }

        if (!$this->isIgnore($e)) {
            app('log')->debug($e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            echo $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
        }
    }

    protected function isIgnore($e): bool
    {
        $ignore = false;
        foreach ($this->ignore as $ignore_exception) {
            if ($e instanceof $ignore_exception) {
                $ignore = true;
                break;
            }
        }
        return $ignore;
    }


}