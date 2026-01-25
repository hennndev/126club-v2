<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class ReadableFormatter extends LineFormatter
{
    public function __construct()
    {
        // Format: [YYYY-MM-DD HH:MM:SS] LEVEL: Message
        parent::__construct(
            "[%datetime%] %level_name%: %message%\n",
            "Y-m-d H:i:s",
            true,
            true
        );
    }

    public function format(LogRecord $record): string
    {
        // Custom format untuk message
        $message = $record->message;
        
        // Format context array untuk lebih readable
        if (!empty($record->context)) {
            $context = $this->formatContextAsJson($record->context);
            $message .= "\n" . $context;
        }
        
        // Replace message in record
        $formattedRecord = $record->with(message: $message, context: []);
        
        return parent::format($formattedRecord) . "\n";
    }
    
    protected function formatContextAsJson(array $context): string
    {
        if (empty($context)) {
            return '';
        }
        
        // Format context sebagai satu object JSON yang utuh
        return json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
