<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        "name" , "brand" , "image_path" , "description", "gender" , "stock" ,"color" , "price" ,"size"  , "category_id"
    ];
    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    } 
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
