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
        $tags = Tag::query()
            ->where('status', true)
            ->get();

        $questions = Question::query()
            ->with(['tags'])
            ->when($request->type, function ($query) use ($request) {
                return $query->where('type', $request->type);
            })
            ->when($request->get('content'), function ($query) use ($request) {
                return $query->where('content', 'like', '%'.$request->get('content').'%');
            })
            ->when($request->get('created_at'), function ($query) use ($request) {
                if ($request->created_at[0] && $request->created_at[1]) {
                    return $query->whereBetween('created_at', $request->created_at);
                }
            })
            ->when($request->get('tags'), function ($query) use ($request) {
                return $query->whereHas('tags', function ($subQuery) use ($request) {
                    return $subQuery->whereIn('id', $request->tags);
                });
            })
            ->latest()
            ->paginate(config('modules.paginator.per_page'));

        return view('exam::questions.index', compact('questions', 'tags'));
    }

    public function create(Request $request)
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::questions.'.$request->type.'_create_and_edit', compact('tags'));
    }

    public function store(QuestionRequest $request)
    {
        try {
            $params = get_request_params($request);
            if (isset($params['content_image']) && !empty($params['content_image'])) {
                $params['content_image'] = $request->file('content_image')->store('public/uploads');
            }
            if (isset($params['explain_image']) && !empty($params['explain_image'])) {
                $params['explain_image'] = $request->file('explain_image')->store('public/uploads');
            }

            // 获取正确答案
            foreach (json_decode($params['options']) as $option) {
                if (in_array($params['type'], [Question::TYPE_RADIO, Question::TYPE_CHECKBOX, Question::TYPE_INPUT], true)) {
                    if ((boolean)$option->is_right) {
                        $params['answer'] = $option->code;
                    }
                }

                if ($params['type'] === Question::TYPE_INPUT) {
                    $params['answer'] .= $option->code;
                }
            }

            // 创建试题
            DB::beginTransaction();
            $question = Question::query()->create([
                'type' => $params['type'],
                'content' => $params['content'],
                'content_image' => $params['content_image'],
                'options' => $params['options'],
                'explain' => $params['explain'] ?? null,
                'explain_image' => $params['explain_image'] ?? null,
                'answer' => $params['answer'] ?? null,
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

    public function edit(Question $question, Request $request)
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::questions.'.$request->type.'_create_and_edit', compact('tags', 'question'));
    }

    public function update(Question $question, QuestionRequest $request)
    {
        DB::beginTransaction();
        try {
            $params = get_request_params($request);
            if (isset($params['content_image'])) {
                $params['content_image'] = $request->file('content_image')->store('public/uploads');
            }
            if (isset($params['explain_image'])) {
                $params['explain_image'] = $request->file('explain_image')->store('public/uploads');
            }

            // 获取正确答案
            foreach (json_decode($params['options']) as $option) {
                if (in_array($params['type'], [Question::TYPE_RADIO, Question::TYPE_CHECKBOX, Question::TYPE_INPUT], true)) {
                    if ((boolean)$option->is_right) {
                        $params['answer'] = $option->code;
                    }
                }

                if ($params['type'] === Question::TYPE_INPUT) {
                    $params['answer'] .= $option->code;
                }
            }

            // 修改试题
            $question->update(array_filter($params));

            // 关联标签
            $question->tags()->sync($params['tags'] ?? []);

        } catch (\Throwable $throwable) {
            DB::rollback();

            return $this->redirectBackWithErrors($throwable->getMessage());
        }

        DB::commit();

        return $this->redirectBackWithSuccess('编辑试题成功');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return $this->redirectBackWithSuccess('删除试题成功');
    }

    public function batchDestroy(Request $request)
    {
        Question::query()->whereIn('id', json_decode($request->ids, true))->delete();

        return $this->redirectBackWithSuccess('批量删除试题成功');
    }
}
