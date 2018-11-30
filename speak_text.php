<?php

error_reporting(E_ALL);

define('BASE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('UPLOAD_DIR', BASE_DIR . 'uploads' . DIRECTORY_SEPARATOR);

require(BASE_DIR . 'vendor/autoload.php');

$config = require(BASE_DIR . 'config.php');

use Go1\Polly\PollyClient;
use Go1\S3\S3Client;
use Go1\GO1\LoClient;

$fileName = 'demo.mp3';
$text = 'Hi! My name is Emma. Welcome to the Amazon Polly demo.';
if (isset($_GET['loId']) && isset($_GET['instanceId'])) {
    $loId = $_GET['loId'];
    $instanceId = $_GET['instanceId'];

    $fileName = "instance-$instanceId-loid-$loId.mp3";

    $params = [
        'instanceId' => $instanceId,
        'loId'       => $loId,
    ];

    $loClient = new LoClient($config['loOptions']['jwt']);
    $lo = $loClient->get('dev', $params);
    $text = $lo[0]['title'] ?? '';
    $text && $text .= ' ';
    $text .= $lo[0]['description'] ?? '';
    $text = strip_tags($text);

    if (!$text) {
        $text = 'Error: Learning object not found';
    }
}

$speech = [

    # Change this to whatever text you want to convert to audio
    'Text'         => $text,
    'OutputFormat' => 'mp3',
    'TextType'     => 'text',
    'VoiceId'      => 'Emma',

];

# get service handle
try {
    $pollyClient = new PollyClient($config['pollyOptions']);
    $s3Client = new S3Client($config['s3Options']);

    $response = $pollyClient->speech(UPLOAD_DIR, $fileName, $speech);
    $s3Url = $s3Client->upload(UPLOAD_DIR, $fileName);

    echo json_encode(['text' => $text, 's3_url' => $s3Url], 200) . PHP_EOL;
} catch (\Exception $e) {
    print_r($e);
}

