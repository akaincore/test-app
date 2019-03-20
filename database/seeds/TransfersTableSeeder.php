<?php

use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransfersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::chunk(100, function ($users) {
            /**
             * @var \Illuminate\Database\Eloquent\Collection|User[] $users
             */
            DB::transaction(function () use ($users) {
                $count = $users->count();
                $dateTime = Carbon::now()->addDays(-1)->toDateTimeString();
                for ($i = 0; $i < $count; $i++) {
                    $inverseIndex = $count - $i - 1;
                    if ($i !== $inverseIndex) {
                        Transfer::create([
                            'sender_id' => $users[$i]->id,
                            'recipient_id' => $users[$inverseIndex]->id,
                            'time' => $dateTime,
                            'sum' => rand(0, 100),
                            'completed' => 1,
                        ]);
                    }
                }
            });
        });
    }
}
