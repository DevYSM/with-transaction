<?php

namespace YSM\Support;

use Closure;
use Illuminate\Support\Facades\DB;

/**
 * Execute a callback within a database transaction.
 *
 * @param Closure $callback
 * @return mixed
 */
function transactional(Closure $callback): mixed
{
    return DB::transaction($callback);
}


