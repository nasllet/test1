<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public function getProduct()
    {
        return DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select(
                'products.id',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                'products.img_path',
                'products.company_id',
                'companies.company_name as company_name'
            )
            ->paginate(2);
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
//更新処理
    public function updateProduct($request)
{
    $this->product_name = $request->product_name;
    $this->company_id = $request->company_id;
    $this->price = $request->price;
    $this->stock = $request->stock;
    $this->comment = $request->comment;

    if ($request->hasFile('img_path')) {
        $this->img_path = $request->file('img_path')->store('images', 'public');
    }

    $this->save();  
}

//新規登録処理
public function storeProduct($request)
{
    $this->product_name = $request->product_name;
    $this->company_id = $request->company_id;
    $this->price = $request->price;
    $this->stock = $request->stock;
    $this->comment = $request->comment;
    
    if ($request->hasFile('img_path')) {
        $this->img_path = $request->file('img_path')->store('images', 'public');
    }

    $this->save();  
}

    // 1つの商品は0回以上販売される
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }


}
