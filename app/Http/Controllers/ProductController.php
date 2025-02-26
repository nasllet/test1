<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    // 登録処理
    public function store(Request $request)
{
    // バリデーション
    $validated = $request->validate([
        'product_name' => 'required',
        'company_id' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'comment' => 'nullable',
    ], [
        'product_name.required' => '商品名を入力してください。',
        'company_id.required' => 'メーカー名を選択してください。',
        'price.required' => '価格を入力してください。',
        'price.numeric' => '価格は数値で入力してください。',
        'stock.required' => '在庫数を入力してください。',
        'stock.integer' => '在庫数は整数で入力してください。',
    ]);


        // トランザクション開始
        DB::beginTransaction();

        try {
            $product = new Product();
            $product->storeProduct($request);

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
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable',
        ], [
            'product_name.required' => '商品名を入力してください。',
            'company_id.required' => 'メーカー名を選択してください。',
            'price.required' => '価格を入力してください。',
            'price.numeric' => '価格は数値で入力してください。',
            'stock.required' => '在庫数を入力してください。',
            'stock.integer' => '在庫数は整数で入力してください。',
        ]);

        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('productlist');
        }

        // トランザクション開始
        DB::beginTransaction();

        try {
            $product->updateProduct($request);

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





