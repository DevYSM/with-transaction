# Laravel WithTransaction Trait

This package provides a reusable Laravel trait that automatically wraps Eloquent model
operations (`create`, `update`, `save`, `delete`, etc.) in database transactions — giving you cleaner, safer code
without writing `DB::transaction()` everywhere.

## ✨ Features

- Automatically wraps model actions in a transaction
- Easily toggle transactional behavior per operation
- Works with all Eloquent models
- Supports create, update, save, delete, restore, forceDelete
- Static helpers: `withTransaction`, `transactional`

---

## 📦 Installation

```bash
composer require your-vendor/with-transaction

php artisan vendor:publish --tag=with-transaction-config
```

## 📖 Usage

### 1. Add the Trait

In your Eloquent model, use the `WithTransaction` trait:

```php
use App\Traits\WithTransaction;

class Post extends Model
{
    use WithTransaction;

    protected $fillable = ['title', 'content'];
}
```
