<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfilesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = [
            [
                'id' => 1,
                'name' => 'Admnistrador',
                'description' => 'Perfil com responsabilidade de girar toda a aplicação e todos os seus derivados.'
            ],
            [
                'id' => 2,
                'name' => 'Médico',
                'description' => 'Perfil com capacidade apenas de ver e editar consultas que lhe estão atribuídas.'
            ],
            [
                'id' => 3,
                'name' => 'Utente',
                'description' => 'Perfil com capacidade apenas de realizar marcações de consultas.'
            ],
            [
                'id' => 4,
                'name' => 'Recepcionista',
                'description' => 'Perfil com capacidade de gerir e atribuir todas as marcações disponíveis na clínica.'
            ],
        ];
        foreach ($profiles as $profile) {
            Profile::updateOrCreate([
                'id' => $profile['id'],
            ], ['name' => $profile['name'], 'description' => $profile['description']]);
        }
    }
}
