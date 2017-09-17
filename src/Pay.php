<?php
namespace Aries\Seppay;

class Pay {

    use Data, Request;

    public function __construct()
    {
        $this->factorNumber = null;
    }

    public function ready()
    {
        $params = [];
        $params['api']  =  config('Seppay.api');
        $params['amount']   =   $this->amount;
        $params['factorNumber'] =   $this->factorNumber;
        $params['redirect'] =   $this->callback;

        #dd($this->create_data($params));

        $res = $this->send_request("https://pay.ir/payment/send", $params, false);
        #dd($res);
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
        $params['api'] = config('Seppay.api');
        $params['transId'] = $_REQUEST['transId'];

        #dd($this->create_data($params));
        $res = $this->send_request("https://pay.ir/payment/verify", $params);

        if($res->status != 1) {
            throw new VerifyException($res->errorCode);
        }

        return $res;
    }
}