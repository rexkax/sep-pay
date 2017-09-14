<?php
namespace aries\seppay;

use GuzzleHttp\Client;

class Pay {
    private $client;
    private $result;
    private $transId;

    private $amount;
    private $callback;
    private $factorNumber;

    public function __construct()
    {
        $this->client = new Client([
            'verify'    =>  false,
            'timeout'   =>  5
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

        $resp = json_decode($res->getBody());

        $this->transId = $resp->transId;

        return $resp;
    }

    public function start()
    {
        return redirect()->to("https://pay.ir/payment/gateway/". $this->transId);
    }

    public function verify()
    {
        if(request()->input('status') == 1) {
            $res = $this->client->post('https://pay.ir/payment/verify', [
                'form_params'   =>  [
                    'api'       =>  config('seppay.api'),
                    'transId'   =>  request()->input('transId')
                ]
            ]);

            $res = json_decode($res->getBody());

            if($res->status == 1) {
                return true;
            } else {
                return false;
            }
        }

        return false;
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