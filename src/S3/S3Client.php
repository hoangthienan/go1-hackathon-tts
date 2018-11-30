<?php

namespace Go1\S3;

use Aws\S3\S3Client as AwsS3Client;
use Aws\S3\Exception\S3Exception;

class S3Client
{
    private $client;
    private $bucket;

    public function __construct(array $config)
    {
        $this->client = new AwsS3Client($config);
        $this->bucket = $config['bucket'];
    }

    public function upload(string $localPath, string $fileName, string $fileType = 'audio')
    {
        !is_dir($localPath) && mkdir($localPath);

        try {
            # Upload data.
            $result = $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $fileType . '/' . $fileName,
                'Body'   => fopen($localPath . $fileName, 'r'),
                'ACL'    => 'public-read',
            ]);

            # return the URL to the object.
            return $result['ObjectURL'] . PHP_EOL;
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}