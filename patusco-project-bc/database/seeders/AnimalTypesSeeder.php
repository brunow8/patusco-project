<?php

namespace Database\Seeders;

use App\Models\AnimalType;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class AnimalTypesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $animalTypes = [
            ['id' => 1, 'name' => 'Cão'],
            ['id' => 2, 'name' => 'Gato'],
            ['id' => 3, 'name' => 'Pássaro'],
            ['id' => 4, 'name' => 'Coelho'],
            ['id' => 5, 'name' => 'Hamster'],
            ['id' => 6, 'name' => 'Porquinho-da-Índia'],
            ['id' => 7, 'name' => 'Tartaruga'],
            ['id' => 8, 'name' => 'Peixe'],
            ['id' => 9, 'name' => 'Cavalo'],
            ['id' => 10, 'name' => 'Furão'],
            ['id' => 11, 'name' => 'Rato'],
            ['id' => 12, 'name' => 'Iguana'],
            ['id' => 13, 'name' => 'Cobra'],
            ['id' => 14, 'name' => 'Lagarto'],
            ['id' => 15, 'name' => 'Papagaio'],
            ['id' => 16, 'name' => 'Ouriço'],
            ['id' => 17, 'name' => 'Chinchila'],
            ['id' => 18, 'name' => 'Cabra'],
            ['id' => 19, 'name' => 'Ovelha'],
            ['id' => 20, 'name' => 'Porco'],
            ['id' => 21, 'name' => 'Vaca'],
            ['id' => 22, 'name' => 'Pônei'],
            ['id' => 23, 'name' => 'Camaleão'],
            ['id' => 24, 'name' => 'Gecko'],
            ['id' => 25, 'name' => 'Pato'],
        ];

        foreach ($animalTypes as $profile) {
            AnimalType::updateOrCreate([
                'id' => $profile['id'],
            ], ['name' => $profile['name']]);
        }
    }
}
