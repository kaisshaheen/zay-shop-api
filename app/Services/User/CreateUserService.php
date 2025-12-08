<?php 

namespace App\Services\User;

use App\Repositories\UserRepository;

class CreateUserService {
    private $users;
    public function __construct(UserRepository $users){
        $this->users = $users;
    }
    
    public function handle($data){
        $user = $this->users->create($data);
        return $user;
    }
    
}