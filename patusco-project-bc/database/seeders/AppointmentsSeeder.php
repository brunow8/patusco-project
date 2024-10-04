<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentsSeeder extends Seeder
{
    public function run()
    {
        $symptoms = [
            'Coughing',
            'Sneezing',
            'Vomiting',
            'Diarrhea',
            'Lethargy',
            'Loss of appetite',
            'Fever',
            'Scratching',
            'Limping',
            'Bloating'
        ];

        for ($i = 0; $i < 100; $i++) {
            $start = Carbon::now()->addDays(rand(1, 30))->setTime(rand(8, 17), rand(0, 59));
            $end = (clone $start)->addHour();

            Appointment::create([
                'user_id' => rand(7, 27),
                'animal_id' => rand(1, 51),
                'medic_id' => [4, 5, 6][array_rand([4, 5, 6])],
                'symptoms' => $symptoms[array_rand($symptoms)],
                'start_appointment_date' => $start,
                'end_appointment_date' => $end,
                'state' => 2
            ]);
        }
    }
}
