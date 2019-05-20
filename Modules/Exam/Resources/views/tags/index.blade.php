@extends('backstage.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">标签管理</a></li>
                        <li class="breadcrumb-item active">标签列表</li>
                    </ol>
                </div>
                <h4 class="page-title">标签列表</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-11">
                            <form id="tags-search-form" class="form-inline">
                                <div class="form-group mb-2">
                                    <input type="search" class="form-control" id="name-input" name="name" placeholder="标签名称" value="{{ request()->query('name') }}">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                        <table class="table table-centered mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>状态</th>
                                <th style="width: 240px;">操作</th>
                            </tr>
                            </thead>
                            @if(isset($tags) && $tags->isNotEmpty())
                                <tbody>
                                @foreach($tags as $tag)
                                    <tr>
                                        <td>
                                            {{ $tag->id }}
                                        </td>
                                        <td>
                                            {{ $tag->name }}
                                        </td>
                                        <td>
                                            {!! $tag->status === true ? '<i class="mdi mdi-circle text-success"></i> 启用中' : '<i class="mdi mdi-circle text-danger"></i> 禁用中' !!}
                                        </td>
                                        <td>
                                            <a href="{{ route('backstage.tags.edit', $tag) }}" class="btn btn-info" onclick="">编辑</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-12">
                                                <div class="text-center">
                                                    <img src="{{ asset('/images/file-searching.svg') }}" height="90" alt="File not found Image">
                                                    <h4 class="text-uppercase text-danger mt-3">没有数据</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
                @if(isset($tags) && $tags->isNotEmpty() && $tags->total() > config('modules.paginator.per_page'))
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-7">
                                {{ $tags->appends(request()->all())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('after_app_js')
    <script>
        $('#name-input').keydown(function (e) {
            if (e.keyCode === 13) {
                globalLoading();
                $('#tags-search-form').submit();
            }
        })
    </script>
@stop