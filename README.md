<p align="center"><a href="https://devsarfo.io" target="_blank"><img src="https://devsarfo.io/assets/images/photo.png" width="250"></a></p>


# Pangaea Take-home Assignment 

For this challenge we'll be creating a HTTP notification system. A server (or set of servers) will keep track of topics ->
subscribers where a topic is a string and a subscriber is an HTTP endpoint. When a message is published on a topic, it
should be forwarded to all subscriber endpoints.

## How to start the application
- Create a database to store application database
- Configure database in .env or in config/database.php
- Open the console and cd to project root directory
- Run php artisan key:generate
- Run php artisan migrate
- Run php artisan serve
- You can now access the application at 127.0.0.1:8000


## Publishing Server Requirements
This project using no 3rd party to send message, instead it uses laravel's scheduler to queue and send messages to endpoints.
 
You can add the scheduler cron entry to your local development machine using: 

    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev null 2>&1

Also you you may use the schedule:work Artisan command. This command will run in the foreground and invoke the scheduler every minute until you terminate the command:

    php artisan schedule:work

## Publisher Server Endpoints
api/ is added because the api route is used to ignore CSFR token errors in web.php

### Create Subscription

**POST /api/subscribe/{topic}**

**Expected Body** 

    { 
        url : string 
    }

**Successful Response** 

    { 
        topic : string, 
        url: string 
    }

**Unsuccessful Response** 
    
    { 
        error : bool, 
        message: string 
    }


### Publish Message to Topic

**POST /api/publish/{topic}**

**Expected Body** 
    
    {
        [key: string]: any
    }

**Successful Response** 

    {
        success: bool,
        message: string
    }

**Unsuccessful Response** 

    { 
        error : bool, 
        message: string 
    }