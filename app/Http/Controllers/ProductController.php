<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest; 
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 商品情報一覧
    public function show(Request $request)
    {
        $products = Product::with('company');

        // キーワード検索
        $keyword = $request->input('keyword');
        if (!empty($keyword)) {
            $products = $products->where('product_name', 'like', "%{$keyword}%")
                ->orWhereHas('company', function ($q) use ($keyword) {
                    $q->where('company_name', 'like', "%{$keyword}%");
                });
        }

        $companyId = $request->input('company_id');
        if (!empty($companyId)) {
            $products = $products->where('company_id', $companyId);
        }

        $products = $products->paginate(10);

        $company = new Company();
        $companies = $company->getCompany();
        return view('product', compact('products', 'companies'));
    }

    // 商品情報登録画面
    public function create(Request $request)
    {
        $company = new Company();
        $companies = $company->getCompany();
        return view('create', compact('companies'));
    }
    // 商品情報登録処理
    public function store(ProductRequest $request)
    {
        // トランザクション開始
        DB::beginTransaction();

        try {
            $validatedData = $request->validated(); // バリデーション済みデータ取得
            Product::create($validatedData); // 商品データ登録

            // トランザクションのコミット
            DB::commit();

            return redirect()->route('productlist')->with('success', '商品が登録されました');
        } catch (\Exception $e) {
            // エラー発生時はロールバック
            DB::rollBack();
            return redirect()->route('productlist')->with('error', '商品登録中にエラーが発生しました');
        }
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
        return view('edit', compact('product', 'companies'));
    }

    // 商品情報編集の更新処理
     public function update(ProductRequest $request, $id)
     {
         $product = Product::find($id);
 
         if (!$product) {
             return redirect()->route('productlist')->with('error', '商品が見つかりません');
         }
 
         // トランザクション開始
         DB::beginTransaction();
 
         try {
             $validatedData = $request->validated(); // バリデーション済みデータ取得
             $product->update($validatedData); // 商品情報を更新
 
             // トランザクションのコミット
             DB::commit();
 
             return redirect()->route('productlist')->with('success', '商品が更新されました');
         } catch (\Exception $e) {
             // エラー発生時はロールバック
             DB::rollBack();
             return redirect()->route('productlist')->with('error', '商品更新中にエラーが発生しました');
         }
     }

    // 商品削除処理
    public function destroy($id)
    {
        // トランザクション開始
        DB::beginTransaction();

        try {
            $product = Product::find($id);

            // 商品が存在しない場合の処理
            if (!$product) {
                DB::rollBack(); 
                return redirect()->route('productlist')->with('error', '商品が見つかりません');
            }

            // 商品を削除
            $product->delete();

            // トランザクションのコミット
            DB::commit();

            return redirect()->route('productlist')->with('success', '商品が削除されました');
        } catch (\Exception $e) {
            // エラー発生時はロールバック
            DB::rollBack();
            return redirect()->route('productlist')->with('error', '商品削除中にエラーが発生しました');
        }
    }
}





