<?php

$key = ''; # Change these to your respective AWS credentials
$secret = '';

return [
    'pollyOptions' => [
        'version'     => 'latest',
        'region'      => 'ap-southeast-1', # Change this to your respective AWS region
        'credentials' => [
            'key'    => $key,
            'secret' => $secret,
        ],
    ],
    's3Options'    => [
        'version'     => 'latest',
        'region'      => 'ap-southeast-1',
        'credentials' => new \Aws\Credentials\Credentials($key, $secret),
        'bucket'      => 'hackathon',
    ],
];
