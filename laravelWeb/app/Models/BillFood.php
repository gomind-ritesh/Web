<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillFood extends Model
{
    /** @use HasFactory<\Database\Factories\BillFoodFactory> */
    use HasFactory;
    
    protected $table = 'bill_foods';
    
    protected $fillable = [
        'bill_id', 'food_id', 'item_qty'
    ];
    
    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'bill_id');
    }
    
    // Relationship with Food
    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
