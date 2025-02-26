@extends('layouts.app')

@section('content')
<div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center py-2 sm:pt-0">
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 100vh;">
        <div class="col-md-6 mx-auto"> 
            <h1 class="ms-4 mb-4 fs-3" style="font-size: 1.8rem;">新規登録画面</h1> 
            <div class="border border-dark bg-white p-4 rounded">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('productstore') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="product_name" class="col-sm-3 col-form-label col-form-label-sm" style="font-size: 1.2rem;">商品名</label> 
                        <div class="col-sm-8">
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name') }}"  style="font-size: 1.2rem;"> 
                            @error('product_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="company_id" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">メーカー名</label> 
                        <div class="col-sm-8">
                            <select class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id"  style="font-size: 1.2rem;"> 
                                <option value="">選択してください</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="price" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">価格</label> 
                        <div class="col-sm-8">
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}"  style="font-size: 1.2rem;"> 
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="stock" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">在庫数</label> 
                        <div class="col-sm-8">
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}"  style="font-size: 1.2rem;"> 
                            @error('stock')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="comment" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">コメント</label> 
                        <div class="col-sm-8">
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3" style="font-size: 1.2rem;">{{ old('comment') }}</textarea> 
                            @error('comment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="img_path" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">画像</label> 
                        <div class="col-sm-8">
                            <input type="file" class="form-control-file @error('img_path') is-invalid @enderror" id="img_path" name="img_path" style="font-size: 1.2rem;"> 
                            @error('img_path')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-3 d-flex text-start">
                        <button type="submit" class="btn btn-warning btn-sm px-3 py-1">新規登録</button> 
                        <a href="{{ route('productlist') }}" class="btn btn-info btn-sm px-3 py-1 ms-2">戻る</a> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection