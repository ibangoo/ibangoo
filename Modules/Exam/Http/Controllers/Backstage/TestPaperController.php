<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
                return $query->whereBetween('created_at', $request->created_at);
            })
            ->paginate(config('modules.paginator.per_page'));

        return view('exam::test_papers.index', compact('testPapers'));
    }
}
