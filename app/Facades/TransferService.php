<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TransferService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'transfer.service';
    }
}
