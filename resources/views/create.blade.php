@extends('layouts.app')

@section('content')
<div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center py-2 sm:pt-0">
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 100vh;">
        <div class="col-md-6 mx-auto"> <!-- 幅を col-md-6 に変更 -->
            <h1 class="ms-4 mb-4 fs-3" style="font-size: 1.8rem;">新規登録画面</h1> 
            <div class="border border-dark bg-white p-4 rounded">
                <form action="{{ route('productstore') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="product_name" class="col-sm-3 col-form-label col-form-label-sm" style="font-size: 1.2rem;">商品名</label> 
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="product_name" name="product_name" required style="font-size: 1.2rem;"> 
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="company_id" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">メーカー名</label> 
                        <div class="col-sm-8">
                            <select class="form-control" id="company_id" name="company_id" required style="font-size: 1.2rem;"> 
                                <option value="">選択してください</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="price" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">価格</label> 
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="price" name="price" required style="font-size: 1.2rem;"> 
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="stock" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">在庫数</label> 
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="stock" name="stock" required style="font-size: 1.2rem;"> 
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="comment" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">コメント</label> 
                        <div class="col-sm-8">
                            <textarea class="form-control" id="comment" name="comment" rows="3"  style="font-size: 1.2rem;"></textarea> 
                        </div>
                    </div>

                    <div class="form-group row fw-bold mb-3 text-nowrap">
                        <label for="img_path" class="col-sm-3 col-form-label" style="font-size: 1.2rem;">画像</label> 
                        <div class="col-sm-8">
                            <input type="file" class="form-control-file" id="img_path" name="img_path" style="font-size: 1.2rem;"> 
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
