<?php

namespace Modules\Exam\Http\Requests\Backstage;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required'],
            'tags' => ['required'],
            'content' => ['required', 'string'],
            'content_image' => ['required', 'image'],
            'options' => ['nullable', 'string'],
            'explain' => ['nullable'],
            'explain_image' => ['nullable', 'image'],
        ];
    }

    public function attributes()
    {
        return [
            'type' => '题型',
            'tags' => '标签',
            'content' => '题干',
            'content_image' => '题干插图',
            'options' => '选项',
            'explain' => '试题解析',
            'explain_image' => '解析插图',
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
