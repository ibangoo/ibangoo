<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Exam\Entities\Tag;
use Modules\Exam\Entities\Test;
use Modules\Exam\Http\Requests\Backstage\TestRequest;

class TestController extends Controller
{
    public function index(Request $request)
    {
        return view('exam::tests.index');
    }

    public function create()
    {
        $tags = Tag::query()->where('status', true)->get();

        return view('exam::tests.create_and_edit', compact('tags'));
    }

    public function store(TestRequest $request)
    {
        $params = get_request_params($request);
        
        try {
            DB::beginTransaction();
            // 创建试卷
            $test = Test::query()->create([
                'name' => $params['name'],
                'total_score' => $params['total_score'],
                'options' => $params['options'],
                'mode' => $params['mode'],
            ]);

            // 关联标签
            $test->tags()->attach($params['tags']);

        } catch (\Throwable $throwable) {
            DB::rollback();

            return $this->redirectBackWithErrors($throwable->getMessage());
        }

        DB::commit();

        return $this->redirectRouteWithSuccess('创建测试成功', 'backstage.tests.index');
    }
}
