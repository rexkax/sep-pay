<?php
namespace aries\seppay;

use GuzzleHttp\Client;

class Pay {
    private $client;
    private $result;

    private $amount;
    private $callback;
    private $factorNumber;

    public function __construct()
    {
        $this->client = new Client([
            'verify'    =>  false
        ]);

        $this->factorNumber = null;
    }

    public function ready()
    {
        $params = [];
        $params['api']  =  config('seppay.api');
        $params['amount']   =   $this->amount;
        if ($this->factorNumber != null)
            $params['factorNumber'] =   $this->factorNumber;
        $params['redirect'] =   $this->callback;

        #$res = $this->client->request('POST', 'https://pay.ir/payment/send?api=test&amount=1000&redirect='.url('/'));
        $res = $this->client->post('https://pay.ir/payment/send', [
            'form_params'  =>  $params
        ]);

        $this->result = json_decode($res->getBody());
        return $this->result;
    }

    public function start()
    {
        return "https://pay.ir/payment/gateway/". $this->result->transId;
        #return redirect("https://pay.ir/payment/gateway/". $this->result->transId);
    }

    public function amount($amount)
    {
        $this->amount = $amount;
    }

    public function callback($url)
    {
        $this->callback = urlencode($url);
    }

    public function factorNumber($number = null)
    {
        $this->factorNumber = $number;
    }
}