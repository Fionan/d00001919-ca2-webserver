# Video ffmpeg reduce/rename via AWS cloud

The project allows a user to upload to a video to an S3 Bucket via an EC2 Apache webserver.
It is designed to work on low powered EC2 instance.


## Table of Contents

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)


## Introduction

This is the repository for code used for my CA2 project for Cloud Technologies DKIT.
This repository contains scripts and code required to allow the user to upload to an S3 bucket via EC2 webserver.

The install script, should be run on an EC2 instance in AWS cloud. It will clone this repo and install the SQS deletion service.

## Requirements

Ensure that you have the following software and dependencies installed before setting up and running the project:

- PHP
- Apache2 with PHP module
- Git
- AWS CLI
- Composer


### Installation 

To install the required packages on a Debian-based system, you can use the following commands:

```bash
wget https://raw.githubusercontent.com/Fionan/d00001919-ca2-webserver/main/setup.sh
chmod +x setup.sh
./setup.sh


