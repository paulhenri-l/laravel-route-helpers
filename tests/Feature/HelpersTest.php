<?php

namespace PaulhenriL\LaravelRouteHelpers\Tests\Feature;

use PaulhenriL\LaravelRouteHelpers\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_compilation()
    {
        $this->assertEquals('/posts/1/comments', post_comments_path(1, false));
        $this->assertEquals('/posts/1/comments/create', new_post_comment_path(1, false));
        $this->assertEquals('/posts/1/comments/1', post_comment_path([1, 1], false));
        $this->assertEquals('/posts/1/comments/1/edit', edit_post_comment_path([1, 1], false));
    }
}
