<?php

declare(strict_types=1);

class Logger
{
    public static function error(string $message): void
    {
        $file = dirname(__DIR__, 2) . '/storage/logs/app.log';
        $date = date('Y-m-d H:i:s');
        file_put_contents($file, "[$date] ERROR: $message" . PHP_EOL, FILE_APPEND);
    }
}
