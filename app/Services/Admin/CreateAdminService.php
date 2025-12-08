<?php 

namespace App\Services\Admin;


use App\Repositories\AdminRepository;
use Illuminate\Database\Eloquent\Model;

class CreateAdminService {
    private $admin;
    public function __construct(AdminRepository $admin){
        $this->admin = $admin;
    }
    
    public function handle(array $data): Model{
        $admin = $this->admin->create(data: $data);
        return $admin;
    }
    
}