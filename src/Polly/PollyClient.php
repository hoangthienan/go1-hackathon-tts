<?php

namespace Go1\Polly;

use Aws\Polly\PollyClient as AwsPollyClient;

Class PollyClient
{
    private $client;

    public function __construct($config)
    {
        $this->client = new AwsPollyClient($config);
    }

    public function speech(string $localPath, string $fileName, array $speech)
    {
        !is_dir($localPath) && mkdir($localPath);

        # get speech
        $response = $this->client->synthesizeSpeech($speech);

        # save response file
        file_put_contents($localPath . $fileName, $response['AudioStream']);

        return $response;
    }
}
