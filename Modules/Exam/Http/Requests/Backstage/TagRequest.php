<?php

namespace Modules\Exam\Http\Requests\Backstage;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->route()->getName() === 'backstage.tags.update') {
            return [
                'name' => 'required|string|unique:tags,name,'.$this->id,
                'status' => 'required|boolean',
            ];
        }

        return [
            'name' => 'required|string|unique:tags',
            'status' => 'required|boolean',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name' => '标签名称',
            'status' => '标签使用状态',
        ];
    }
}
