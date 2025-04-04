<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('total_price');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
//説明
//foreignId('product_id')->constrained('products')
//明示的に products テーブルを参照することを指定しましたが、通常、constrained() を使う場合は、このままで大丈夫です（デフォルトで products テーブルの id を参照します）。

//onDelete('cascade')
//これにより、products が削除された場合に、その商品に関連する注文も削除されます。