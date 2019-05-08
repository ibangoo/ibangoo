<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function index(Request $request)
    {
        return view('exam::tests.index');
    }
}
