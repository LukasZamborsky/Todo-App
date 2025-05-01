<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Tag;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('tag_task')->truncate();
        DB::table('tasks')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $titles = [
            ['title' => 'Upratať izbu', 'description' => 'Vysávať, utrieť prach a usporiadať veci.'],
            ['title' => 'Dokončiť Laravel projekt', 'description' => 'Implementovať posledné funkcie a nasadiť projekt.'],
            ['title' => 'Zabehať si', 'description' => 'Ísť si zabehať aspoň 5 kilometrov.'],
            ['title' => 'Ísť na nákup', 'description' => 'Nakúpiť potraviny a čistiace prostriedky.'],
            ['title' => 'Napísať e-mail šéfovi', 'description' => 'Informovať šéfa o stave projektu.'],
            ['title' => 'Prečítať knihu', 'description' => 'Prečítať minimálne jednu kapitolu.'],
            ['title' => 'Zavolať babke', 'description' => 'Zistiť, ako sa má a čo potrebuje.'],
            ['title' => 'Zapísať si poznámky', 'description' => 'Spísať poznámky z dnešného meetingu.'],
            ['title' => 'Pripraviť večeru', 'description' => 'Navariť niečo jednoduché a chutné.'],
            ['title' => 'Opraviť bicykel', 'description' => 'Vymeniť dušu a dotiahnuť brzdy.'],
            ['title' => 'Zaplatiť faktúry', 'description' => 'Zaplať elektrinu, vodu a internet.'],
            ['title' => 'Naplánovať dovolenku', 'description' => 'Vybrať miesto, termín a rezervovať hotel.'],
            ['title' => 'Vyčistiť počítač', 'description' => 'Vymazať nepotrebné súbory a prečistiť systém.'],
            ['title' => 'Skontrolovať účty', 'description' => 'Overiť zostatok na účte a plán výdavkov.'],
            ['title' => 'Cvičiť jogu', 'description' => 'Krátke 30-minútové strečingové cvičenie.'],
            ['title' => 'Vyplniť formulár', 'description' => 'Dokončiť a odoslať daňový formulár.'],
            ['title' => 'Odovzdať úlohu', 'description' => 'Nahrať zadanie do systému pred termínom.'],
            ['title' => 'Naučiť sa Vue', 'description' => 'Prejsť si oficiálnu dokumentáciu a vytvoriť demo.'],
            ['title' => 'Pridať úlohu do systému', 'description' => 'Vyskúšať, či funguje pridávanie úloh cez UI.'],
            ['title' => 'Zálohovať dáta', 'description' => 'Vytvoriť zálohu všetkých pracovných súborov.'],
            ['title' => 'Vyniesť smeti', 'description' => 'Skontrolovať, ktoré koše sú plné.'],
            ['title' => 'Zaliezť do postele', 'description' => 'Oddýchnuť si po náročnom dni.'],
            ['title' => 'Zavolať kamarátovi', 'description' => 'Dohodnúť si stretnutie na víkend.'],
            ['title' => 'Napísať blog', 'description' => 'Zhrnúť svoje skúsenosti s Laravelom.'],
            ['title' => 'Opraviť chybu v kóde', 'description' => 'Identifikovať a opraviť bug, ktorý bráni deployu.'],
        ];
        

        foreach ($titles as $index => $data) {
            $task = Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'is_completed' => $index < 10,
            ]);
        
            $task->tags()->attach(
                Tag::inRandomOrder()->take(rand(1, 2))->pluck('id')
            );
        }
    }
}
