# LaravelAWS


## Useage

```
composer require rayjun/laravel-aws
```
```
php artisan vendor:publish 
```

```php

use Rayjun\AWS\Lambda\Client;

$result = $client->request("GET", "/dev/",["user"=> "1"]);
 
echo $result->getBody()->getContents()

```



