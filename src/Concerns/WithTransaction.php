<?php

namespace YSM\Concerns\WithTransaction;

use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

trait WithTransaction
{
    /**
     * Whether to wrap operations in a database transaction by default.
     *
     * @var bool
     */
    protected bool $wrapInTransaction = true;

    /**
     * Create a new model instance within a transaction if enabled.
     *
     * @param array $attributes
     *
     * @return static
     * @throws Throwable
     */
    public static function create(array $attributes = []): static
    {
        if (!static::shouldWrapStatic()) {
            return tap(new static(), function ($model) use ($attributes) {
                $model->fill($attributes)->save();
            });
        }

        return DB::transaction(function () use ($attributes) {
            return tap(new static(), function ($model) use ($attributes) {
                $model->fill($attributes)->save();
            });
        });
    }

    /**
     * Determine if static operations should be wrapped in a transaction.
     *
     * This creates a new instance of the model and checks its `wrapInTransaction` flag.
     *
     * @return bool
     */
    protected static function shouldWrapStatic(): bool
    {
        return (new static())->shouldWrap();
    }

    /**
     * Determine if instance operations should be wrapped in a transaction.
     *
     * @return bool
     */
    protected function shouldWrap(): bool
    {
        return $this->wrapInTransaction;
    }

    /**
     * Save the model within a transaction if enabled.
     *
     * @param array $options
     *
     * @return bool
     * @throws Throwable
     */
    public function save(array $options = []): bool
    {
        if (!$this->shouldWrap()) {
            return parent::save($options);
        }

        return DB::transaction(fn () => parent::save($options));
    }

    /**
     * Execute a callback within a transaction if enabled.
     *
     * @param Closure(): mixed $callback
     *
     * @return mixed
     * @throws Throwable
     */
    public static function withTransaction(Closure $callback): mixed
    {
        if (!static::shouldWrapStatic()) {
            return $callback();
        }

        return DB::transaction($callback);
    }

    /**
     * Create a new model instance and pass it to the callback,
     * running the callback inside a transaction if enabled.
     *
     * @param Closure(static): mixed $callback
     *
     * @return mixed
     * @throws Throwable
     */
    public static function transactional(Closure $callback): mixed
    {
        if (!static::shouldWrapStatic()) {
            return $callback(new static());
        }

        return DB::transaction(fn () => $callback(new static()));
    }

    /**
     * Update the model within a transaction if enabled.
     *
     * @param array $attributes
     * @param array $options
     *
     * @return bool
     * @throws Throwable
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        if (!$this->shouldWrap()) {
            return tap($this)->fill($attributes)->save($options);
        }

        return DB::transaction(fn () => tap($this)->fill($attributes)->save($options));
    }

    /**
     * Delete the model within a transaction if enabled.
     *
     * @return bool|null
     * @throws Throwable
     */
    public function delete(): ?bool
    {
        if (!$this->shouldWrap()) {
            return parent::delete();
        }

        return DB::transaction(fn () => parent::delete());
    }

    /**
     * Force delete the model within a transaction if enabled.
     *
     * @return bool|null
     * @throws Throwable
     */
    public function forceDelete(): ?bool
    {
        if (!$this->shouldWrap()) {
            return parent::forceDelete();
        }

        return DB::transaction(fn () => parent::forceDelete());
    }

    /**
     * Restore the model within a transaction if enabled.
     *
     * @return bool|null
     * @throws Throwable
     */
    public function restore(): ?bool
    {
        if (!$this->shouldWrap()) {
            return parent::restore();
        }

        return DB::transaction(fn () => parent::restore());
    }

    /**
     * Temporarily disable transaction wrapping for the given callback.
     *
     * @param Closure(self): mixed $callback
     *
     * @return mixed
     */
    public function withoutTransaction(Closure $callback): mixed
    {
        $original = $this->wrapInTransaction;
        $this->wrapInTransaction = false;

        try {
            return $callback($this);
        } finally {
            $this->wrapInTransaction = $original;
        }
    }

    /**
     * Force transaction wrapping for the given callback.
     *
     * @param Closure(self): mixed $callback
     *
     * @return mixed
     * @throws Throwable
     */
    public function withForcedTransaction(Closure $callback): mixed
    {
        $original = $this->wrapInTransaction;
        $this->wrapInTransaction = true;

        try {
            return $this->shouldWrap()
                ? DB::transaction(fn () => $callback($this))
                : $callback($this);
        } finally {
            $this->wrapInTransaction = $original;
        }
    }
}
