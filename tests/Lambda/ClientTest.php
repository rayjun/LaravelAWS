<?php

namespace Rayjun\AWS\Lambda;

use Rayjun\AWS\Signature\AWS4Signature;

use Rayjun\AWS\Lambda\Client;

class ClientTest extends \PHPUnit_Framework_TestCase {

    public function testClient()
    {
        $client= new Client();
        
        $result = $client->request("GET", "/dev/",["user"=> "1"]);

        //$result = $client->request("POST", "/dev/users/",["user" => "1"]);
    
        $this->assert($result != null);
    }
}