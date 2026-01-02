<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str; // Import Str

class UserSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::where('is_admin', false)->delete();
        Schema::enableForeignKeyConstraints();

        $users = [
            ['name' => 'Alice Smith', 'exptotal' => 4000], // Max cap
            ['name' => 'Bob Johnson', 'exptotal' => 3800],
            ['name' => 'Charlie Lee', 'exptotal' => 3500],
            ['name' => 'David Kim', 'exptotal' => 3200],
            ['name' => 'Eva Chen', 'exptotal' => 2900],
            ['name' => 'Frank Wu', 'exptotal' => 2600],
            ['name' => 'Grace Hall', 'exptotal' => 2300],
            ['name' => 'Henry Davis', 'exptotal' => 2000],
            ['name' => 'Ivy Martin', 'exptotal' => 1700],
            ['name' => 'Jack Brown', 'exptotal' => 1400],
            ['name' => 'Karen White', 'exptotal' => 1100], // New users below
            ['name' => 'Liam Green', 'exptotal' => 800],
            ['name' => 'Mia Black', 'exptotal' => 500],
            ['name' => 'Noah Blue', 'exptotal' => 300],
            ['name' => 'Olivia Red', 'exptotal' => 100],
            ['name' => 'Paul Yellow', 'exptotal' => 50],
            ['name' => 'Quinn Purple', 'exptotal' => 50],
            ['name' => 'Ryan Orange', 'exptotal' => 50],
            ['name' => 'Sara Pink', 'exptotal' => 50],
            ['name' => 'Tom Gray', 'exptotal' => 50], // Minimal for testing
        ];

        $i = 1;
        foreach ($users as $userData) {
            // Generate a random 6-character alphanumeric token
            $token = Str::upper(Str::random(6));

            User::create([
                'name' => $userData['name'],
                'email' => 'user' . $i . '@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_admin' => false,
                'exptotal' => $userData['exptotal'],
                'reset_token' => $token, // <-- Updated to 6 chars
            ]);
            $i++;
        }
    }
}