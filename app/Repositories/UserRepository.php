<?php

namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{

    /**
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function withLastSentTransfer()
    {
        $users = User::leftJoin(DB::raw('
            (
                select 
                 t1.id as transfer_id,
                 t1.sender_id as transfer_sender_id,
                 t1.recipient_id as transfer_recipient_id,
                 t1.sum as transfer_sum,
                 t1.time as transfer_time,
                 t1.created_at as transfer_created_at,
                 t1.completed as transfer_completed
                from transfers t1
                 left join transfers t2 
                 on t1.sender_id = t2.sender_id 
                 and t1.created_at < t2.created_at
                where t2.sender_id is null
            ) tmp
        '), 'users.id', '=', 'tmp.transfer_sender_id')
            ->simplePaginate();
        return $users;
    }

}
