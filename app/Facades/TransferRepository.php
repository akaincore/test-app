<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TransferRepository extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'transfer.repository';
    }
}
