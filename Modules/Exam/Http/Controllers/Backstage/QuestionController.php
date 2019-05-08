<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Exam\Entities\Question;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        return view('exam::questions.index');
    }

    public function create(Request $request)
    {
        $tags =[];
        return view('exam::questions.create_and_edit', compact('tags'));
    }
}
