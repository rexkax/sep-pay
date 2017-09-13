<?php
namespace aries\seppay;

use GuzzleHttp\Client;

class Pay {
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify'    =>  false
        ]);
    }

    public function set()
    {
        $res = $this->client->request('POST', 'https://pay.ir/payment/send?api=test&amount=1000&redirect='.url('/'));

        return $res;
    }
}