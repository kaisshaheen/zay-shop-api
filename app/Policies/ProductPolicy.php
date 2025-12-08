<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
      public function modify(Admin $admin, Product $product): Response
    {
        return $admin->id === $product->admin_id ? Response::allow() : Response::deny();
    }
}
