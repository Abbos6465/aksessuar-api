<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{

    public function __construct()
    {
        
    }

    public function update(User $user,Product $product){
        return ($user->id === $product->user_id || $user->role_id === 1);
    }

    public function delete(User $user,Product $product){
        return ($user->id === $product->user_id || $user->role_id === 1);
    }
}
