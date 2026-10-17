<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;
use Faker\Factory as Faker;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');
        $categoryIds = Category::pluck('id')->toArray();
        $tagIds = Tag::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            $contact = Contact::create([
                'first_name'  => $faker->firstName,
                'last_name'   => $faker->lastName,
                'gender'      => $faker->randomElement([1, 2, 3]),
                'email'       => $faker->safeEmail,
                'tel'         => $faker->numerify('090########'),
                'address'     => $faker->address,
                'building'    => $faker->secondaryAddress,
                'detail'      => $faker->realText(100),
                'category_id' => $faker->randomElement($categoryIds),
            ]);

            // 既存のタグからランダムに1〜3件を中間テーブルに紐付け
            if (!empty($tagIds)) {
                $randomTagIds = $faker->randomElements($tagIds, $faker->numberBetween(1, min(3, count($tagIds))));
                $contact->tags()->attach($randomTagIds);
            }
        }
    }
}