<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Exam\Entities\Tag;
use Modules\Exam\Entities\Question;
use Modules\Exam\Http\Requests\Backstage\QuestionRequest;
use Modules\Exam\Imports\QuestionsImport;

/**
 * Class QuestionController - 试题管理控制器
 *
 * @package Modules\Exam\Http\Controllers\Backstage
 */
class QuestionController extends Controller
{
    /**
     * 试题管理列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
            ->latest('id')
            ->paginate(config('modules.paginator.per_page'));

        return view('exam::questions.index', compact('questions', 'tags'));
    }

    /**
     * 试题创建页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::questions.'.$request->type.'_create_and_edit', compact('tags'));
    }

    /**
     * 试题创建操作
     *
     * @param QuestionRequest $request
     *
     * @return mixed
     */
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
            if ($params['type'] !== Question::TYPE_TEXTAREA) {
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
            }


            // 创建试题
            DB::beginTransaction();
            $question = Question::query()->create([
                'type' => $params['type'],
                'content' => $params['content'],
                'options' => $params['options'],
                'content_image' => $params['content_image'] ?? null,
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

    /**
     * 试题编辑页面
     *
     * @param Question $question
     * @param Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Question $question, Request $request)
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::questions.'.$request->type.'_create_and_edit', compact('tags', 'question'));
    }

    /**
     * 试题编辑操作
     *
     * @param Question        $question
     * @param QuestionRequest $request
     *
     * @return mixed
     * @throws \Exception
     */
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

    /**
     * 单个试题删除
     *
     * @param Question $question
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return $this->redirectBackWithSuccess('删除试题成功');
    }

    /**
     * 试题批量删除
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function batchDestroy(Request $request)
    {
        Question::query()->whereIn('id', json_decode($request->ids, true))->delete();

        return $this->redirectBackWithSuccess('批量删除试题成功');
    }

    /**
     * 导入 Excel
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @throws \Throwable
     */
    public function import(Request $request)
    {
        if ($request->isMethod('post')) {
            $items = Excel::toArray(new QuestionsImport, $request->file('excel'))[0];
            array_shift($items);

            // 汇总去重插入标签
            $tags = array_column($items, 1);
            $tmpTag = [];
            foreach ($tags as $tag) {
                foreach (explode('、', $tag) as $value) {
                    $tmpTag[] = $value;
                }
            }
            $tagNames = array_unique($tmpTag);

            // 关联标签键值
            $allTags = [];
            $tagsCollection = Tag::all();
            foreach ($tagNames as $name) {
                $tag = $tagsCollection->where('name', $name)->first();
                if ($tag) {
                    $allTags[$tag->name] = $tag->id;
                } else {
                    $allTags[$name] = (Tag::query()->create(['name' => $name, 'status' => true]))->id;
                }
            }

            $relations = [];
            foreach ($tags as $key => $tag) {
                foreach (explode('、', $tag) as $value) {
                    $relations[$key][] = $allTags[$value];
                }
            }

            $typesCount = [
                Question::TYPE_RADIO => 0,
                Question::TYPE_CHECKBOX => 0,
                Question::TYPE_BOOLEAN => 0,
                Question::TYPE_INPUT => 0,
                Question::TYPE_TEXTAREA => 0,
            ];
            DB::transaction(function () use ($items, $relations, &$typesCount) {
                $typeMap = array_flip(Question::$typeMap);
                foreach ($items as $key => $item) {
                    if (!isset($typeMap[$item[0]])) {
                        return $this->redirectBackWithErrors('Excel 表'.(++$key).'行题型出错');
                    }

                    $options = null;
                    switch ($typeMap[$item[0]]) {
                        case Question::TYPE_RADIO:
                            foreach (explode('、', $item[5]) as $option) {
                                $code = mb_substr($option, 0, 1);
                                $options[] = [
                                    'is_right' => $code === $item[3],
                                    'body' => substr($option, 2),
                                    'code' => $code,
                                ];
                            }
                            $options = json_encode($options);
                            $typesCount[Question::TYPE_RADIO]++;
                            break;
                        case Question::TYPE_CHECKBOX:
                            foreach (explode('、', $item[5]) as $option) {
                                $code = mb_substr($option, 0, 1);
                                $options[] = [
                                    'is_right' => in_array($code, explode('、', $item[3])),
                                    'body' => substr($option, 2),
                                    'code' => $code,
                                ];
                            }
                            $options = json_encode($options);
                            $typesCount[Question::TYPE_CHECKBOX]++;
                            break;
                        case Question::TYPE_INPUT:
                            foreach (explode('、', $item[3]) as $option) {
                                $options[] = [
                                    'body' => $option,
                                ];
                            }
                            $options = json_encode($options);
                            $typesCount[Question::TYPE_INPUT]++;
                            break;
                        case Question::TYPE_BOOLEAN:
                            foreach (['正确', '错误'] as $index => $body) {
                                $options[] = [
                                    'is_right' => $body === $item[3],
                                    'body' => $body,
                                    'code' => (['A', 'B'])[$index],
                                ];
                            }
                            $options = json_encode($options);
                            $typesCount[Question::TYPE_BOOLEAN]++;
                            break;
                        case Question::TYPE_TEXTAREA:
                            $options = $item[5];
                            $typesCount[Question::TYPE_TEXTAREA];
                            break;
                    }

                    $question = Question::query()->create([
                        'type' => $typeMap[$item[0]],
                        'content' => $item[2],
                        'options' => $options,
                        'explain' => $item[4],
                        'answer' => $item[3],
                    ]);
                    $question->tags()->attach($relations[$key]);
                }
            });

            return $this->redirectRouteWithSuccess(
                '导入 Excel 成功',
                'backstage.questions.import.view',
                ['type_counts' => $typesCount]
            );
        }

        return view('exam::questions.import');
    }

    public function downExcelTemplate()
    {

    }
}
