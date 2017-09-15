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
        $this->factorNumber = null;
    }

    public function ready()
    {
        $params = [];
        $params['api']  =  config('seppay.api');
        $params['amount']   =   $this->amount;
        $params['factorNumber'] =   $this->factorNumber;
        $params['redirect'] =   $this->callback;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://pay.ir/payment/send");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "api={$params['api']}&amount={$params['amount']}&redirect={$params['redirect']}&factorNumber={$params['factorNumber']}");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res);

        if($res->status == 1) {
            $this->transId = $res->transId;
        } else {
            throw new SendException($res->errorCode);
        }

        return $res;
    }

    public function start()
    {
        return redirect()->to("https://pay.ir/payment/gateway/". $this->transId);
    }

    public function verify()
    {
        $api = config('seppay.api');
        $transId = $_REQUEST['transId'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://pay.ir/payment/verify");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "api={$api}&transId={$transId}");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res);

        if($res->status != 1) {
            throw new VerifyException($res->errorCode);
        }

        return $res;
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