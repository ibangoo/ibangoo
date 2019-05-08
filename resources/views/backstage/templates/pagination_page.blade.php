@extends("backstage.layouts.app")

@section("content")
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pagination</a></li>
                        <li class="breadcrumb-item active">Pagination</li>
                    </ol>
                </div>
                <h4 class="page-title">Pagination</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-11">
                            <form class="form-inline" id="tutorial-class-search-form">
                                <div class="form-group mb-2">
                                    <input type="search" class="form-control" id="name-input" name="name" placeholder="标题/导师姓名" value="{{ request()->query('name') }}">
                                </div>
                                <div class="form-group mb-2 mx-2">
                                    <select class="custom-select" id="university-select" name="university_id">
                                        <option value="null">请选择...</option>
                                        @foreach($options as $option)
                                            <option value="{{ $option->id }}" {{ (int)request()->query('option_id') === $option->id ? "selected" : null }}>
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th style="width: 240px;">操作</th>
                            </tr>
                            </thead>
                            @if($items->isNotEmpty())
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->id }}
                                        </td>

                                        <td>
                                            <a href="{{ route('// TODO', $item) }}" class="btn btn-info" onclick="">编辑</a>
                                            <form style="display: inline;" action="{{ route('// TODO', $item) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <a href="javascript:void(0);" onclick="swal({title: '是否确定删除？',showCancelButton: true}).then((res)=>{if(res.value) $(this).parent().submit()});" class="btn btn-danger">
                                                    删除
                                                </a>
                                            </form>
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
                    @if($items->isNotEmpty() && $items->total() > 10)
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-7">
                                    {{ $items->appends(request()->only([]))->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
