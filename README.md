# LaravelAWS


## Usage

Install by [composer](https://getcomposer.org/)
``` shell
composer require rayjun/laravel-aws
```

Add ServiceProvider to config/app.php
```
Rayjun\AWS\AWSServiceProvider::class,

```

Get aws.php config file
```shell
php artisan vendor:publish 
```

```php
<?php
return [
    
    "secretKey" => "",

    "accessKey" => "",

    "region" => "",

    "service" => "",

    "host" => ""
];
```

Code example
```php

use Rayjun\AWS\Lambda\Client;

$result = $client->request("GET", "/dev/",["user"=> "1"]);
 
echo $result->getBody()->getContents()

```



