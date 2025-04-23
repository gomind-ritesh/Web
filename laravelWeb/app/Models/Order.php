<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use carbon\carbon;

class Order extends Model
{
    protected $primaryKey = 'bill_id';
    
    protected $fillable = [
        'bill_date','customer_id', 'bill_discount', 'status', 'reviewed'
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id' , 'customer_id');
    }

    public function food()
    {
        return $this->belongsToMany(Food::class,'food_id' , 'food_id')
                    ->withPivot('item_qty')
                    ->withTimestamps();
    }
    
   // One-to-Many relationship with BillFood for direct access to the pivot data
   public function billFoods()
   {
       return $this->hasMany(BillFood::class, 'bill_id');
   }

}
