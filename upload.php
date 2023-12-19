<?php

// AWS SDK for PHP
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// AWS credentials and configuration
$aws_access_key = 'YOUR_AWS_ACCESS_KEY';
$aws_secret_key = 'YOUR_AWS_SECRET_KEY';
$bucket_name = 'YOUR_S3_BUCKET_NAME';
$region = 'YOUR_REGION';

// AWS SNS topic ARN
$sns_topic_arn = 'YOUR_SNS_TOPIC_ARN'; // To be replaced with your SNS topic ARN

// File upload directory
$upload_dir = 'uploads/';

// TODO: Implement a function to publish a message to the SNS topic
function publishToSnsTopic($message)
{
    // TODO: Implement the logic to publish a message to the SNS topic
    // Example:
    // $sns = new SnsClient([
    //     'version' => 'latest',
    //     'region' => $region,
    //     'credentials' => [
    //         'key'    => $aws_access_key,
    //         'secret' => $aws_secret_key,
    //     ],
    // ]);
    //
    // $sns->publish([
    //     'TopicArn' => $sns_topic_arn,
    //     'Message'  => $message,
    // ]);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize "company name" and "description"
    $company_name = filter_input(INPUT_POST, 'companyname', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // Check if the strings are not empty
    if (empty($company_name) || empty($description)) {
        die('Error: Company name and description are required.');
    }

    // Check if the form was submitted with a file
    if (!empty($_FILES['file']['name'])) {
        // Check if there are no errors during the file upload
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Perform additional sanity checks
            $file_type = mime_content_type($_FILES['file']['tmp_name']);
            $file_size = $_FILES['file']['size'];

            // Check if the file is a video
            if (strpos($file_type, 'video/') === false) {
                die('Error: Only video files are allowed.');
            }

            // Check if the file size is less than 4 GB
            if ($file_size > 4 * 1024 * 1024 * 1024) {
                die('Error: File size exceeds 4 GB limit.');
            }

            // Create an S3 client
            $s3 = new S3Client([
                'version' => 'latest',
                'region' => $region,
                'credentials' => [
                    'key'    => $aws_access_key,
                    'secret' => $aws_secret_key,
                ],
            ]);

            // Generate a unique file name
            $file_name = uniqid('video_') . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            // Upload the file to the S3 bucket
            try {
                $result = $s3->putObject([
                    'Bucket' => $bucket_name,
                    'Key'    => $file_name,
                    'Body'   => fopen($_FILES['file']['tmp_name'], 'rb'),
                    'ACL'    => 'public-read',
                ]);

                // Publish a message to the SNS topic
                $message = 'New video uploaded: ' . $result['ObjectURL'];
                publishToSnsTopic($message);

                // Display success message
                echo 'File uploaded successfully. S3 URL: ' . $result['ObjectURL'];
            } catch (S3Exception $e) {
                die('Error uploading file to S3: ' . $e->getMessage());
            }
        } else {
            // Display error message based on the file upload error
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    die('Error: File size exceeds the upload limit.');
                case UPLOAD_ERR_PARTIAL:
                    die('Error: The uploaded file was only partially uploaded.');
                case UPLOAD_ERR_NO_FILE:
                    die('Error: No file was uploaded.');
                default:
                    die('Error: File upload failed. Please try again.');
            }
        }
    } else {
        // Display error message if no file is submitted
        die('Error: No file was submitted.');
    }
}

?>
