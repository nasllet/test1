import './bootstrap';
@section('scripts')
<!-- jQuerry読み込み-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Ajax-->
<script>
    $(document).ready(function() {
    function fetchProducts() {
        let keyword = $('#searchKeyword').val();
        let company_id = $('#searchCompany').val();

        console.log("検索実行: keyword=", keyword, " company_id=", company_id); // デバッグ用

        let data = { keyword: keyword };
        if (company_id){
            data.company_id = company_id;
        }

        $.ajax({
            url: "{{ route('productsearch')}}",
            method: "GET",
            data: data,
            success: function(response) {
                console.log("検索結果取得成功: ", response); // デバッグ用
                $('#product').html($(response).find('#product').html()); 
                error: function(xhr, status, error) {
            console.error("検索エラー: ", error); // エラーログを表示
        }
            }
        });
    }
    //検索ワード変更時
    $('#searchKeyword').on('keyup',fetchProducts);
    //メーカー名選択時
    $('#searchCompany').on('change',fetchProducts);
    });
    </script>
@endsection