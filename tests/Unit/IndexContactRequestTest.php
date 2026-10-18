<?php

namespace Tests\Unit;

use App\Http\Requests\IndexContactRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IndexContactRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * バリデーションを実行するヘルパーメソッド
     */
    private function validate(array $data)
    {
        $request = new IndexContactRequest();
        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    // 空のデータ（パラメータなし）でもバリデーションが通ること
    public function search_validation_passes_with_empty_data(): void
    {
        $validator = $this->validate([]);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    // 有効な検索パラメータ（キーワード、性別、カテゴリID、日付）でバリデーションが通ること
    public function search_validation_passes_with_valid_parameters(): void
    {
        $category = Category::factory()->create();

        $validator = $this->validate([
            'keyword' => 'テスト',
            'gender' => '1',
            'category_id' => $category->id,
            'date' => '2026-01-01',
        ]);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    // 性別に無効な値が指定された場合にバリデーションエラーになること
    public function search_validation_fails_when_gender_is_invalid(): void
    {
        $validator = $this->validate([
            'gender' => '9',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('gender', $validator->errors()->messages());
    }

    /** @test */
    // 存在しないカテゴリIDが指定された場合にバリデーションエラーになること
    public function search_validation_fails_when_category_id_does_not_exist(): void
    {
        $validator = $this->validate([
            'category_id' => 999,
        ]);

        $validator = $this->validate([
            'category_id' => 999,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('category_id', $validator->errors()->messages());
    }

    /** @test */
    // 日付フォーマットが不正な場合にバリデーションエラーになること
    public function search_validation_fails_when_date_is_invalid(): void
    {
        $validator = $this->validate([
            'date' => 'invalid-date',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('date', $validator->errors()->messages());
    }
}