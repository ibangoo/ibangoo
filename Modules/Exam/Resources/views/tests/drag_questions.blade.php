@extends('backstage.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">测试管理</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">测试列表</a></li>
                        <li class="breadcrumb-item active">管理试题</li>
                    </ol>
                </div>
                <h4 class="page-title">管理试题</h4>
            </div>
        </div>
    </div>

    @if($questions->isNotEmpty())
        <div class="row">
            <div class="col-md-6" id="dragula-left">
                <h2>排序区</h2>
                @foreach($questions->sortBy('sort') as $question)
                    <div class="card d-block mb-1 sort-item" data-id="{{ $question->id }}">
                        <div class="card-header">
                            <h5 class="card-title mt-1 mb-1">
                                <span class="badge badge-{{ get_type_name_color($question->type) }}">{{ $question->type_name }}</span>
                                {{ $question->content }}
                            </h5>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-6" id="dragula-right">
                <h2>缓冲区</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form id="sort-form" action="{{ route('backstage.tests.sort-questions',$test) }}" class="form-inline" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="sort">
                    <button id="sort-button" type="submit" class="btn btn-primary mt-2 mr-2">排序</button>
                    <a href="{{ route('backstage.tests.questions', $test) }}" class="btn btn-info mt-2">返回</a>
                </form>
            </div>
        </div>
        <input type="hidden" id="sort_count" value="{{ $questions->count() }}">
    @else
        @include('backstage.templates.empty')
    @endif
@stop


@section('after_app_js')
    <script src="{{ asset('js/vendor/dragula.min.js') }}"></script>
    <script src="{{ asset('js/vendor/component.dragula.js') }}"></script>
    <script>
        $(function () {
            dragula([document.getElementById('dragula-left'), document.getElementById('dragula-right')]);

            $('#sort-button').click(function (event) {
                event.stopPropagation();

                let sortItemsCount = $('.sort-item').length;
                let sortCollectionCount = $('#sort_count').val();
                if (parseInt(sortCollectionCount) !== parseInt(sortItemsCount)) {
                    alertError('请将所有试题移动至排序区');
                    return false;
                }

                let sort = [];
                $('.sort-item').each(function (key) {
                    sort.push({
                        question_id: $(this).data('id'),
                        sort: key + 1
                    })
                });
                $('input[name="sort"]').val(JSON.stringify(sort));
                globalLoading();
            });
        });
    </script>
@stop
