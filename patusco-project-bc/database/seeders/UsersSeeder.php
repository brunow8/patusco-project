<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Admin
        DB::table('users')->insert([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => strtolower('john.doe@gmail.com'),
            'birthday' => Carbon::parse('1980-01-01')->toDateString(),
            'profile_id' => 1,
            'password' => Hash::make('teste'),
            'cellphone' => '1234567890',
        ]);

        // Receptionists
        DB::table('users')->insert([
            [
                'first_name' => 'Emily',
                'last_name' => 'Smith',
                'email' => strtolower('emily.smith@gmail.com'),
                'birthday' => Carbon::parse('1990-05-12')->toDateString(),
                'profile_id' => 4,
                'password' => Hash::make('teste'),
                'cellphone' => '1234567891',
            ],
            [
                'first_name' => 'Laura',
                'last_name' => 'Johnson',
                'email' => strtolower('laura.johnson@gmail.com'),
                'birthday' => Carbon::parse('1988-03-22')->toDateString(),
                'profile_id' => 4,
                'password' => Hash::make('teste'),
                'cellphone' => '1234567892',
            ],
        ]);

        // Medics
        DB::table('users')->insert([
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => strtolower('michael.brown@gmail.com'),
                'birthday' => Carbon::parse('1985-07-18')->toDateString(),
                'profile_id' => 2,
                'password' => Hash::make('teste'),
                'cellphone' => '1234567893',
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Davis',
                'email' => strtolower('sophia.davis@gmail.com'),
                'birthday' => Carbon::parse('1991-09-11')->toDateString(),
                'profile_id' => 2,
                'password' => Hash::make('teste'),
                'cellphone' => '1234567894',
            ],
            [
                'first_name' => 'Daniel',
                'last_name' => 'Garcia',
                'email' => strtolower('daniel.garcia@gmail.com'),
                'birthday' => Carbon::parse('1982-02-05')->toDateString(),
                'profile_id' => 2,
                'password' => Hash::make('teste'),
                'cellphone' => '1234567895',
            ],
        ]);

        // Clients
        $clients = [
            ['first_name' => 'Oliver', 'last_name' => 'Martinez', 'birthday' => '1995-06-21'],
            ['first_name' => 'Emma', 'last_name' => 'Rodriguez', 'birthday' => '1993-04-09'],
            ['first_name' => 'Liam', 'last_name' => 'Lee', 'birthday' => '1992-12-14'],
            ['first_name' => 'Ava', 'last_name' => 'Hernandez', 'birthday' => '1998-10-23'],
            ['first_name' => 'Ethan', 'last_name' => 'Clark', 'birthday' => '1990-11-19'],
            ['first_name' => 'Mia', 'last_name' => 'Lewis', 'birthday' => '1996-08-27'],
            ['first_name' => 'Lucas', 'last_name' => 'Walker', 'birthday' => '1994-03-03'],
            ['first_name' => 'Amelia', 'last_name' => 'Hall', 'birthday' => '1991-09-30'],
            ['first_name' => 'Noah', 'last_name' => 'Allen', 'birthday' => '1997-02-25'],
            ['first_name' => 'Isabella', 'last_name' => 'Young', 'birthday' => '1999-01-15'],
            ['first_name' => 'Mason', 'last_name' => 'King', 'birthday' => '1990-07-05'],
            ['first_name' => 'Sophia', 'last_name' => 'Scott', 'birthday' => '1994-05-11'],
            ['first_name' => 'Jacob', 'last_name' => 'Green', 'birthday' => '1995-12-02'],
            ['first_name' => 'Charlotte', 'last_name' => 'Adams', 'birthday' => '1992-06-09'],
            ['first_name' => 'Benjamin', 'last_name' => 'Baker', 'birthday' => '1993-11-29'],
            ['first_name' => 'Harper', 'last_name' => 'Gonzalez', 'birthday' => '1998-04-20'],
            ['first_name' => 'Henry', 'last_name' => 'Nelson', 'birthday' => '1997-01-08'],
            ['first_name' => 'Evelyn', 'last_name' => 'Carter', 'birthday' => '1996-09-17'],
            ['first_name' => 'Alexander', 'last_name' => 'Mitchell', 'birthday' => '1991-02-04'],
            ['first_name' => 'Abigail', 'last_name' => 'Perez', 'birthday' => '1999-08-01'],
        ];

        foreach ($clients as $client) {
            DB::table('users')->insert([
                'first_name' => $client['first_name'],
                'last_name' => $client['last_name'],
                'email' => strtolower($client['first_name'] . '.' . $client['last_name'] . '@gmail.com'),
                'birthday' => Carbon::parse($client['birthday'])->toDateString(),
                'profile_id' => 3,
                'password' => Hash::make('teste'),
                'cellphone' => '123456789' . rand(6, 9), // Generates a random last digit
            ]);
        }
    }
}
