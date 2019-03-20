<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userService
     */
    public function __construct(UserRepository $userService)
    {
        $this->userRepository = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = $this->userRepository->withLastSentTransfer();
        return view('users', [
            'users' => $users,
        ]);
    }
}
