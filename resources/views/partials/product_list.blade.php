@foreach ($products as $product)
<tr id="product-{{ $product->id }}">
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
    <td>{{ $product->company->company_name }}</td>
    <td>
        <a href="{{ route('productdetail', ['id' => $product->id]) }}" class="btn btn-info btn-sm">詳細</a>
        <form action="{{ route('productdestroy', $product->id) }}" method="POST" class="delete-form d-inline" data-id="{{ $product->id }}">
            @csrf
            @method('DELETE')
            <button type="btn" class="delete-btn btn btn-danger btn-sm" data-id="{{ $product->id }}">削除</button>
        </form>
    </td>
</tr>
@endforeach

<!-- ページネーションを含む部分 -->
<tr>
    <td colspan="7">
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </td>
</tr>