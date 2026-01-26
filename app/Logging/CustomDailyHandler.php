<?php

namespace App\Logging;
use Monolog\Handler\RotatingFileHandler;

class CustomDailyHandler extends RotatingFileHandler
{
    protected function getTimedFilename(): string
    {
        $fileInfo = pathinfo($this->filename);
        $timedFilename = $fileInfo['filename'] . '_' . date($this->dateFormat ?: 'Y-m-d') . '.' . $fileInfo['extension'];
        if (!empty($fileInfo['dirname'])) {
            $timedFilename = $fileInfo['dirname'] . DIRECTORY_SEPARATOR . $timedFilename;
        }
        return $timedFilename;
    }
}