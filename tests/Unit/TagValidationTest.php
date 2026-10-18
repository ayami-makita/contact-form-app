<?php

namespace Tests\Unit;

use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TagValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * バリデーションを実行するヘルパーメソッド
     */
    private function validate(array $data)
    {
        $request = new StoreTagRequest();
        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    public function store_tag_validation_passes_with_valid_data(): void
    {
        $validator = $this->validate(['name' => 'テストタグ']);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function store_tag_validation_fails_when_name_is_missing(): void
    {
        $validator = $this->validate(['name' => '']);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
    }

    /** @test */
    public function store_tag_validation_fails_when_name_exceeds_50_chars(): void
    {
        $validator = $this->validate(['name' => str_repeat('あ', 51)]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
    }

    /** @test */
    public function store_tag_validation_fails_when_name_is_duplicate(): void
    {
        Tag::factory()->create(['name' => '重複タグ']);

        $validator = $this->validate(['name' => '重複タグ']);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
    }
}