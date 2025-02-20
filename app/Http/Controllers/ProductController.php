<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    // 商品情報一覧
    public function show()
    {
        $product = new Product();
        $products = $product->getProduct();
        return view('product',compact('products'));
    }

    // 商品情報登録画面
    public function create(Request $request)
    {
        $company = new Company(); 
        $companies = $company->getCompany();
        return view('create',compact('companies'));
    }

    // 登録処理
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable',
        ]);
    
        $product = new Product();  
        $product->storeProduct($request);  
    
        return redirect()->route('productlist')->with('success', '商品が登録されました');
    }

    // 詳細画面の表示＆エラー表示
    public function detail($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('productlist')->with('error', '商品が見つかりません');
        }

        return view('detail', compact('product'));
    }

    // 編集画面の表示＆エラー表示
    public function edit($id)
{
    $product = Product::find($id);

    if (!$product) {
        return redirect()->route('productlist')->with('error', '商品が見つかりません');
    }
        $companies = Company::get();
        return view('edit', compact('product','companies'));

    }

    // 商品情報編集の更新処理
    public function update(Request $request, $id)
{
    $request->validate([
        'product_name' => 'required|string',
        'company_id' => 'required|exists:companies,id',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'comment' => 'nullable',
    ]);

    $product = Product::find($id);

    if (!$product) {
        return redirect()->route('productlist');
    }

    $product->updateProduct($request);

    return redirect()->route('productlist');
}

public function destroy($id)
{
    $product = Product::find($id);

    // 商品が存在しない場合の処理
    if (!$product) {
        return redirect()->route('productlist');
    }

    // 商品を削除
    $product->delete();

    return redirect()->route('productlist');
}

}






