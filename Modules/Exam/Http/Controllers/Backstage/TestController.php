<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Exam\Entities\Question;
use Modules\Exam\Entities\Tag;
use Modules\Exam\Entities\Test;
use Modules\Exam\Http\Requests\Backstage\TestRequest;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $tests = Test::query()
            ->with(['tags'])
            ->paginate(config('modules.paginator.per_page'));

        return view('exam::tests.index', compact('tests'));
    }

    public function create()
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::tests.create_and_edit', compact('tags'));
    }

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

    public function changeStatus(Test $test)
    {
        $test->update(['status' => (int)!$test->status]);

        return $this->redirectBackWithSuccess('修改状态成功');
    }

    public function edit(Test $test)
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::tests.create_and_edit', compact('tags', 'test'));
    }

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

        return view('exam::tests.questions', compact('tags', 'test', 'questions'));
    }
}
