<?php

namespace Tests\Feature;

use App\Facades\TransferRepository;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransferRepositoryTest extends TestCase
{

    use RefreshDatabase;


    public function testStore()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();
        $attributes = [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'time' => Carbon::now()->addDay(),
            'sum' => 100,
        ];
        TransferRepository::store($attributes);
        $this->assertDatabaseHas('transfers', $attributes);
    }

    public function testUnprocessedSum()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();
        $transfers = factory(Transfer::class, 5)->states('unprocessed')->create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
        $sum = $transfers->sum('sum');
        $fromDb = TransferRepository::unprocessedSum($sender);
        $this->assertEquals($sum, $fromDb);
    }

    public function testUnprocessed()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();
        $unprocessed = factory(Transfer::class, 5)->states('unprocessed')->create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
        $unprocessed->each(function ($transfer) use ($sender, $recipient) {
            $transfer->sender = $sender;
            $transfer->recipient = $recipient;
        });
        $completed = factory(Transfer::class, 5)->states('completed')->create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
        $completed->each(function ($transfer) use ($sender, $recipient) {
            $transfer->sender = $sender;
            $transfer->recipient = $recipient;
        });
        $failed = factory(Transfer::class, 5)->states('failed')->create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
        $failed->each(function ($transfer) use ($sender, $recipient) {
            $transfer->sender = $sender;
            $transfer->recipient = $recipient;
        });
        $transfersFromDb = TransferRepository::unprocessed(5, 0);
        $transfersFromDb->each(function ($transfer) use ($unprocessed, $completed, $failed) {
            $this->assertTrue($unprocessed->contains($transfer));
            $this->assertFalse($completed->contains($transfer));
            $this->assertFalse($failed->contains($transfer));
        });
    }

    public function testProcess()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();
        $transfer = factory(Transfer::class)->states('unprocessed')->create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'time' => Carbon::now()->addHours(-1),
        ]);
        TransferRepository::process($transfer);
        $this->assertDatabaseHas('transfers', [
            'id' => $transfer->id,
            'completed' => 1,
            'with_error' => 0,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $sender->id,
            'balance' => $sender->balance - $transfer->sum,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $recipient->id,
            'balance' => $recipient->balance + $transfer->sum,
        ]);
    }
}
