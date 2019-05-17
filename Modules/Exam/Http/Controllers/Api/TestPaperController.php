<?php

namespace Modules\Exam\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Exam\Entities\Question;
use Modules\Exam\Entities\Test;
use Modules\Exam\Entities\TestPaper;
use Modules\Exam\Http\Requests\Api\TestPaperRequest;

class TestPaperController extends Controller
{
    public function store(TestPaperRequest $request)
    {
        // 获取测试试题
        $test = Test::query()->find($request->test_id);
        if (!$test) {
            return $this->responseInvalidArgument('测试不存在');
        }

        $typeScore = array_column(json_decode($test->options, true), 'score', 'type');
        $answers = json_decode($request->answers);
        $questions = $test->questions;
        $content = [];

        // 对比用户答题
        $actualScore = 0;
        foreach ($questions as $question) {
            foreach ($answers as $answer) {
                if ($answer->question_id === $question->id) {
                    $content[$answer->question_id] = [
                        'type' => $question->type,
                        'question' => $question,
                        'is_right' => $question->answer === $answer->question_answer,
                        'user_answer' => $answer->question_answer,
                        'right_answer' => $question->answer,
                        'total_score' => $typeScore[$question->type],
                    ];

                    if ($answer->question_answer === $question->answer) {
                        $actualScore += $typeScore[$question->type];
                        $content[$answer->question_id]['actual_score'] = $typeScore[$question->type];
                    } else {
                        $content[$answer->question_id]['actual_score'] = 0;
                    }
                }
            }
        }

        $params = get_request_params($request);
        $params['is_judged'] = in_array(Question::TYPE_TEXTAREA, array_keys($typeScore)) ? false : true;
        $params['total_score'] = $test->total_score;
        $params['actual_score'] = $actualScore;
        if ($content) {
            $params['content'] = json_encode($content, true);
        }

        $testPaper = TestPaper::query()->create($params);

        return $this->responseArray($testPaper);
    }
}
