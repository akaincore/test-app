<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            DB::transaction(function () {
                factory(User::class, 100)->create([
                    'password' => Hash::make('qweqweqwe'),
                    'balance' => rand(0, 10000),
                ]);
            });
        }
    }
}
