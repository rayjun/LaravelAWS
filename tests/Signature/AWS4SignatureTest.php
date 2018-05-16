<?php

namespace Rayjun\AWS\Signature;


class AWS4SignatureTest extends \PHPUnit_Framework_TestCase {

    public function testGetHeaders() 
    {
        $aws4Signature = new AWS4Signature();

        $result = $aws4Signature->signV4Headers("/dev", ["method" =>"GET", "query" => "user=1"]);
        $this->assert(true);
    }
}