<?php

namespace YSM\WithTransaction;

use Illuminate\Support\ServiceProvider;
use YSM\WithTransaction\Builders\TransactionBuilder;

class WithTransactionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Optional: publish config, routes, etc.
    }

    public function register(): void
    {
        $this->app->singleton('with-transaction', fn() => new TransactionBuilder());

        //
    }
}
