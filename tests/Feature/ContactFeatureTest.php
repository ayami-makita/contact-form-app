<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function contact_creation_screen_can_be_rendered(): void
    {
        // ルートURL（/）でお問い合わせ入力画面が表示されること
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_submit_contact_form(): void
    {
        $category = Category::factory()->create();

        $formData = [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'category_id' => $category->id,
            'detail' => '機能テストのお問い合わせ内容です。',
        ];

        // /contacts へPOSTリクエストを実行
        $response = $this->post('/contacts', $formData);

        // データベースにデータが保存されていることを確認
        $this->assertDatabaseHas('contacts', [
            'email' => 'test@example.com',
            'first_name' => '山田',
            'last_name' => '太郎',
        ]);

        // 保存後にリダイレクトされることを確認
        $response->assertStatus(302);
    }
}