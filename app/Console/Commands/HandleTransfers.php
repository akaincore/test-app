<?php

namespace App\Console\Commands;

use App\Exceptions\NotEnoughBalanceException;
use App\Models\Transfer;
use App\Services\TransferService;
use Illuminate\Console\Command;

class HandleTransfers extends Command
{

    const ITEMS_LIMIT = 100;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfers:handle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command handles delayed users transfers';
    /**
     * @var TransferService
     */
    private $transferService;

    /**
     * Create a new command instance.
     *
     * @param TransferService $transferService
     */
    public function __construct(TransferService $transferService)
    {
        parent::__construct();
        $this->transferService = $transferService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $i = 0;
        do {
            $unprocessed = $this->transferService->unprocessed(self::ITEMS_LIMIT, self::ITEMS_LIMIT * $i);
            $unprocessed->each(function (Transfer $transfer) {
                try {
                    $this->transferService->process($transfer);
                    $this->info($transfer->id . ' processed');
                } catch (NotEnoughBalanceException $exception) {
                    $this->warn($transfer->id . ' failed with not enough balance');
                    $transfer->with_error = 1;
                    $transfer->save();
                }
            });
            $i++;
        } while ($unprocessed->count());
    }
}
