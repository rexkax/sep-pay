<?php
namespace Aries\Seppay\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    protected $fillable = [
        'amount',
        'transId',
        'factorNumber'
    ];

    public function payable()
    {
        return $this->morphTo();
    }
}