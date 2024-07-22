<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
{

    public function authorize():bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => ['required' ,'string','max:255'],
            'name' => ['required' ,'string','max:255'],
            'price' => ['required' ,'numeric','min:1'],
            'quantity' => ['required' ,'integer','min:0'],
            'type' => ['required' ,'in:physical,downloadable'],
            'weight' => ['required_if:type,physical','numeric','min:1'],
            'size' => ['required_if:type,downloadable','numeric','min:1'],
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status'    => false,
            'message'  => 'Validation errors',
            'data'     => $validator->errors()
        ]));
    }
}
