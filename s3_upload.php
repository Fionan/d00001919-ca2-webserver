<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// Include the AWS SDK for PHP
require 'vendor/autoload.php';

// AWS credentials and configuration

if(file_exists('.env')){
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}



$aws_access_key = $_ENV['AWS_ACCESS_KEY'];
$aws_secret_key = $_ENV['AWS_SECRET_KEY'];
$bucket_name = $_ENV['AWS_BUCKET_NAME'];
$region = $_ENV['AWS_REGION'];

// Instantiate an S3 client
$s3Client = new S3Client([
    'version' => 'latest',
    'region' => $region,
    'credentials' => [
        'key'    => $aws_access_key,
        'secret' => $aws_secret_key,
    ],
]);

// Generate a pre-signed URL for a PUT operation
$objectKey = 'uploads/' . uniqid('file_') . '.mp4'; // Change the file name as needed
$command = $s3Client->getCommand('PutObject', [
    'Bucket' => $bucket_name,
    'Key'    => $objectKey,
]);

$request = $s3Client->createPresignedRequest($command, '+20 minutes');

$presignedUrl = (string) $request->getUri();
ob_clean();
header('Content-Type: application/json');

echo json_encode(['presignedUrl' => $presignedUrl]);
exit;
?>
