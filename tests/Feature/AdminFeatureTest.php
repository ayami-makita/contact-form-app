<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        // 未認証ユーザーが管理画面にアクセスした際、ログイン画面等へリダイレクトされること
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_admin_dashboard(): void
    {
        // 認証済みユーザーを作成してログイン状態でアクセス
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_delete_contact(): void
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create();

        // 削除リクエストを実行
        $response = $this->actingAs($user)->delete("/admin/contacts/{$contact->id}");

        // データベースから削除されていること（ソフトデリートまたは物理削除）
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);

        // リダイレクトされること
        $response->assertStatus(302);
    }
}