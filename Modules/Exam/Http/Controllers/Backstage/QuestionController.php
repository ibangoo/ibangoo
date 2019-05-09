<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Exam\Entities\Tag;
use Modules\Exam\Entities\Question;
use Modules\Exam\Http\Requests\Backstage\QuestionRequest;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        return view('exam::questions.index');
    }

    public function create(Request $request)
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::questions.'.$request->type.'_create_and_edit', compact('tags'));
    }

    public function store(QuestionRequest $request)
    {
        DB::beginTransaction();
        try {
            $params = get_request_params($request);

            if ($params['content_image']) {
                $path = $request->file('content_image')->store('images');
                $params['content_image'] = $path;
            }
            if ($params['explain_image']) {
                $path = $request->file('explain_image')->store('images');
                $params['explain_image'] = $path;
            }

            // 创建试题
            $question = Question::query()->create([
                'type' => $params['type'],
                'content' => $params['content'],
                'content_image' => $params['content_image'],
                'options' => $params['options'],
                'explain' => $params['explain'],
                'explain_image' => $params['explain_image'],
            ]);

            // 关联标签
            $question->tags()->attach($params['tags']);

        } catch (\Throwable $throwable) {
            DB::rollback();

            return $this->redirectBackWithErrors($throwable->getMessage());
        }

        DB::commit();

        return $this->redirectRouteWithSuccess('创建试题成功', 'backstage.questions.index');
    }
}
