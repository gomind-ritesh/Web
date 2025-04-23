<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    /** @use HasFactory<\Database\Factories\FoodFactory> */
    use HasFactory;
    
    protected $primaryKey = 'food_id';
    
    protected $fillable = [
        'food_name', 'food_price', 'food_discount', 'food_category', 
        'food_type', 'food_source', 'available'
    ];
    
    // Many-to-Many relationship with Order through the bill_foods pivot table
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'bill_foods', 'food_id', 'bill_id')
                    ->withPivot('item_qty')
                    ->withTimestamps();
    }
    
    // One-to-Many relationship with BillFood
    public function billFoods()
    {
        return $this->hasMany(BillFood::class, 'food_id');
    }

}
