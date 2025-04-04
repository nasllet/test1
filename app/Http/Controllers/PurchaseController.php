<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction(); // トランザクション開始
        try {
            $product = Product::findOrFail($request->product_id);

            if ($product->stock < $request->quantity) {
                return response()->json(['message' => '在庫不足です'], 400);
            }

            // 購入処理（在庫減少）
            $product->stock -= $request->quantity;
            $product->save();

            // 注文の作成
            $sale = Sale::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $product->price * $request->quantity
            ]);

            DB::commit(); // 成功したらコミット

            // 注文情報と関連する商品情報を取得
            $product = $sale->product;
            return response()->json([
             'message' => '購入完了',
             'sale' => $sale, 
             'product' => $product
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '購入処理に失敗しました',
                'error' => $e->getMessage() // ← 例外の詳細を追加
            ], 500);
        }
    }
}

