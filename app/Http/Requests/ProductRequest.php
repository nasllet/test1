<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable',
        ];
    }
    public function attributes()
{
    return [
            'product_name' => '商品名',
            'company_id' => '会社名',
            'price' => '価格',
            'stock' => '在庫数',
            'comment' => 'コメント',
    ];
}
/**
 * エラーメッセージ
 *
 * @return array
 */
public function messages() {
    return [
        'product_name.required' => '商品名を入力してください。',
            'company_id.required' => 'メーカー名を選択してください。',
            'price.required' => '価格を入力してください。',
            'price.numeric' => '価格は数値で入力してください。',
            'stock.required' => '在庫数を入力してください。',
            'stock.integer' => '在庫数は整数で入力してください。',
    ];
}
}
