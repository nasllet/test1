@extends('layouts.app')

@section('content')
    <div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center py-2 sm:pt-0">
        <div class="container d-flex justify-content-center align-items-start" style="min-height: 100vh;">
            <div class="row justify-content-center">
                <h1 class="text-start mb-4 fs-3 ms-4" style="font-size: 1.8rem;">商品情報編集画面</h1>
                <div class="col-md-8 border border-dark p-4 rounded bg-white">
                    
                    <form action="{{ route('productupdate', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
              
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <label for="id" class="col-sm-4 text-start pe-3 fw-bold fs-5 font-size: 1.2rem;">ID</label>
                            <input type="text" class="form-control @error('id') is-invalid @enderror" id="id" name="id" value="{{ $product->id }}" readonly>
                        </div>

                        <div class="d-flex align-items-center mb-3 gap-3">
                            <label for="product_name" class="col-sm-4 text-start pe-3 fw-bold fs-5 font-size: 1.2rem;">商品名</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" >
                            @error('product_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

      
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <label for="company_id" class="col-sm-4 text-start pe-3 fw-bold fs-5 font-size: 1.2rem;">メーカー名</label>
                            <select class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id" >
                                <option value="">選択してください</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

        
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <label for="price" class="col-sm-4 text-start pe-3 fw-bold fs-5 font-size: 1.2rem;">価格</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" >
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center mb-3 gap-3">
                            <label for="stock" class="col-sm-4 text-start pe-3 fw-bold fs-5 font-size: 1.2rem;">在庫数</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" >
                            @error('stock')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

      
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <label for="comment" class="col-sm-4 text-start pe-3 fw-bold fs-5 font-size: 1.2rem;">コメント</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3">{{ old('comment', $product->comment) }}</textarea>
                            @error('comment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center mb-3 gap-3">
                            <label for="img_path" class="col-sm-4 text-start pe-3 fw-bold fs-5 font-size: 1.2rem;">画像</label>
                            <input type="file" class="form-control @error('img_path') is-invalid @enderror" id="img_path" name="img_path">
                            @error('img_path')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

   
                        <div class="text-start mt-4">
                            <button type="submit" class="btn btn-warning btn-sm">更新</button>
                            <a href="{{ route('productdetail', ['id' => $product->id]) }}" class="btn btn-info btn-sm ms-2">戻る</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection