<?php

namespace Tests\Unit;

use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreContactRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * バリデーションを実行するヘルパーメソッド
     */
    private function validate(array $data)
    {
        $request = new StoreContactRequest();
        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    public function store_contact_validation_passes_with_valid_data(): void
    {
        $category = Category::factory()->create();

        $validator = $this->validate([
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'category_id' => $category->id,
            'detail' => 'お問い合わせテスト内容です。',
        ]);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function store_contact_validation_fails_when_required_fields_are_missing(): void
    {
        $validator = $this->validate([]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('first_name', $validator->errors()->messages());
        $this->assertArrayHasKey('last_name', $validator->errors()->messages());
        $this->assertArrayHasKey('gender', $validator->errors()->messages());
        $this->assertArrayHasKey('email', $validator->errors()->messages());
        $this->assertArrayHasKey('tel', $validator->errors()->messages());
        $this->assertArrayHasKey('address', $validator->errors()->messages());
        $this->assertArrayHasKey('category_id', $validator->errors()->messages());
        $this->assertArrayHasKey('detail', $validator->errors()->messages());
    }

    /** @test */
    public function store_contact_validation_fails_when_tel_format_is_invalid(): void
    {
        $category = Category::factory()->create();

        $validator = $this->validate([
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '090-1234-5678', // ハイフンありが許可されていない正規表現のため
            'address' => '東京都渋谷区',
            'category_id' => $category->id,
            'detail' => 'お問い合わせテスト内容',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('tel', $validator->errors()->messages());
    }

    /** @test */
    public function store_contact_validation_fails_when_detail_exceeds_120_chars(): void
    {
        $category = Category::factory()->create();

        $validator = $this->validate([
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都渋谷区',
            'category_id' => $category->id,
            'detail' => str_repeat('あ', 121), // 120文字超
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('detail', $validator->errors()->messages());
    }
}