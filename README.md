# Laravel route helpers

Rails like route helpers for your laravel app!

## What is that?

if you have a `CommentsController` this package will generate these helpers:

| URI                        | Route Name(s)                                    | Helper Name             |
|----------------------------|--------------------------------------------------|-------------------------|
| `/comments`                | comments.index                                   | `comments_path()`       |
| `/comments/{comment}`      | comments.show, comments.update, comments.destroy | `comment_path()`        |
| `/comments/create`         | comments.create                                  | `create_comment_path()` |
| `/comments/{comment}/edit` | comments.edit                                    | `edit_comment_path()`   |

These helpers can the be used in your views like so:

```blade
<a href="{{ posts_path() }}">Posts</a>
<a href="{{ new_post_path() }}">Got to the post creation form</a>
<a href="{{ post_path(1) }}">Show post with an id of 1</a>
<a href="{{ edit_post_path(1) }}">Edit post with an id of 1</a>

<form method="POST" action="{{ posts_path() }}">
    <!-- Form to create a new post -->
</form>

<form method="POST" action="{{ post_path(1) }}">
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

The helper file is a simple php file in which all of the helpers function are
defined.

*Helpers name are generated from route names.*

### Restful only

This package will generate helpers only for restful controllers. Custom non
restful action won't get helpers.

A restful controller is a controller whose actions are in this list:

 - index
 - create
 - store
 - show
 - edit
 - update
 - destroy

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

Route::resource('comments', 'CommentsController');
```

| URI                              | Route Name(s)                                          | Helper Name               |
|----------------------------------|--------------------------------------------------------|---------------------------|
| `/posts/comments`                | posts.comments.index                                   | `post_comments_path()`      |
| `/posts/comments/{comment}`      | posts.comments.show, comments.update, comments.destroy | `post_comment_path()`       |
| `/posts/comments/create`         | posts.comments.create                                  | `create_post_comment_path()` |
| `/posts/comments/{comment}/edit` | posts.comments.edit                                    | `edit_post_comment_ath()`   |

#### Nested Controllers

This package also handles nesting so given a PostsCommentsController registered
like so:

```php
Route::resource('posts.comments', 'PostsCommentsController');
```

The helpes will be:



#### Irregular names

This package uses Laravel's provided `Str::singularize()` function in order to
generate the singular helpers. If your controller uses an irregular name it will
the correct singularized form will be used.

If `Str::singularize()` cannot guess the correct singular form of your
controller name you'll have to configure it: 
[https://stackoverflow.com/questions/25646229/laravel-custom-inflection](https://stackoverflow.com/questions/25646229/laravel-custom-inflection)

#### Singular resources

While you should most of the time use plural names for your resources you may
sometimes need to use singular name for an `AccountController` for instance.

In that case you may simply not declare the `index` action in your controller
and routes. Therefore the plural version of the route helper won't need to be
generated.

### The helpers

You can use these function the same way you'd use the `route()` function.

Actually, under the hood, calling the the `edit_comment_path($comment)` helper
will result in calling `route('comments.edit', $comment)`.

### Compile the helpers file manually

When you run your application using the `local` environement the helpers file
will get recompiled whenever you change your routes file.

In any other environment the helpers file if not present will be generated on 
the first request made to your application.

You can also manually launch the generation of the helpers file from your ci/cd
pipeline by calling the `route:compile-helpers` artisan command.

```shell script
php artisan route:compile-helpers
```

### Configuration

You can publish the config file by calling this command:

```shell script
 artisan vendor:publish --provider="PaulhenriL\LaravelRouteHelpers\ServiceProvider"
```

## Contributing

If you have any questions about how to use this library feel free to open an
issue.

If you think that the documentation or the code could be improved in any way
open a PR and I'll happily review it!
