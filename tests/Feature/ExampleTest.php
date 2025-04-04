<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order()
    {
        // サンプル商品を作成
        $product = Product::create([
            'product_name' => 'サンプル商品',
            'price' => 5000,
            'stock' => 10,
            'company_id' => 1
        ]);

        // 購入リクエストを送信
        $response = $this->postJson('/api/purchase', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // レスポンスの確認
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'order' => [
                         'id',
                         'product_id',
                         'quantity',
                         'total_price'
                     ]
                 ]);

        // 注文が作成され、関連商品情報が正しいことを確認
        $order = Order::first();
        $this->assertEquals(2, $order->quantity);
        $this->assertEquals($product->price * 2, $order->total_price);

        // 注文に関連する商品情報を取得して確認
        $productFromOrder = $order->product;
        $this->assertEquals($product->id, $productFromOrder->id);
    }
}
