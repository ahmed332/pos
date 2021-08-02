<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class productRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {

        return [


            'ar.name'=>'required|unique:product_translations,name',
            'en.name'=>'required|unique:product_translations,name',
            'ar.description'=>'required|unique:product_translations,description',
            'en.description'=>'required|unique:product_translations,description',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',

         ];


    }

    public function messages()
    {
        return [
           'ar.name.required'=>trans('site.ar-name-required'),
           'en.name.required'=>trans('site.En.name.required'),
           'ar.description.required'=>trans('site.ar-description-required'),
           'En-description.required'=>trans('site.En-description-required'),
           'purchase_price_required'=>trans('site.purchase_price_required')
        ];
    }
}
