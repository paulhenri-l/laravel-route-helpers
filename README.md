# Laravel route helpers

One of the things I've always liked with rails is how easily you can generate
paths to your controllers.

As I've always missed this feature I found a way to add it to Laravel.

For instance, if you have a `PostsController` this package will give you the 
`postsPath()`, `postPath()`, `editPostPath()` and `newPostPath()` helpers. 

These helpers can the be used in your views like so:

```blade
<a href="{{ postsPath() }}">Posts</a>
<a href="{{ newPostPath() }}">Got to the post creation form</a>
<a href="{{ postPath($post) }}">Show post with an id of 1</a>
<a href="{{ editPostPath([$post, 'query' => 'parameter']) }}">Edit post with an id of 1</a>

<form method="POST" action="{{ postsPath() }}">
    <!-- Form to create a new post -->
</form>

<form method="POST" action="{{ postPath(1) }}">
    <!-- Form to edit the post with an id of 1 -->
</form>
```

## Installation

You can install this package using composer

```shell script
composer require paulhenri-l/laravel-route-helpers
```

Laravel package discovery is used so you basically have nothing more to do.

## Usage

This package will automatically scan your router for restful controllers and
generate a route helpers file. Everytime you add a new route the helpers file
will get recompiled.

The helper file is a simple php file where all of the helpers function are.

*A note about performance. Indeed generating this file is not the most efficient
thing to do. That's why you need to call the `route:compile-helpers` artisan
command before deploying to production. That way the helper file will be
generated once without adding any overhead to your application*

### Resources controllers

 - This package will generate helper only for restful controllers. Custom non
restful action won't get helpers.
 - Helpers are generated from route names.
 - Controllers should be plural

That is if you want to generate helpers for a controller named
`CommentsController` your routes should be defined either as this:

```php
<?php

Route::get('/comments', 'CommentsController@index')->name('comments.index');
Route::get('/comments/create', 'CommentsController@create')->name('comments.create');
Route::post('/comments', 'CommentsController@store')->name('comments.store');
Route::get('/comments/{comment}', 'CommentsController@show')->name('comments.show');
Route::get('/comments/{comment}/edit', 'CommentsController@edit')->name('comments.edit');
Route::patch('/comments/{comment}', 'CommentsController@update')->name('comments.update');
Route::delete('/comments/{comment}', 'CommentsController@destroy')->name('comments.destroy');
```

or

```php
<?php

Route::resource('/comments', 'CommentsController');
```

This will give us these helpers:

| URI                        | Route Name(s)                                  | Helper Name           |
|----------------------------|------------------------------------------------|-----------------------|
| `/comments`                | comments.index                                 | `commentsPath()`      |
| `/comments/{comment}`      | comments.show comments.update comments.destroy | `commentPath()`       |
| `/comments/create`         | comments.create                                | `createCommentPath()` |
| `/comments/{comment}/edit` | comments.edit                                  | `editCommentPath()`   |

#### Irregular names

This package uses Laravel's provided `Str::singularize()` function in order to
generate the singular helpers. If your controller uses an irregular name it will
the correct singularized form will be used.

If `Str::singularize()` cannot guess the correct singular form of your
controller name you'll have to configure it: 
[https://stackoverflow.com/questions/25646229/laravel-custom-inflection](https://stackoverflow.com/questions/25646229/laravel-custom-inflection)

#### Singular resources

While you should most of the time use plural names for your resources you may
sometimes need o use singular name for an `AccountController` for instance.

In that case you may simply not declare the `index` action in your controller
and routes. Therefore the plural version of the roue helper won't need to be
generated.

### The helpers

The helpers are simple php functions, they'll all be put in the
`bootstrap/cache/route_helpers_functions.php` file.

You can use these function the same way you'd use the `route()` function.

Actually, under the hood, calling the the `editCommentPath($comment)` will
result in calling `route('comments.edit', $comment)`.

### Query parameters

If you want to add query parameters you can do it just as you'd do it with the
`route()` helper.

```php
commentsPath(['page' => 10]);
```

This is equals to:

```php
route('comments.index', ['page' => 10]);
```

### Compile the helpers file

When you run your application using the `local` environement the helpers file
will get recompiled whenever you change your routes file.

In any other environment the helpers file if not present will be generated on 
the first request made to your application.

You can also manually launch the generation of the helpers file from your ci/cd
pipeline by calling the `route:compile-helpers` artisan command.

```shell script
php artisan route:compile-helpers
```

## Contributing

If you have any questions about how to use this library feel free to open an
issue.

If you think that the documentation or the code could be improved in any way
open a PR and I'll happily review it!
