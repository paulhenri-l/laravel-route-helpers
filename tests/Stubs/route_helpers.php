<?php

if (!function_exists('post_comments_path')) {
    function post_comments_path($params = [], $absolute = true)
    {
        return route('posts.comments.index', $params, $absolute);
    }
}

if (!function_exists('new_post_comment_path')) {
    function new_post_comment_path($params = [], $absolute = true)
    {
        return route('posts.comments.create', $params, $absolute);
    }
}

if (!function_exists('post_comment_path')) {
    function post_comment_path($params = [], $absolute = true)
    {
        return route('posts.comments.show', $params, $absolute);
    }
}

if (!function_exists('edit_post_comment_path')) {
    function edit_post_comment_path($params = [], $absolute = true)
    {
        return route('posts.comments.edit', $params, $absolute);
    }
}
