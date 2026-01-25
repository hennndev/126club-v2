<?php

namespace App\Logging;

use Monolog\Logger;

class CustomLogger
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('readable');
        
        $handler = new \Monolog\Handler\StreamHandler(
            storage_path('logs/laravel.log'),
            Logger::DEBUG
        );
        
        $handler->setFormatter(new ReadableFormatter());
        
        $logger->pushHandler($handler);
        
        return $logger;
    }
}
