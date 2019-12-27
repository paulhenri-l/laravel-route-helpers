<?php

if (!function_exists('postCommentsPath')) {
    function postCommentsPath($params = [], $absolute = true)
    {
        return route('posts.comments.index', $params, $absolute);
    }
}

if (!function_exists('newPostCommentPath')) {
    function newPostCommentPath($params = [], $absolute = true)
    {
        return route('posts.comments.create', $params, $absolute);
    }
}

if (!function_exists('postCommentPath')) {
    function postCommentPath($params = [], $absolute = true)
    {
        return route('posts.comments.show', $params, $absolute);
    }
}

if (!function_exists('editPostCommentPath')) {
    function editPostCommentPath($params = [], $absolute = true)
    {
        return route('posts.comments.edit', $params, $absolute);
    }
}
