<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_store_tag(): void
    {
        // 認証済みユーザーが新しいタグを登録できること
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/tags', [
            'name' => '新しいタグ',
        ]);

        $this->assertDatabaseHas('tags', ['name' => '新しいタグ']);
        $response->assertStatus(302);
    }

    /** @test */
    public function authenticated_user_can_update_tag(): void
    {
        // 認証済みユーザーが既存のタグを更新できること
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => '元のタグ名']);

        $response = $this->actingAs($user)->put("/admin/tags/{$tag->id}", [
            'name' => '更新後のタグ名',
        ]);

        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => '更新後のタグ名']);
        $response->assertStatus(302);
    }

    /** @test */
    public function authenticated_user_can_delete_tag(): void
    {
        // 認証済みユーザーがタグを削除できること
        $user = User::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->actingAs($user)->delete("/admin/tags/{$tag->id}");

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
        $response->assertStatus(302);
    }
}