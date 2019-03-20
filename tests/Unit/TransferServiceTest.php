<?php

namespace Tests\Unit;

use App\Models\Transfer;
use App\Models\User;
use App\Facades\TransferRepository;
use App\Facades\TransferService;
use Mockery;
use Tests\TestCase;

class TransferServiceTest extends TestCase
{

    public function testStore()
    {
        $user = Mockery::mock(User::class);
        $attributes = [
            'sum' => 10,
            'sender_id' => 1,
        ];
        $user->shouldReceive('getAttribute')
            ->with('balance')
            ->once()
            ->andReturn(100);
        $user->shouldReceive('getAttribute')
            ->with('id')
            ->once()
            ->andReturn(1);
        TransferRepository::shouldReceive('unprocessedSum')
            ->with($user)
            ->once()
            ->andReturn(0);
        TransferRepository::shouldReceive('store')
            ->with($attributes)
            ->once();
        TransferService::store($attributes, $user);
    }

    public function testUnprocessed()
    {
        TransferRepository::shouldReceive('unprocessed')
            ->withArgs([10, 0])
            ->once()
            ->andReturn([]);
        TransferService::unprocessed(10, 0);
    }

    public function testProcess()
    {
        $sender = Mockery::mock(User::class);
        $sender->shouldReceive('getAttribute')
            ->with('balance')
            ->once()
            ->andReturn(100);
        $transfer = Mockery::mock(Transfer::class);
        $transfer->shouldReceive('getAttribute')
            ->with('sender')
            ->once()
            ->andReturn($sender);
        $transfer->shouldReceive('getAttribute')
            ->with('sum')
            ->once()
            ->andReturn(50);
        TransferRepository::shouldReceive('process')
            ->with($transfer)
            ->once();
        TransferService::process($transfer);
    }
}
