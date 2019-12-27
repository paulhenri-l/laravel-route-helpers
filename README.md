# Laravel route helpers

Route helpers are simple function that will generate paths to your application
resources.

For instance if you have a `PostsController` you'll get these helpers:

- `posts_path()`
- `post_path()`
- `new_post_path()`
- `edit_post_path()`

Which can in turn be used in your views/app like so:

```blade
<a href="{{ posts_path() }}">Posts</a>
<a href="{{ new_post_path() }}">Got to the post creation form</a>
<a href="{{ post_path($post) }}">Show the given post</a>
<a href="{{ edit_post_path($post) }}">Edit the given post</a>

<form method="POST" action="{{ posts_path() }}">
    <!-- Form to create a new post -->
</form>

<form method="POST" action="{{ post_path($post) }}">
    <!-- Form to edit the given post -->
</form>
```

```php
redirect()->to(posts_path()); // Redirect to posts.index
```

The end goal is to leverage autocompletion in order to rapidly create path to 
our resources while not having to remember the exact route name.

## Installation

You can install this package using composer

```shell script
composer require paulhenri-l/laravel-route-helpers
```

## Usage

Register your routes just as usual but give them names. On the next application
boot the helpers will be generated and loaded.

Your route names should be one of the 7 restful names used by laravel (\*.index,
\*.create, \*.store, \*.show, \*.edit, \*.update and \*.destroy)

```php
Route::resource('comments', 'CommentsController');
```

This will generate these helpers:

```php
comments_path();
comment_path();
create_comment_path();
edit_comment_path();
```

### Nested resources

If you are nesting your resources the generated helpers will keep the nesting.

```php
Route::resource('posts.comments', 'PostsCommentsController');
```

```php
post_comments_path();
post_comment_path();
create_post_comment_path();
edit_post_comment_path();
```

### Irregular names

If you are using irregular names for your resources, the correct singular form
will be used for the helpers.

```php
Route::resource('people', 'PeopleController');
```

```php
people_path();
person_path();
create_person_path();
edit_person_path();
```

If this tool cannot guess the correct singular form for your route name you'll 
have to configure it:
[https://stackoverflow.com/questions/25646229/laravel-custom-inflection](https://stackoverflow.com/questions/25646229/laravel-custom-inflection)

### Singular resources

If you have singular resources you cannot use the index function as it would
create conflicts between the plural and singular helpers.

```php
Route::resource('account', 'AccountController')->except('index');
```

### Helpers works just like the route function

```php
<?php

posts_path();
posts_path(['query' => 'param']);
posts_path(['query' => 'param'], true);
post_path($post);
post_comment_path([$post, $comment]);
```

### Compile the helpers file manually

When you run your application using the `local` environement the helpers file
will get recompiled whenever you change your routes file.

In any other environment the helpers file if not present will be generated on
the first boot of your application.

You can also manually launch the generation of the helpers file by calling this
artisan command.

```shell script
php artisan route:compile-helpers
```

## Contributing

If you have any questions about how to use this library feel free to open an
issue.

If you think that the documentation or the code could be improved in any way
open a PR and I'll happily review it!
