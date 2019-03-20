<?php

namespace App\Repositories;


use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferRepository
{

    /**
     * @param array $attributes
     */
    public function store(array $attributes)
    {
        Transfer::create($attributes);
    }

    /**
     * @param User $user
     * @return int
     */
    public function unprocessedSum(User $user)
    {
        return $user->sentTransfers()
            ->unprocessed()
            ->sum('sum');
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Transfer[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function unprocessed(int $limit, int $offset)
    {
        return Transfer::with([
            'sender',
            'recipient',
        ])
            ->unprocessed()
            ->shouldBeProcessed()
            ->orderBy('time')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function process(Transfer $transfer)
    {
        DB::transaction(function () use ($transfer) {
            $transfer->sender->balance -= $transfer->sum;
            $transfer->sender->save();
            $transfer->recipient->balance += $transfer->sum;
            $transfer->recipient->save();
            $transfer->completed = 1;
            $transfer->save();
        });
    }

}
