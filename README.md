# Video ffmpeg reduce/rename via AWS cloud

Brief project description goes here.

## Table of Contents

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)


## Introduction

This is the repository for code used for my CA2 project for Cloud Technologies DKIT.
This repository contains scripts and code required to allow the user to upload to an S3 bucket via EC2 webserver.

TO-DO
We will later add a system damon to monitor a SQS queue and remove processed videos from our S3 bucket.

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


