@extends('backstage.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">测试管理</a></li>
                        <li class="breadcrumb-item active">测试列表</li>
                    </ol>
                </div>
                <h4 class="page-title">测试列表</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <a href="{{ route('backstage.tests.create') }}" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle mr-2"> </i>新增测试</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @if ($tests->isNotEmpty())
                                <div class="table-responsive-lg">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>测试标题</th>
                                            <th>测试类型</th>
                                            <th>判卷类型</th>
                                            <th>测试状态</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($tests as $test)
                                            <tr>
                                                <td>{{ $test->name }}</td>
                                                <td>{{ $test->mode_name }}</td>
                                                <td>{{ $test->is_auto_label }}</td>
                                                <td>
                                                    {!! $test->status ? '<i class="mdi mdi-circle text-success"></i> ' : '<i class="mdi mdi-circle text-danger"></i> ' !!}
                                                    {{ $test->status_label }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('backstage.tests.edit', $test) }}" class="btn btn-primary mr-1">编辑</a>
                                                    <a href="#" class="btn btn-primary mr-1">已提交试卷</a>
                                                    <a href="{{ route('backstage.tests.questions', $test) }}" class="btn btn-primary mr-1">管理试题</a>
                                                    <form action="{{ route('backstage.tests.change-status', $test)  }}" method="POST" style="display: inline;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PATCH') }}
                                                        <button type="submit" class="btn btn-{{ $test->status ? 'danger' : 'info' }} mr-1" onclick="globalLoading()">{{ $test->status ? '禁用' : '启用' }}</button>
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
                    </div>
                </div>
                @include('backstage.templates.card_footer_pagination', ['data' => $tests])
            </div>
        </div>
    </div>
@endsection