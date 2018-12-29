<?php
namespace Aries\Seppay;

use Aries\Seppay\Traits\Data;
use Aries\Seppay\Traits\Request;

class Pay {

    use Data, Request;

    public function __construct()
    {
        $this->factorNumber = null;
    }

    public function ready()
    {
        $params                 =   [];
        $params['api']          =   config('Seppay.api');
        $params['amount']       =   $this->amount;
        $params['factorNumber'] =   $this->factorNumber;
        $params['redirect']     =   $this->callback;
        $params['mobile']       =   $this->mobile;
        $params['description']  =   $this->description;

        $res = $this->send_request("https://pay.ir/pg/send", $params, false);

        if($res->status == 1) {
            $this->transId = $res->token;
        } else {
            throw new SendException($res->errorCode);
        }

        return $res;
    }

    public function start()
    {
        return redirect()->to("https://pay.ir/pg/". $this->transId);
    }

    public function verify()
    {
        $params['api']      = config('Seppay.api');
        $params['token']  = $_REQUEST['token'];

        $transaction    = \DB::table('transactions')->where('transId', '=', $params['token']);

        $res            = $this->send_request("https://pay.ir/pg/verify", $params);

        if($res->status != 1) {
            $transaction->update([
                'status' => 'FAILED'
            ]);
            throw new VerifyException($res->errorCode);
        } else {

			$transaction->update([
				'status'        =>  'SUCCESS'
			]);

			return $res;
		}
    }
}
