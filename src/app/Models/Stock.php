<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;


class Stock extends Model
{
    use SoftDeletes, HasApiTokens, HasFactory;

    protected $fillable = [
        'product_id', 
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
