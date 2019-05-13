<?php

namespace Modules\Exam\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Exam\Transformers\TestsTransformer;
use Modules\Exam\Entities\Test;

/**
 * 测试接口
 *
 * Class TestController
 *
 * @package Modules\Exam\Http\Controllers\Api
 */
class TestController extends Controller
{
    /**
     * 测试列表
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $tests = Test::query()
            ->where('status', true)
            ->paginate(config('modules.paginator.per_page'));

        return $this->responsePaginator($tests, new TestsTransformer);
    }

    /**
     * 测试详情
     *
     * @param $testId
     *
     * @return mixed
     */
    public function questions($testId)
    {
        $test = Test::with(['questions'])->find($testId);

        return $this->responseArray($test);
    }
}
