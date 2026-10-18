<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateTagRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * バリデーションを実行するヘルパーメソッド
     */
    private function validate(array $data, $tag = null)
    {
        $request = new UpdateTagRequest();
        
        // リクエストにデータをセット
        $request->replace($data);

        // ルートパラメータ（tag ID または tagオブジェクト）をバインドする
        if ($tag) {
            $tagId = $tag instanceof Tag ? $tag->id : $tag;
            $request->setRouteResolver(function () use ($tagId) {
                $route = new \Illuminate\Routing\Route('PUT', 'tags/{tag}', []);
                $route->parameters['tag'] = $tagId;
                return $route;
            });
        }

        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    public function update_tag_validation_passes_with_valid_data(): void
    {
        $tag = Tag::factory()->create(['name' => '元のタグ名']);

        $validator = $this->validate([
            'name' => '新しいタグ名',
        ], $tag);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function update_tag_validation_passes_with_same_name(): void
    {
        // 自分の名前を変更せずそのまま送信する場合は重複エラーにならないこと
        $tag = Tag::factory()->create(['name' => '現在のタグ名']);

        $validator = $this->validate([
            'name' => '現在のタグ名',
        ], $tag);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function update_tag_validation_fails_when_name_is_missing(): void
    {
        $tag = Tag::factory()->create();

        $validator = $this->validate([
            'name' => '',
        ], $tag);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
    }

    /** @test */
    public function update_tag_validation_fails_when_name_exceeds_50_chars(): void
    {
        $tag = Tag::factory()->create();

        $validator = $this->validate([
            'name' => str_repeat('あ', 51),
        ], $tag);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
    }

    /** @test */
    public function update_tag_validation_fails_when_name_is_duplicate_with_other_tag(): void
    {
        Tag::factory()->create(['name' => '既存のタグ名']);
        $tag = Tag::factory()->create(['name' => '更新対象のタグ']);

        $validator = $this->validate([
            'name' => '既存のタグ名',
        ], $tag);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
    }
}