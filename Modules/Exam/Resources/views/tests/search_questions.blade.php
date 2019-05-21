@extends('backstage.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">题库管理</a></li>
                        <li class="breadcrumb-item active">题库列表</li>
                    </ol>
                </div>
                <h4 class="page-title">题库列表</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding-bottom: 0;">
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <form id="search-form" class="form-inline" action="{{ route('backstage.tests.search-questions', $test) }}">
                                {{-- 试题类型 --}}
                                <div class="form-group mr-3 mb-2">
                                    <label for="type-select" class="mr-2">试题类型</label>
                                    <select class="custom-select select2" id="type-select" name="type">
                                        <option value="0">请选择...</option>
                                        @foreach(\Modules\Exam\Entities\Question::$typeMap as $key => $type)
                                            <option value="{{ $key }}" @if($key === request('type')) selected @endif>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- 关键字查询 --}}
                                <div class="form-group mr-3 mb-2">
                                    <label for="content" class="mr-2">关键字查询</label>
                                    <input type="search" class="form-control" id="content" name="content" value="{{ request('content') }}">
                                </div>
                                {{-- 标签查询 --}}
                                <div class="form-group mr-2 mb-2">
                                    <label for="tags-select">标签查询</label>
                                </div>
                                <div class="form-group mr-3 mb-2" style="min-width: 150px;">
                                    <select class="custom-select select2 select2-multiple" id="tags-select" name="tags[]" data-toggle="select2" multiple="multiple">
                                        <option value="0">请选择...</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" @if(in_array($tag->id, request('tags', []))) selected @endif>{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- 出题时间 --}}
                                <div class="form-group mr-3 mb-2">
                                    <label for="created_at_start" class="mr-2">出题时间</label>
                                    <input id="created_at_start" type="text"
                                           class="form-control mr-2 created_at"
                                           name="created_at[]"
                                           data-single-date-picker="true"
                                           data-date-format="yyyy-mm-dd"
                                           value="{{ request('created_at') ? request('created_at')[0] : '' }}"
                                    >
                                    <label for="created_at_start_end" class="mr-2">-</label>
                                    <input id="created_at_start_end" type="text"
                                           class="form-control mr-2 created_at"
                                           name="created_at[]"
                                           data-single-date-picker="true"
                                           data-date-format="yyyy-mm-dd"
                                           value="{{ request('created_at') ? request('created_at')[1] : '' }}"
                                    >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="display: flex; justify-content: left">
                    <button id="search-button" type="button" class="btn btn-rounded btn-xs btn-success" style="min-width: 200px">
                        搜索
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="jq-toast-wrap top-right" style="top: 30%; width: 150px">
        <div class="jq-toast-single bg-warning" style="text-align: left;">
            <h2 class="jq-toast-heading">当前所选试题</h2>
            <h2><span style="font-size: 24px">@{{ total }}</span> 道</h2>
        </div>
        <div class="jq-toast-single bg-info" style="text-align: left;">
            <h2 class="jq-toast-heading mb-0 text-md-center" id="submit-button">提交</h2>
        </div>
    </div>

    @if($questions->isNotEmpty())
        <div class="row mb-2">
            <div class="col-10">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input d-inline" id="select-all-checkbox">
                    <label class="custom-control-label mr-3" for="select-all-checkbox">全选</label>
                </div>
            </div>
            <div class="col-2" style="text-align: right">
                @if($questions->isNotEmpty())
                    共 {{ $questions->total() }} 条试题
                @endif
            </div>
        </div>

        <div class="row">
            @foreach($questions as $question)
                <div class="col-md-6">
                    <div class="card d-block">
                        <div class="card-header">
                            <h5 class="card-title">
                            <span class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input question-checkbox" 
                                       id="customCheck{{ $question->id }}" 
                                       value="{{ $question->id }}"
                                       data-type="{{ $question->type }}"
                                       @if (in_array($question->id, $test->questions->pluck('id')->toArray(), true))
                                           checked
                                       @endif
                                >
                                <label class="custom-control-label" for="customCheck{{ $question->id }}" style="line-height: 20px">
                                    {{ $question->id }}、
                                    <span class="badge badge-{{ get_type_name_color($question->type) }}">{{ $question->type_name }}</span>
                                    &nbsp;
                                    {{ $question->content }}
                                </label>
                            </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @switch($question->type)
                                @case(\Modules\Exam\Entities\Question::TYPE_RADIO)
                                @case(\Modules\Exam\Entities\Question::TYPE_CHECKBOX)
                                @foreach(json_decode($question->options) as $option)
                                    {{ $option->body }} <br>
                                @endforeach
                                @break;
                                @case(\Modules\Exam\Entities\Question::TYPE_BOOLEAN)
                                {{ $question->answer }}
                                @break;
                                @case(\Modules\Exam\Entities\Question::TYPE_INPUT)
                                @foreach(json_decode($question->options) as $option)
                                    {{ $option->body }} <br>
                                @endforeach
                                @break;
                                @case(\Modules\Exam\Entities\Question::TYPE_TEXTAREA)
                                {{ $question->options }}
                                @break
                            @endswitch
                        </div>
                        <div class="card-footer" style=" display: flex; justify-content: flex-end;align-items: center;">
                            <span class="mr-2">标签：{{ $question->tags_to_string }}</span>
                            <a href="{{ route('backstage.questions.edit', ['question' => $question, 'type' => $question->type]) }}" class="card-link text-custom btn btn-primary mr-2">编辑</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        @include('backstage.templates.empty')
    @endif

    @if(isset($questions) && $questions->isNotEmpty() && $questions->total() > config('modules.paginator.per_page'))
        <div class="row">
            <div class="col-md-12" style="display: flex; justify-content: center">
                {{ $questions->appends(request()->all())->links() }}
            </div>
        </div>
    @endif

    <form id="submit-form" method="POST" action="{{ route('backstage.tests.attach-questions', $test) }}" style="display: none;">
        {{ csrf_field() }}
        <input type="hidden" id="questions" name="questions">
    </form>
@stop


@section('after_app_js')
    <script src="{{ asset('js/vendor/vue.js') }}"></script>
    <script>
        let app = new Vue({
            el: '#app',
            data: {
                total: parseInt('{{ $test->questions->count() ?? 0 }}')
            }
        });

        $(function () {
            $('.created_at').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: "YYYY-MM-DD",
                }
            }, function (start) {
                this.element.val(start.format('YYYY-MM-DD'));
            });

            $('#select-all-checkbox').click(function () {
                if (this.checked) {
                    $('.question-checkbox').attr('checked', true);
                } else {
                    $('.question-checkbox').attr('checked', false);
                }
            });

            $('.question-checkbox').click(function () {
                if ($(this).is(':checked')){
                    app.total++;
                    localStorage.setItem('total', app.total);
                } else {
                    app.total--;
                    localStorage.setItem('total', app.total);
                }
            });

            localStorage.setItem('questions', JSON.stringify({!! $test->questions !!}));

            let questions = [];
            if (localStorage.getItem('questions')){
                questions = JSON.parse(localStorage.getItem('questions'));
            }

            $('#submit-button').click(function(){
                $('.question-checkbox:checked').each(function () {
                    questions.push({
                        id: $(this).val(),
                        type: $(this).data('type')
                    });
                });

                if (!questions) {
                    alertError('请选择题库试题后再提交');
                }

                $('#questions').val(JSON.stringify(questions));
                localStorage.setItem('questions', JSON.stringify(questions));
                globalLoading();
                $('#submit-form').submit();
            });
        });

        $('#search-button').click(function () {
            globalLoading();
            $('#search-form').submit();
        });

        $('#batch-delete').click(function () {
            if ($('.question-checkbox:checked').length <= 0) {
                alertError('请选择需要删除的试题');
                return;
            }

            let ids = [];
            $('.question-checkbox:checked').each(function () {
                ids.push($(this).attr('value'));
            });
            $('input[name="ids"]').attr('value', JSON.stringify(ids));

            globalLoading();
            $('#batch-delete-form').submit();
        });
    </script>
@stop
