<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use SoftDeletes, HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail', 'unit_price'
    ];


    // Define a function to get the total stock quantity for a product
    public static function getStock($product_id)
    {
        // Use the 'where' method to filter by the product_id and 'sum' to calculate the total quantity
        return Stock::where('product_id', $product_id)->sum('quantity');
    }
}
