<?php

namespace App\Services\User;

use App\Repositories\UserRepository;

class FindEmailService{
    private $users;
    public function __construct(UserRepository $users){
        $this->users = $users;
    }
    public function handle($email){
        $user_email = $this->users->findByEmail($email);
        return $user_email;
    }
}