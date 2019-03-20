<?php

namespace Tests\Feature;

use App\Models\Transfer;
use App\Models\User;
use App\Repositories\UserRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRepositoryTest extends TestCase
{

    use RefreshDatabase;

    public function testUsersWithLastTransfer()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();
        $transfer = factory(Transfer::class)->states('completed')->create();
        $userWithLastTransfer = UserRepository::class;
    }
}
