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
        
        $sortColumn = $request->input('sort_column', 'id'); // デフォルト: ID
        $sortDirection = $request->input('sort_direction', 'asc'); // デフォルト: 昇順（asc）
    
        if (!in_array($sortColumn, ['id', 'product_name', 'price', 'stock'])) {
            $sortColumn = 'id'; // 不正なカラム名の場合、デフォルトに戻す
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // 不正なソート方向の場合、デフォルトに戻す
        }
        $products = $products->paginate(5);

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
    DB::beginTransaction();

    try {
        // バリデーションを通過したデータを取得
        $validatedData = $request->validated(); 

        // 画像がアップロードされているかチェック
        if ($request->hasFile('img_path')) {
            // 画像をstorageディレクトリに保存
            $path = $request->file('img_path')->store('public/images');
            $validatedData['img_path'] = $path; // 画像パスを配列に追加
        }

        // 商品データ登録
        Product::create($validatedData); 

        // トランザクションのコミット
        DB::commit();

        return redirect()->route('productlist')->with('success', '商品が登録されました');
    } catch (\Exception $e) {
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
    
        DB::beginTransaction();
    
        try {
            // バリデーションを通過したデータを取得
            $validatedData = $request->validated(); 
    
            // 画像がアップロードされているかチェック
            if ($request->hasFile('img_path')) {
                // 画像をstorageディレクトリに保存
                $path = $request->file('img_path')->store('public/images');
                $validatedData['img_path'] = $path; // 画像パスを配列に追加
            }
    
            // 商品情報を更新
            $product->update($validatedData); 
    
            // トランザクションのコミット
            DB::commit();
    
            return redirect()->route('productlist')->with('success', '商品が更新されました');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('productlist')->with('error', '商品更新中にエラーが発生しました');
        }
    }

    // 商品削除処理
    public function destroy($id)
{
    DB::beginTransaction();

    try {
        $product = Product::find($id);//商品を検索

        if (!$product) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => '商品が見つかりません'], 404);
        }

        $product->delete();//商品を削除
        DB::commit();

        return response()->json(['success' => true, 'message' => '商品が削除されました']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => '商品削除中にエラーが発生しました'], 500);
    }
}
    // 商品検索の処理
    public function productsearch(Request $request)
{
    $products = Product::with('company');
    
        // キーワード検索
        $keyword = $request->input('keyword');
        if (!empty($keyword)) {
            $products = $products->where(function ($query) use ($keyword) { // 🔹 ここで開いた `{`
                $query->where('product_name', 'like', "%{$keyword}%")
                      ->orWhereHas('company', function ($q) use ($keyword) {
                          $q->where('company_name', 'like', "%{$keyword}%");
                      });
            }); // 🔹 ここで閉じる `}`
        }
    
        // メーカーIDによる絞り込み
        $companyId = $request->input('company_id');
        if (!empty($companyId)) {
            $products = $products->where('company_id', $companyId);
        }
    
        // 価格の範囲検索
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        if (isset($minPrice) && isset($maxPrice)) {
            $products = $products->whereBetween('price', [$minPrice, $maxPrice]);
        } elseif (isset($minPrice)) {
            $products = $products->where('price', '>=', $minPrice);
        } elseif (isset($maxPrice)) {
            $products = $products->where('price', '<=', $maxPrice);
        }
    
        // 在庫数の範囲検索
        $minStock = $request->input('min_stock');
        $maxStock = $request->input('max_stock');
    
        if (isset($minStock) && isset($maxStock)) {
            $products = $products->whereBetween('stock', [$minStock, $maxStock]);
        } elseif (isset($minStock)) {
            $products = $products->where('stock', '>=', $minStock);
        } elseif ($maxStock !== null && $maxStock !== "") { // `maxStock === "0"` でも適用
            $products = $products->where('stock', '<=', $maxStock);
        }
    
        // ページネーション
        $products = $products->paginate(5);

    return response()->json([
        'products' => $products,
        'pagination' => (string) $products->links('pagination::bootstrap-4')
    ]);
    }
    
}