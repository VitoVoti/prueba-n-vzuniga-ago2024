<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Role;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TestArticlesAndTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
        
            $tag_1 = Tag::create([
                "name" => "Python",
            ]);

            $tag_2 = Tag::create([
                "name" => "PHP",
            ]);

            $tag_3 = Tag::create([
                "name" => "C++",
            ]);

            $a = new Article([
                'title' => 'Test Article 1',
                'body' => 'Test Content 1 Content lorem ipsum dolor sit amet',
            ]);
            $u = User::all()->last();
            $u->articles()->save($a);
            $u->save();

            $a->tags()->attach($tag_1);
            $a->tags()->attach($tag_3);

            $a->save();

        });

        
    }
}
