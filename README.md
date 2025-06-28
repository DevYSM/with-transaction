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
composer require ysm/with-transaction

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

### 2. Use in the controller

Now you can use your model as usual, and all operations will be automatically wrapped in a transaction:

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
       $post = Post::transactional(function () use ($request) {
       
            $model = Post::create($request->validated());
            
            $model->tags()->attach(Tag::inRandomOrder()->take(3)->pluck('id'));
            $model->categories()->attach(Category::inRandomOrder()->take(3)->pluck('id'));
            
            $model->addMediaFromRequest('image')
                ->toMediaCollection('posts');
            
            // all or nothing rollback automatically
            return $model;
        });

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ]);
    }
}
```

### 3. Updating the Model

You can also use the same trait for updating models:

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function Update(Request $request, Post $post)
    {
        $post = Post::transactional(function () use ($post) {
        
            $post->update([
                'title' => 'Update title Post v1',
            ]);

            $post->tags()->sync([4, 5, 6]);

            return $post;
       });

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post,
        ], 200);
    }
}
```

### 4. Deleting the Model

You can also delete models using the same trait:

```php
namespace App\Http\Controllers\Api; 
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
class PostController extends Controller
{
    public function destroy(Post $post)
    {
        $post = Post::transactional(function () use ($post) {
            $post->delete();
            // If you want to perform any additional operations after deletion,
            // you can do so here. For example, logging or cleaning up related data.
            // If the deletion fails, the transaction will automatically roll back.
            return $post;
        });

        return response()->json([
            'message' => 'Post deleted successfully',
            'post' => $post,
        ], 200);
    }
}
```

### Thanks for using this package! ❤️

If you have any questions or suggestions, feel free to open an issue on GitHub.
 
