@extends('layouts.app')

@section('content')
<div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center py-2 sm:pt-0">
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 100vh;">
        <div class="row justify-content-center">
        <h1 class="text-start mb-4 fs-3 ms-4" style="font-size: 1.9rem;">商品詳細</h1> 
            <div class=" col-md-9 border border-dark p-4 rounded bg-white"> 
                
            <div class="d-flex align-items-center mb-3 gap-3">
                    <label class="col-sm-4 text-nowrap fw-bold fs-5">ID</label>
                    <div class="col-sm-9 fs-5 ps-5">{{ $product->id }}</div>
                </div>

                <div class="d-flex align-items-center mb-3 gap-3">
                    <label class="col-sm-4 text-nowrap fw-bold fs-5">商品画像</label>
                    <div class="col-sm-9  ps-5">
                        <img src="{{ asset('storage/' . $product->img_path) }}" class="img-fluid rounded d-block float-start" style="max-width: 140px;">
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3 gap-3">
                    <label class="col-sm-4 text-nowrap fw-bold fs-5">商品名</label>
                    <div class="col-sm-9 fs-5 ps-5">{{ $product->product_name }}</div>
                </div>

                <div class="d-flex align-items-center mb-3 gap-3">
                    <label class="col-sm-4 text-nowrap fw-bold fs-5">価格</label>
                    <div class="col-sm-9 fs-5 ps-5">¥{{ number_format($product->price) }}</div>
                </div>

                <div class="d-flex align-items-center mb-3 gap-3">
                    <label class="col-sm-4 text-nowrap fw-bold fs-5">在庫数</label>
                    <div class="col-sm-9 fs-5 ps-5">{{ $product->stock }}</div>
                </div>

                <div class="d-flex align-items-center mb-3 gap-3">
                    <label class="col-sm-4 text-nowrap fw-bold fs-5">メーカー名</label>
                    <div class="col-sm-9 fs-5 ps-5">{{ $product->company->company_name }}</div>
                </div>

                <div class="d-flex align-items-center mb-3 gap-3">
                    <label class="col-sm-4 text-nowrap fw-bold fs-5">コメント</label>
                    <div class="col-sm-9 fs-5 ps-5">{{ $product->comment }}</div>
                </div>

                <div class="text-start mt-4">
                    <a href="{{ route('productedit', ['id' => $product->id]) }}" class="btn btn-warning btn-sm">編集</a>
                    <a href="{{ route('productlist') }}" class="btn btn-info btn-sm ms-2">戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection