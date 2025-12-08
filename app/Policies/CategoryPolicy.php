<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    public function modify(Admin $admin, Category $category): Response
    {
        return $admin->id === $category->admin_id ? Response::allow() : Response::deny();
    }
}
