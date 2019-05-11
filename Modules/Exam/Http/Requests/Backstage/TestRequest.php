<?php

namespace Modules\Exam\Http\Requests\Backstage;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getName()) {
            case 'backstage.tests.store':
                return [
                    'name' => ['required', 'string'],
                    'total_score' => ['required', 'integer'],
                    'options' => ['required', 'string'],
                    'mode' => ['required', 'string'],
                    'tags' => ['nullable']
                ];
                break;
            case 'backstage.tests.update':
                return [
                    'name' => ['nullable', 'string'],
                    'total_score' => ['nullable', 'integer'],
                    'options' => ['nullable', 'string'],
                    'mode' => ['nullable', 'string'],
                    'tags' => ['nullable']
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'name' => '测试名称',
            'total_score' => '设置总分',
            'options' => '设置试题选项',
            'mode' => '出题方式',
            'tags' => '标签'
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
}