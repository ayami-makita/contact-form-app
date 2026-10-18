<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexContactRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true; // 管理画面のミドルウェアや認証でガードするため true
    }


    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:0,1,2,3'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'date' => ['nullable', 'date'],
        ];
    }
}

