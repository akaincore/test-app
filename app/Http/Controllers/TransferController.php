<?php

namespace App\Http\Controllers;

use App\Exceptions\NotEnoughBalanceException;
use App\Http\Requests\CreateTransfer;
use App\Services\TransferService;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    /**
     * @var TransferService
     */
    private $transferService;

    /**
     * @param TransferService $transferService
     */
    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * @param CreateTransfer $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateTransfer $request)
    {
        try {
            $this->transferService->store($request->validated(), Auth::user());
        } catch (NotEnoughBalanceException $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'Not enough balance to create transfer'
                ]);
        }
        session()->flash('alert', 'Transfer created!');
        return redirect()->route('users.index');
    }
}
