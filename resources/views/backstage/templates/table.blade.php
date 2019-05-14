@extends('backstage.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">测试管理</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">测试试卷</a></li>
                        <li class="breadcrumb-item active">已提交试卷</li>
                    </ol>
                </div>
                <h4 class="page-title">已提交试卷</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-block">
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <form id="search-form" class="form-inline" action="{{ route('backstage.test-papers.index') }}">
                                <div class="form-group mr-2">
                                    <label for="user_name" class="sr-only">搜索提交人姓名</label>
                                    <input type="search" class="form-control mr-2" id="user_name" name="user_name" value="{{ request('user_name') }}" placeholder="搜索提交人姓名">
                                </div>
                                <div class="form-group mr-2">
                                    <label for="status" class="mr-2">分数筛选</label>
                                    <select id="status" class="custom-select mr-2" name="status">
                                        <option disabled value="" selected>请选择</option>
                                        @foreach(\Modules\Exam\Entities\TestPaper::$statusMap as $value => $title)
                                            <option
                                                    value="{{ $value }}"
                                                    @if ($value === request('status')) selected @endif
                                            >{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="created_at_start" class="mr-2">提交时间</label>
                                    <input type="text" id="created_at_start" class="form-control created_at" name="created_at[]" value="{{ request('created_at') ? request('created_at')[0] : '' }}">
                                </div>
                                <div class="form-group mr-2">
                                    <label for="created_at_end" class="mr-2">-</label>
                                    <input type="text" id="created_at_end" class="form-control created_at" name="created_at[]" value="{{ request('created_at') ? request('created_at')[1] : '' }}">
                                </div>
                                <div class="form-group ml-2">
                                    <button class="btn btn-success">搜索</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($testPapers->isNotEmpty())
                        <div class="table-responsive-lg">
                            <table class="table table-centered mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>状态</th>
                                    <th style="width: 240px;">操作</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($testPapers as $testPaper)
                                    <tr>
                                        <td>
                                            {{ $testPaper->id }}
                                        </td>
                                        <td>
                                            {{ $testPaper->name }}
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <a href="{{ route('backstage.test-papers.edit', $testPaper) }}" class="btn btn-info" onclick="">编辑</a>
                                            <form style="display: inline;" action="{{ route('backstage.test-papers.destroy', $testPaper) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <a href="javascript:void(0);"
                                                   class="btn btn-danger"
                                                   onclick="swal({title: '是否确定删除？', showCancelButton: true}).then((res) => {if (res.value) $(this).parent().submit()});"
                                                >
                                                    删除
                                                </a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include('backstage.templates.empty')
                    @endif
                </div>
                @if ($testPapers->isNotEmpty() && $testPapers->total > config('modules.paginator.per_page'))
                    <div class="card-footer">
                        @include('backstage.templates.card_footer_pagination', ['data' => $testPapers])
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('after_app_js')
    <script>
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
        })
    </script>
@stop
