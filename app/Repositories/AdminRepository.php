<?php 

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class AdminRepository {
    public function create(array $data): Model {
        return Admin::create($data);
    }

    public function findByEmail($email) {
        return Admin::where("email", $email)->first();
    }
}