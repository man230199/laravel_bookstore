<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    private $table            = 'product';

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
        $form = $this->form;
        if ($form == 'product') {
            $id = $this->id;

            $condName = "bail|required|between:5,100|unique:$this->table,name";
            $condThumb = 'bail|required|image|max:10000';

            if (!empty($id)) {
                $condName .= ",$id";
                $condThumb = 'bail|image|max:10000';
            }

            return [
                'name'        => $condName,
                'short_description'     => 'bail|required|min:5|max:500',
                'description'     => 'bail|required|min:5|max:1000',
                'status'      => 'bail|in:active,inactive',
                'thumb'       => $condThumb
            ];
        }
        if ($form == 'product_attribute') { 
            return [];
        }
        if ($form == 'product_image') { 
            return [];
        }
    }

    public function messages()
    {
        return [
            // 'name.required' => 'Name không được rỗng',
            // 'name.min'      => 'Name :input chiều dài phải có ít nhất :min ký tứ',
        ];
    }
    public function attributes()
    {
        return [
            // 'description' => 'Field Description: ',
        ];
    }
}
