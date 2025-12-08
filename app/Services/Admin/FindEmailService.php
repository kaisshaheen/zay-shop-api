<?php

namespace App\Services\Admin;

use App\Repositories\AdminRepository;



class FindEmailService{
    private $admin;
    public function __construct(AdminRepository $admin){
        $this->admin = $admin;
    }
    public function handle($email){
        $admin_email = $this->admin->findByEmail($email);
        return $admin_email;
    }
}