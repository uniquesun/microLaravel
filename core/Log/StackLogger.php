<?php

namespace Core\Log;

use Psr\Log\AbstractLogger;

class StackLogger extends AbstractLogger
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function log($level, $message, array $context = array()): void
    {
        if (is_array($message)) {
            $message = var_export($message, true) . var_export($context, true);
        } else if (is_string($message)) {
            $message = $this->interpolate($message, $context);
        }

        $message = sprintf($this->config['format'], date('y-m-d h:m:s'), $level, $message);

        error_log($message . PHP_EOL, 3, $this->config['path'] . '/log.text');
    }

    protected function interpolate($message, array $context = array()): string
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }


}