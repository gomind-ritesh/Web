<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_pwd',
        'customer_firstname',
        'customer_lastname',
        'phone',
        'ban',
    ];

    protected $hidden = [
        'customer_pwd',
    ];

    protected $casts = [
        'customer_pwd' => 'hashed',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class,'customer_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->customer_firstname} {$this->customer_lastname}";
    }
}
