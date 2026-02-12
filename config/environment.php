<?php

$env = env('ENVIRONMENT', 'local');
$location = env('LOCATION', 'LOCAL');

if ($location === 'LOCAL') {
    $file = base_path("environments/{$env}.php");
    $config = require $file;
} else {
    $file = base_path("environments/{$env}_server.php");
    $encrypted = require $file;
    $key = base64_decode(str_replace('base64:', '', env('APP_KEY')));
    $encrypter = new \Illuminate\Encryption\Encrypter($key, 'AES-256-CBC');
    $config = unserialize($encrypter->decryptString($encrypted));
}

return $config;