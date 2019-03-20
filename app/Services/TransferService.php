<?php

namespace App\Services;

use App\Exceptions\NotEnoughBalanceException;
use App\Models\Transfer;
use App\Models\User;
use App\Repositories\TransferRepository;
use App\Repositories\UserRepository;

class TransferService
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var TransferRepository
     */
    private $transferRepository;

    public function __construct(UserRepository $userRepository, TransferRepository $transferRepository)
    {
        $this->userRepository = $userRepository;
        $this->transferRepository = $transferRepository;
    }

    /**
     * @param array $attributes
     * @param User $user
     * @throws NotEnoughBalanceException
     */
    public function store(array $attributes, User $user)
    {
        $unprocessedSum = $this->transferRepository->unprocessedSum($user);
        if ($user->balance - $unprocessedSum < $attributes['sum']) {
            throw new NotEnoughBalanceException;
        }
        $attributes['sender_id'] = $user->id;
        $this->transferRepository->store($attributes);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return TransferRepository[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function unprocessed(int $limit, int $offset)
    {
        return $this->transferRepository->unprocessed($limit, $offset);
    }


    /**
     * @param Transfer $transfer
     * @throws NotEnoughBalanceException
     */
    public function process(Transfer $transfer)
    {
        if ($transfer->sender->balance < $transfer->sum) {
            throw new NotEnoughBalanceException;
        }
        $this->transferRepository->process($transfer);
    }
}
