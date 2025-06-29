<?php

namespace YSM\WithTransaction\Facades;

use Illuminate\Support\Facades\Facade;

class Transaction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'with-transaction';
    }
}
