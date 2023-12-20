#!/bin/bash
sudo rm -rf /var/www/html/*
# Clone the GitHub repository into /var/www/html
sudo git clone https://github.com/your-username/your-repo.git /var/www/html
# Install boto3 using pip
sudo pip install boto3
# Move sqs_delete_service.py to /home/ubuntu/
sudo mv /var/www/html/sqs_delete_service.py /home/ubuntu/
# Move the s3-deletion-service.service file to the correct location
sudo mv /var/www/html/s3-deletion-service.service /etc/systemd/system/
# We can now change ownership to the web server user 
chown -R www-data:www-data /var/www/html
# Reload reload for our service
sudo systemctl daemon-reload
# Start and enable the s3-deletion-service
sudo systemctl start s3-deletion-service
sudo systemctl enable s3-deletion-service
