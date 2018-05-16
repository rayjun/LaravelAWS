<?php
namespace Rayjun\AWS\Signature;


use DateTime;

class AWS4Signature {

    //aws secret key
    private $secretKey;

    //aws access key
    private $accessKey;

    //aws region
    private $region;

    //aws service
    private $service;

    // hash algorithm
    private $alg = "sha256";

    //aws header algorithm
    private $algorithm = "AWS4-HMAC-SHA256";

    //x-amz-date
    private $xAmzDate;

    //amzDate
    private $amzDate;

    //host
    private $host;

    public function __construct() {
        $this->secretKey = config("aws")["secretKey"];
        $this->accessKey = config("aws")["accessKey"];
        $this->region = config("aws")["region"];
        $this->service = config("aws")["service"];
        $this->host = config("aws")["host"];
    }


    public function signV4Headers($uri,$opt=[]) {
        $options = array(); 
        $headers = array();

        $now = new DateTime("UTC");

        $this->xAmzDate = $now->format("Ymd\THis\Z");
        $this->amzDate = $now->format("Ymd");

        $method = "GET";
        if(isset($opt['method'])) {
            $method = $opt['method'];
        }
        $contentType = "application/json";
        if(isset($opt['contentType'])) {
            $contentType = $opt['contentType'];
        }

        $query = "";
        if(isset($opt['query'])) {
            $query = $opt['query'];
        }

        $payload = "";
        if(isset($opt['payload'])) {
            $payload = $opt['payload'];
        }

        $hashedPayload = hash($this->alg, $payload);
        $canonicalUri = $uri;
        $canonicalQueryString = $query;


        $canonicalHeadersArr = [
            "content-type:".$contentType,
            "host:".$this->host,
            "x-amz-date:".$this->xAmzDate
         ];

        $canonicalHeaders = implode("\n", $canonicalHeadersArr)."\n";

        $signedHeaders = 'content-type;host;x-amz-date';

        $canonicalRequestArr = [
            $method,
            $canonicalUri,
            $canonicalQueryString,
            $canonicalHeaders,
            $signedHeaders,
            $hashedPayload
        ];

        $canonicalRequest = implode("\n", $canonicalRequestArr);

        $credentialScopeArr = [
            $this->amzDate,
            $this->region,
            $this->service,
            'aws4_request'
        ];

        $credentialScope = implode("/", $credentialScopeArr);

        $stringToSign  = "".$this->algorithm."\n".$this->xAmzDate ."\n".$credentialScope."\n".hash('sha256', $canonicalRequest)."";

        $kSecret = 'AWS4' . $this->secretKey;
        $kDate = hash_hmac( $this->alg, $this->amzDate, $kSecret, true );
        $kRegion = hash_hmac( $this->alg, $this->region, $kDate, true );
        $kService = hash_hmac( $this->alg, $this->service, $kRegion, true );
        $kSigning = hash_hmac( $this->alg, 'aws4_request', $kService, true );     
        $signature = hash_hmac( $this->alg, $stringToSign, $kSigning ); 
        $authorizationHeader = $this->algorithm . ' ' . 'Credential=' . $this->accessKey .
        '/' . $credentialScope . ', ' .  'SignedHeaders=' . $signedHeaders .
        ', ' . 'Signature=' . $signature;

        $headers = [
            'content-type'=> $contentType, 
            'x-amz-date'=> $this->xAmzDate, 
            'Authorization'=> $authorizationHeader
        ];
        
        return $headers;
    }

}
