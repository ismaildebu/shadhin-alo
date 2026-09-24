<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArticleResponseTest extends TestCase
{
    public function test_dump_articles_response(): void
    {
        $response = $this->getJson('/articles');

        dump($response->status(), $response->json());

        $this->assertTrue(true);
    }
}
