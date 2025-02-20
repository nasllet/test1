<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class sale extends Model
{
    protected $fillable = [
        'id',
        'product_id',
    ];
public function getSale()
{
    return DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('sales.id', 'sales.product_name') 
            ->get();
}
}
