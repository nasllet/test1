@extends('layouts.app')

@section('content')
<div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center py-2 sm:pt-0">
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 100vh;">
        <div class="row g-1 justify-content-center">
            <h1 class="text-start mb-4 fs-3 ps-3" style="font-size: 1.8rem;">商品一覧画面</h1> 
            <div class="col-md-12 border border-dark p-4 rounded bg-white">
                <table class="table table-bordered table-striped table-hover text-center text-nowrap" style="font-size: 1.2rem;">
                    <thead>
                        <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">商品画像</th>
                        <th class="p-3">商品名</th>
                        <th class="p-3">価格</th>
                        <th class="p-3">在庫数</th>
                        <th class="p-3">メーカー名</th>
                        <th class="p-3">
                                <a href="{{ route('productcreate') }}" class="btn btn-warning btn-sm">新規登録</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if ($product->img_path)
                                    <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="img-fluid rounded float-start" style="max-width: 100px;">
                                @else
                                    画像なし
                                @endif
                            </td>
                            <td>{{ $product->product_name }}</td>
                            <td>¥{{ number_format($product->price) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->company_name }}</td>
                            <td>
                                <a href="{{ route('productdetail', ['id' => $product->id]) }}" class="btn btn-info btn-sm">詳細</a>
                                <form action="{{ route('productdestroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">削除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- ページネーション -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection