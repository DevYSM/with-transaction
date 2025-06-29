<?php

namespace YSM\WithTransaction\Support;

use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Execute a callback within a database transaction with optional success and failure hooks.
 *
 * @param Closure      $callback
 * @param int          $attempts
 * @param Closure|null $onSuccess
 * @param Closure|null $onFailure
 *
 * @return mixed
 */
function transaction(
    Closure  $callback,
    int      $attempts = 1,
    ?Closure $onSuccess = null,
    ?Closure $onFailure = null
): mixed
{
    try {
        $result = DB::transaction($callback, $attempts);

        if ($onSuccess) {
            $onSuccess($result);
        }

        return $result;
    } catch (Throwable $e) {
        if ($onFailure) {
            $onFailure($e);
        }

        throw $e; // rethrow to maintain native behavior
    }
}
