<?php

error_reporting(E_ALL);

define('BASE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('UPLOAD_DIR', BASE_DIR . 'uploads' . DIRECTORY_SEPARATOR);

require(BASE_DIR . 'vendor/autoload.php');

$config = require(BASE_DIR . 'config.php');

$speech = [

    # Change this to whatever SSML you want to convert to audio
    'Text'         => '
    <speak>
    Hi! My name is Emma.
    Welcome to the Amazon Polly demo.
    Today is <say-as interpret-as="date">????1201</say-as>
    </speak>',
    'OutputFormat' => 'mp3',
    'TextType'     => 'ssml',
    'VoiceId'      => 'Emma',
];

use Go1\Polly\PollyClient;

# get service handle
try {
    $pollyClient = new PollyClient($config['pollyOptions']);
} catch (\Exception $e) {
    print_r($e);
}

$fileName = 'demo-ssml.mp3';
$response = $pollyClient->speech(UPLOAD_DIR, $fileName, $speech);
