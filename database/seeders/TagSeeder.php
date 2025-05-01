<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        
        DB::table('tags')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tags = ['Práca', 'Domáce úlohy', 'Nákup', 'Šport', 'Zábava', 'Cestovanie', 'Škola', 'Zdravie', 'Financie', 'Iné'];

        foreach ($tags as $name) {
            Tag::create(['name' => $name]);
        }
    }

}
