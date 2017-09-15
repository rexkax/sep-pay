##Sep Pay
*Payment Package with Saman Electronic Payment (pay.ir) over iran Shaparak Network for Laravel*

***installing:***
-------------
run this command:

``composer require aries/seppay``

add this line to `config/app.php`:

```php
'provider'  =>  [
    ...
    aries/seppay/SeppayServiceProvider::class,
]
```

and run this command:

`php artisan vendor:publish`


***Usage:***
---------
```php
<?php

namespace App\Http\Controllers;

use aries\seppay\Pay;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        try {
            $pay = new Pay();
            $pay->amount(1000);
            $pay->callback(url('/'));
            $response = $pay->ready();
            
            /*
             * do anything you want with $response Object
             * Like: store Transaction ID on your cart with: $response->transId;
             */
            
            return $pay->start();

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function callback()
    {
        try {
            $pay = new Pay();
            $response = $pay->verify();
            
            /*
             * if verification was successful you can send order for your customer
             */
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
```