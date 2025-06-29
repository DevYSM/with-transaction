<?php

namespace YSM\WithTransaction\Facades;

use Illuminate\Support\Facades\Facade;
use YSM\WithTransaction\Builders\TransactionBuilder;

class Transaction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TransactionBuilder::class;
    }
}
