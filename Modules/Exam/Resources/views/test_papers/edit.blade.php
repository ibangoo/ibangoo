@extends('backstage.layouts.app')

@section('after_app_css')
    <style>
        .table td {
            border-top: none;
            border-bottom: 1px dotted #e3eaef;
        }
    </style>
@stop


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">测试管理</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">已提交试卷</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle = $testPaper->is_judged ? '查看详情' : '判卷' }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ $pageTitle }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" value="{{ $no = 1 }}">
                    @foreach(json_decode($test->options) as $option)
                        <div class="table-responsive-lg">
                            <table class="table table-centered mb-0">
                                <tbody>
                                <tr>
                                    <td style="border-bottom: none;">
                                        <h4>
                                            {{ \Modules\Exam\Entities\Question::$typeMap[$option->type] }}
                                            ({{ $option->num }}题，共{{ $option->num * $option->score }}分)
                                        </h4>
                                    </td>
                                </tr>
                                @foreach($content as $item)
                                    @if ($option->type === $item->type)
                                        <tr>
                                            <td>
                                                <p>
                                                    {{ $no++ }}
                                                    {!!  $item->is_right ? '<span class="text-success">【正确】</span>' : '<span class="text-danger">【错误】</span>' !!}
                                                    {{ $item->question->content }}
                                                </p>
                                                @if ($item->question->type !== \Modules\Exam\Entities\Question::TYPE_TEXTAREA)
                                                    @foreach(json_decode($item->question->options) as $question)
                                                        <p class="ml-2 @if ($item->right_answer === $question->code) text-info @endif">
                                                            {{ $question->code.'、'.$question->body }}
                                                        </p>
                                                    @endforeach
                                                @else
                                                    <p>{!! '<img src="'.Storage::url($item->question->content_image).'" style="max-width:500px">' !!}</p>
                                                    <h5>答案</h5>
                                                    <p>{{ $question->body }}</p>
                                                    <div>
                                                        <form action="{{ route('backstage.test-papers.change-score', $testPaper) }}" method="POST" class="form-inline">
                                                            <div class="form-group">
                                                                {{ csrf_field() }}
                                                                {{ method_field('PATCH') }}
                                                                <input type="text" class="form-control" name="actual_score" value="{{ $item->actual_score }}">
                                                                <input type="hidden" name="question_id" value="{{ $item->question->id }}">
                                                                <button class="ml-2 btn btn-info">修改分数</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form action="{{ route('backstage.test-papers.judged', $testPaper) }}" class="form-inline">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        @if (!$testPaper->is_judged)
                            <button class="btn btn-primary mr-2">完成判卷</button>
                        @endif
                        <a href="{{ route('backstage.test-papers.index') }}" class="btn btn-info">返回</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
