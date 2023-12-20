import boto3
import time

# Specify SQS queue URL
queue_url = 'https://sqs.eu-west-1.amazonaws.com/447855643453/d00001919-ca2-delete-video-queue'

# Specify your AWS region
aws_region = 'eu-west-1'



# Create an SQS and S3 clients
sqs = boto3.client('sqs','eu-west-1')
s3 = boto3.client('s3','eu-west-1')


# Specify your S3 bucket name
bucket_name = 'd00001919-testbucket'

while True:
    # Receive messages from the queue
    response = sqs.receive_message(
        QueueUrl=queue_url,
        AttributeNames=['All'],
        MessageAttributeNames=['All'],
        MaxNumberOfMessages=1,
        VisibilityTimeout=0,
        WaitTimeSeconds=0
    )

    # Process the received message if available
    if 'Messages' in response:
        print("Message found!")
        message = response['Messages'][0]
        # Extract the body from the message
        s3_object_key = message['Body']
        print(s3_object_key)
        #  We will use our s3 client here: and view the repsonse
        response2= s3.delete_object(Bucket=bucket_name, Key=s3_object_key)
        print(response2)
        print("attempting delete")
        # Delete the message from the queue
        receipt_handle = message['ReceiptHandle']
        sqs.delete_message(QueueUrl=queue_url, ReceiptHandle=receipt_handle)
        print("now we sleep")
    # Sleep for 5 minutes before checking the queue again
    time.sleep(5)  # change BEFORE uplaoding sleeping for 5 minutes

