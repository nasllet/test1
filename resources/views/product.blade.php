@extends('layouts.app')

@section('content')
<div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center py-2 sm:pt-0">
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 100vh;">
        <div class="row g-1 justify-content-center">
            <h1 class="text-start mb-4 fs-3 ps-3" style="font-size: 1.8rem;">商品一覧画面</h1> 
            <form id="search-form" method="GET">
    <input class="border border-dark rounded" type="text" name="keyword" id="keyword" 
           value="{{ request('keyword') }}" placeholder="商品名・メーカー名を検索">

    <select class="border border-dark rounded" name="company_id" id="company_id">
        <option value="">すべてのメーカー</option>
        @foreach($companies as $company)
            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                {{ $company->company_name }}
            </option>
        @endforeach
    </select>

    <input class="border border-dark rounded" type="number" id="min_price" name="min_price" 
           placeholder="最小価格" value="{{ request('min_price') }}" style="width: 100px;">
    <input class="border border-dark rounded" type="number" id="max_price" name="max_price" 
           placeholder="最大価格" value="{{ request('max_price') }}" style="width: 100px;"> 

    <input class="border border-dark rounded" type="number" id="min_stock" name="min_stock" 
           placeholder="最小在庫" value="{{ request('min_stock') }}" style="width: 100px;">
    <input class="border border-dark rounded" type="number" id="max_stock" name="max_stock" 
           placeholder="最大在庫" value="{{ request('max_stock') }}" style="width: 100px;">

    <button type="submit" id="search-btn" class="btn btn-success btn-sm">検索</button>
</form>


<div class="col-md-12 border border-dark p-4 rounded bg-white" id="product-list">
<table id="product-table" class="table table-bordered table-striped table-hover tablesorter text-center text-nowrap" style="font-size: 1.2rem;">
    <thead>
        <tr>
            <th class="p-3 text-nowrap">ID</th>
            <th class="p-3 sorter-false text-nowrap">商品画像</th>
            <th class="p-3 text-nowrap">商品名</th>
            <th class="p-3 text-nowrap">価格</th>
            <th class="p-3 text-nowrap">在庫数</th>
            <th class="p-3 text-nowrap">メーカー名</th>
            <th class="p-3 sorter-false text-nowrap">
                <a href="{{ route('productcreate') }}" class="btn btn-warning btn-sm">新規登録</a>
            </th>
        </tr>
    </thead>
    <tbody id="product-table-body">
        @foreach ($products as $product)
        <tr id="product-row-{{ $product->id }}">
            <td>{{ $product->id }}</td>
            <td>
                @if ($product->img_path)
                    <img src="{{ asset('storage/images/' . basename($product->img_path)) }}" alt="商品画像" class="img-fluid rounded" style="max-width: 80px;">
                @else
                    画像なし
                @endif
            </td>
            <td>{{ $product->product_name }}</td>
            <td>¥{{ number_format($product->price) }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->company->company_name }}</td>
            <td>
                <a href="{{ route('productdetail', ['id' => $product->id]) }}" class="btn btn-info btn-sm">詳細</a>
                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $product->id }}">削除</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script>
  $(document).ready(function () {
    // tablesorter 初期化
    $("#product-table").tablesorter();

    // 検索機能
    function searchProducts() {
        $.ajax({ //👉 AJAXリクエストを送信する関数（jQuery）ページをリロードせずに、非同期でサーバーにリクエストを送るために使います
            url: "{{ route('productsearch') }}",// 検索APIのエンドポイント productsearch というルートの URL を取得する
            method: "GET", //👉 HTTPリクエストの種類（GETリクエスト）
            data: { //👉 サーバーに送るデータ（検索条件）
                keyword: $("#keyword").val(),
                company_id: $("#company_id").val(),
                min_price: parseFloat($("#min_price").val()) || null,
                max_price: parseFloat($("#max_price").val()) || null,
                min_stock: parseFloat($("#min_stock").val()) || null,
                max_stock: parseFloat($("#max_stock").val()) || null
            },
            success: function(response) {//AJAXリクエストが成功したときの処理
                var productListHtml = '';//商品一覧のHTMLを一時的に保存する変数を用意
                response.products.data.forEach(function(product) {//検索結果の products.data に含まれる各商品を処理
                    var imgPath = product.img_path 
                        ? "{{ asset('storage/images') }}/" + product.img_path.split('/').pop() //商品画像のパスを取得  product.img_path が 存在する場合
                        : null;//product.img_path が 存在しない場合
                   
                    //商品データを <tr>（テーブルの行）としてHTMLに追加 
                    productListHtml += `   
                        <tr id="product-row-${product.id}"> 
                            <td>${product.id}</td>
                            <td>${imgPath ? `<img src="${imgPath}" alt="商品画像" class="img-fluid rounded" style="max-width: 80px;">` : '画像なし'}</td>
                            <td>${product.product_name}</td>
                            <td>¥${product.price.toLocaleString()}</td>
                            <td>${product.stock}</td>
                            <td>${product.company.company_name}</td>
                            <td>
                                <a href="/product/detail/${product.id}" class="btn btn-info btn-sm">詳細</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${product.id}">削除</button>
                            </td>
                        </tr>
                    `;
                });
 
                // 検索結果をテーブルに更新
                $("#product-table-body").html(productListHtml); //テーブルの内容を新しい検索結果に置き換える

                // tablesorter の更新
                $("#product-table").tablesorter();  //tablesorter を初期化する,tablesorter プラグインを適用し、テーブルをソート可能にする。
                $("#product-table").trigger("update"); // tablesorter を最新のデータに対応させる
            },
            error: function(xhr, status, error) {//検索リクエストが失敗したときの処理
                console.log(xhr.responseText);
                alert("検索に失敗しました");
            }
        });
    }

    // 削除処理
    $(document).on('click', '.delete-btn', function(event) {//delete-btnクラスを持つボタンがクリックされたら 処理を実行する。
        event.preventDefault();//ページのリロードを防ぎ、AJAX で削除処理を実行 する。
        var productId = $(this).data('id'); //削除対象の productId を取得
        var deleteUrl = "{{ route('productdestroy', ':id') }}".replace(':id', productId);//Laravel の route() を使って 削除用のURL を生成（:id を productId に置換）

        if (!confirm('本当に削除しますか？')) {//confirm() を使って、ユーザーに 削除の確認ダイアログ を表示
            return;// 処理を中断
        }

        $.ajax({    //👉 AJAXリクエストを送信する関数（jQuery）ページをリロードせずに、非同期でサーバーにリクエストを送るために使います           
            url: deleteUrl,//削除APIのエンドポイント deleteUrl という作成した URL を取得する
            type: 'DELETE',//HTTPリクエストの種類（DELETEリクエスト）
            data: {//サーバーに送るデータ内容
                _token: $('meta[name="csrf-token"]').attr('content')//CSRFトークン を送信する
            },
            success: function(response) {//AJAXリクエストが成功したときの処理
                if (response.success) {//サーバーから返ってきた response の中に success というキーがあるか確認
                    alert(response.message);//削除成功のメッセージをポップアップ表示 する
                    $(`#product-row-${productId}`).fadeOut(500, function() { // 削除した商品の行（tr）をフェードアウト（0.5秒で消す）
                        $(this).remove(); //HTMLから完全に削除
                    });
                } else {
                    alert('削除に失敗しました');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('エラーが発生しました');
            }
        });
    });

    // フィルタイベント
    $('#keyword, #company_id, #min_price, #max_price, #min_stock, #max_stock').on('change keyup', function() {
        searchProducts();
    });

    $(document).on("click", "#search-btn", function(event) {
        event.preventDefault();
        searchProducts();
    });
});

</script>

@endsection