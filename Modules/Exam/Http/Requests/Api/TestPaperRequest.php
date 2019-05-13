<?php

namespace Modules\Exam\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class TestPaperRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getName()) {
            case 'api.test-paper.store':
                return [
                    'user_id' => ['required', 'integer'],
                    'test_id' => ['required', 'integer'],
                    'minutes' => ['required', 'integer'],
                    'actual_score' => ['required', 'integer'],
                    'answers' => ['required', 'string'],
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'user_id' => '所属用户',
            'test_id' => '所属测试',
            'minutes' => '测试时间',
            'total_score' => '测试总分',
            'actual_score' => '测试实际分数',
            'status' => '测试分数评级',
            'answers' => '用户答案',
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
