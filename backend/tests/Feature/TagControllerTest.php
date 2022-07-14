<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $basePath = '/api/tags';

    /** @test */
    public function index_タグ一覧を取得できること()
    {
        Tag::factory(5)->create(['name' => 'laravel']);

        $this->getJson($this->basePath)->assertOk();
    }
}
