<?php

namespace Rayjun\AWS\Lambda;

use Rayjun\AWS\Signature\AWS4Signature;
use GuzzleHttp\Client as GuzzleClient;
use DateTime;

class Client {
   
    //query
    private $query;

    //payload
    private $payload;

    //host
    private $host;

    //baseUrl
    private $baseUrl;

    //api
    private $api;

    //aws4 signature
    private $signature;

    //guzzleClient
    private $guzzleClient;

    public function __construct() {
        $this->host = config("aws")["host"];
        $this->baseUrl = "https://".$this->host;
        $this->signature = new AWS4Signature();
        $this->guzzleClient = new GuzzleClient();
    }


    public function request($method, $uri, array $data){
        $this->api = $this->baseUrl.$uri;

        ksort($data);
        $opt = ["method" => $method];

        if($method == "GET" || $method == "DELETE")
        {
            ksort($data);
            foreach ($data as $key => $value) {
                $this->query .= $key.'='.$value.'&';                
            }
            $this->query = substr($this->query, 0, -1);
            $opt["query"] = $this->query;
            $this->api .= "?".$this->query;
        }
        else {
            $this->payload = json_encode($data);
            $opt["payload"] = $this->payload;
            $config["body"] = $this->payload;
        }

        $headers = $this->signature->signV4Headers($uri, $opt);
        
        $config["headers"] = $headers;

        $result = $this->guzzleClient->request($method, $this->api, $config);

        return $result;
    }

  }