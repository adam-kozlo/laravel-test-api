<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $firstUser = User::factory()->create([
            'name' => 'test_user1',
            'email' => 'test_user1@example.com',
            'password' => Hash::make('lorem25#'),
        ]);

        $secondUser = User::factory()->create([
            'name' => 'test_user2',
            'email' => 'test_user2@example.com',
            'password' => Hash::make('lorem26#'),
        ]);

        $patients = [
            [
                'user_id' => $firstUser->id,
                'name' => 'Jan',
                'surname' => 'Kowalski',
                'email' => 'test1@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $firstUser->id,
                'name' => 'Adam',
                'surname' => 'Nowak',
                'email' => 'test2@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $secondUser->id,
                'name' => 'Michał',
                'surname' => 'Wojewódzki',
                'email' => 'test3@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $secondUser->id,
                'name' => 'Paweł',
                'surname' => 'Malinowski',
                'email' => 'test4@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('patients')->insert($patients);

    }
}
