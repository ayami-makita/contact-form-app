<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_category_has_many_contacts(): void
    {
        $category = Category::factory()->create();
        $contact = Contact::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($category->contacts->contains($contact));
    }

    /** @test */
    public function a_contact_belongs_to_a_category(): void
    {
        $category = Category::factory()->create();
        $contact = Contact::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $contact->category);
        $this->assertEquals($category->id, $contact->category->id);
    }

    /** @test */
    public function a_contact_can_sync_tags(): void
    {
        $contact = Contact::factory()->create();
        $tags = Tag::factory()->count(3)->create();

        // タグを同期
        $contact->tags()->sync($tags->pluck('id'));

        $this->assertCount(3, $contact->tags);
    }
}