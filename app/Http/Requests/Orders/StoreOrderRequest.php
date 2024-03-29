<?php

namespace App\Http\Requests\Orders;

use App\Rules\ValidShippingMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'address_id' => [
                'required',
                Rule::exists('addresses', 'id')->where(function ($builder) {
                    $builder->where('user_id', $this->user()->id);
                })
            ],
            'shipping_method_id' => [
                'required',
                'exists:shipping_methods,id',
                new ValidShippingMethod($this->address_id)
            ]
        ];
    }
}
