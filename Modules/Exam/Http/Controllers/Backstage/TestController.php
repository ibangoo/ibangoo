<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Exam\Entities\Question;
use Modules\Exam\Entities\Tag;
use Modules\Exam\Entities\Test;
use Modules\Exam\Http\Requests\Backstage\TestRequest;

class TestController extends Controller
{
    /**
     * 测试列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $tests = Test::query()
            ->with(['tags'])
            ->paginate(config('modules.paginator.per_page'));

        return view('exam::tests.index', compact('tests'));
    }

    /**
     * 测试创建页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::tests.create_and_edit', compact('tags'));
    }

    /**
     * 测试创建操作
     *
     * @param TestRequest $request
     *
     * @return mixed
     */
    public function store(TestRequest $request)
    {
        $params = get_request_params($request);

        // 判断是否存在主观题
        if ($options = json_decode($params['options'], true)) {
            foreach ($options as $option) {
                if ($option['type'] === 'textarea') {
                    $params['is_auto'] = false;
                    break;
                }
            }
            $params['is_auto'] = true;
        }

        try {
            DB::beginTransaction();
            // 创建试卷
            $test = Test::query()->create([
                'name' => $params['name'],
                'total_score' => $params['total_score'],
                'options' => $params['options'],
                'mode' => $params['mode'],
                'is_auto' => $params['is_auto'],
            ]);

            // 关联标签
            if (isset($params['tags']) && empty($params['tags'])) {
                $test->tags()->attach($params['tags']);
            }

        } catch (\Throwable $throwable) {
            DB::rollback();

            return $this->redirectBackWithErrors($throwable->getMessage());
        }

        DB::commit();

        return $this->redirectRouteWithSuccess('创建测试成功', 'backstage.tests.index');
    }

    /**
     * 测试修改状态
     *
     * @param Test $test
     *
     * @return mixed
     */
    public function changeStatus(Test $test)
    {
        $test->update(['status' => (int)!$test->status]);

        return $this->redirectBackWithSuccess('修改状态成功');
    }

    /**
     * 测试编辑页面
     *
     * @param Test $test
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Test $test)
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::tests.create_and_edit', compact('tags', 'test'));
    }

    /**
     * 测试更新操作
     *
     * @param Test        $test
     * @param TestRequest $request
     *
     * @return mixed
     */
    public function update(Test $test, TestRequest $request)
    {
        $params = get_request_params($request);

        // 判断是否存在主观题
        if ($options = json_decode($params['options'], true)) {
            foreach ($options as $option) {
                if ($option['type'] === Question::TYPE_TEXTAREA) {
                    $params['is_auto'] = false;
                    break;
                }
            }
        }

        try {
            DB::beginTransaction();

            // 修改测试
            $test->update(array_filter($params));

            // 关联标签
            $test->tags()->sync($params['tags'] ?? []);

        } catch (\Throwable $throwable) {
            DB::rollback();

            return $this->redirectBackWithErrors($throwable->getMessage());
        }

        DB::commit();

        return $this->redirectBackWithSuccess('修改测试成功');
    }

    /**
     * 测试关联试题
     *
     * @param Test    $test
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questions(Test $test, Request $request)
    {
        $tags = Tag::query()->where('status', true)->get();
        $questions = $test->questions()
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
            ->get();

        // 组合排序
        $questionables = $test->questionables;
        $questions->map(function ($item) use ($questionables) {
            foreach ($questionables as $questionable) {
                if ($questionable->question_id === $item->id) {
                    $item->sort = $questionable->sort;
                }
            }
        });

        return view('exam::tests.questions', compact('tags', 'test', 'questions'));
    }

    /**
     * 测试添加试题页面
     *
     * @param Test    $test
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchQuestions(Test $test, Request $request)
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

        return view('exam::tests.search_questions', compact('test', 'tags', 'questions'));
    }

    /**
     * 测试关联试题操作
     *
     * @param Test    $test
     * @param Request $request
     *
     * @return mixed
     */
    public function attachQuestions(Test $test, Request $request)
    {
        // 获取测试配置
        $options = json_decode($test->options, true);

        // 获取提交试题
        $questions = json_decode($request->questions, true);

        // 测试已关联的试题
        $relationQuestion = $test->questions->toArray();

        // 试题统计
        $totalQuestions = [];
        foreach ($questions as $question) {
            if (!isset($totalQuestions[$question['id']])) {
                $totalQuestions[$question['id']] = $question['type'];
            }
        }

        foreach ($relationQuestion as $question) {
            if (!isset($totalQuestions[$question['id']])) {
                $totalQuestions[$question['id']] = $question['type'];
            }
        }

        $totalType = [];
        foreach ($totalQuestions as $type) {
            if (!isset($totalType[$type])) {
                $totalType[$type] = 1;
            } else{
                $totalType[$type]++;
            }
        }

        // 配置对比
        foreach ($options as $option) {
            foreach ($totalType as $type => $num) {
                if ($option['type'] === $type) {
                    if ($num > $option['num']) {
                        return $this->redirectBackWithErrors(Question::$typeMap[$type].'限制关联 '.$option['num'].' 道题。');
                    }
                }
            }
        }

        $test->questions()->sync(array_keys($totalQuestions));

        return $this->redirectBackWithSuccess('添加试题成功');
    }

    /**
     * 测试删除试题操作
     *
     * @param Test    $test
     * @param Request $request
     *
     * @return mixed
     */
    public function detachQuestions(Test $test, Request $request)
    {
        $test->questions()->detach(is_string($request->ids) ? json_decode($request->ids, true) : $request->ids);

        return $this->redirectBackWithSuccess('删除试题成功');
    }

    /**
     * 测试试题排序
     *
     * @param Test    $test
     * @param Request $request
     *
     * @return mixed
     */
    public function sortQuestions(Test $test, Request $request)
    {
        $data = [];
        $sort = json_decode($request->sort, true);
        foreach ($sort as $item) {
            $data[$item['question_id']] = [
                'sort' => $item['sort'],
            ];
        }
        $test->questions()->sync($data);

        return $this->redirectBackWithSuccess('排序成功');
    }

    /**
     * 测试试题拖拽
     *
     * @param Test $test
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dragQuestions(Test $test)
    {
        $questions = $test->questions;

        // 组合排序
        $questionables = $test->questionables;
        $questions->map(function ($item) use ($questionables) {
            foreach ($questionables as $questionable) {
                if ($questionable->question_id === $item->id) {
                    $item->sort = $questionable->sort;
                }
            }
        });

        return view('exam::tests.drag_questions', compact('test', 'questions'));
    }
}
