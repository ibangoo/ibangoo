<?php

namespace Modules\Exam\Http\Requests\Backstage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;

class QuestionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        switch ($this->route()->getName()) {
            case 'backstage.questions.store':
                $rules = [
                    'type' => ['required'],
                    'tags' => ['required'],
                    'content' => ['required', 'string'],
                    'content_image' => ['nullable', 'image'],
                    'options' => ['nullable', 'string'],
                    'explain' => ['nullable'],
                    'explain_image' => ['nullable', 'image'],
                ];
                break;
            case  'backstage.questions.update':
                $rules = [
                    'type' => ['nullable'],
                    'tags' => ['nullable'],
                    'content' => ['nullable', 'string'],
                    'content_image' => ['nullable', 'image'],
                    'options' => ['nullable', 'string'],
                    'explain' => ['nullable'],
                    'explain_image' => ['nullable', 'image'],
                ];
                break;
        }

        return $rules;
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
