<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Exam\Entities\TestPaper;

class TestPaperController extends Controller
{
    public function index(Request $request)
    {
        $testPapers = TestPaper::query()
            ->when($request->user_name, function ($query) use ($request) {
                return $query->where('user_name', 'like', '%'.$request->user_name.'%');
            })
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->created_at, function ($query) use ($request) {
                if ($request->create_at[0] && $request->created_at[1]) {
                    return $query->whereBetween('created_at', $request->created_at);
                }
            })
            ->paginate(config('modules.paginator.per_page'));

        return view('exam::test_papers.index', compact('testPapers'));
    }

    public function edit(TestPaper $testPaper)
    {
        $test = $testPaper->test;
        $content = json_decode($testPaper->content);

        return view('exam::test_papers.edit', compact('testPaper', 'test', 'content'));
    }

    public function changeScore(TestPaper $testPaper, Request $request)
    {
        $content = json_decode($testPaper->content, true);
        if ($request->actual_score > $content[$request->question_id]['total_score']) {
            return $this->redirectBackWithErrors('得分不能高于总分');
        }

        // 计算分数
        $content[$request->question_id]['actual_score'] = $request->actual_score;

        // 重新计算总分
        $actualScore = 0;
        foreach ($content as $item) {
            $actualScore += $item['actual_score'];
        }
        $content = json_encode($content);

        DB::transaction(function () use ($testPaper, $content, $actualScore) {
            $testPaper->update(compact('content'));
            $testPaper->test()->update(['actual_score' => $actualScore]);
        });

        return $this->redirectBackWithSuccess('修改分数成功');
    }

    public function judged(TestPaper $testPaper)
    {
        $testPaper->update(['is_judged' => true]);

        return $this->redirectBackWithSuccess('公布试卷成绩成功');
    }
}
