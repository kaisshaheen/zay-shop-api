<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required | string | max:256" , 
            "brand" => "required | string", 
            "image_path" => "nullable | image |mimes:png,jpg,jpeg | max:2048",
            "description" => "required | string" , 
            "gender" => "required | string",
            "color" => "required | string", 
            "size" => "required | string",  
            "price" => "required | numeric",
            "stock" => "required | numeric",
            "category_id" => "required | numeric"
        ];
        
        if ($this->isMethod('POST')) { 
            $rules['image_path'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) { 
            $rules['image_path'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }
    }
}
