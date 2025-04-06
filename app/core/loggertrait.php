<?php
namespace App\Core;

/**
 * Logging Trait
 * Provides common logging functionality
 */
trait LoggerTrait {
    /**
     * Log activity to file
     * @param string $message
     */
    public function logActivity($message) {
        $logFile = __DIR__ . '/../../logs/activity.log';
        
        // Create logs directory if it doesn't exist
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        file_put_contents(
            $logFile,
            "[$timestamp] " . $message . PHP_EOL,
            FILE_APPEND
        );
    }
}