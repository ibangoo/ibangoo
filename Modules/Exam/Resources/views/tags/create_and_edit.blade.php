@extends('backstage.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">标签管理</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">标签列表</a></li>
                        <li class="breadcrumb-item active">新增标签</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    新增标签
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ isset($item) ? route('backstage.tags.update', $item) : route('backstage.tags.store')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ isset($item) ? method_field('PATCH') : null }}
                        <div class="form-group row mb-3">
                            <label for="name" class="col-2 col-form-label text-right">名称</label>
                            <div class="col-10">
                                <input type="text" id="name" name="name" class="form-control" placeholder="" value="{{ $item->name ?? old('name') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="status" class="col-2 col-form-label text-right">是否禁用</label>
                            <div class="row col-10" style="margin-top: 8px;">
                                <div class="custom-control custom-radio mx-2">
                                    <input type="radio" id="status1" name="status" class="custom-control-input" value="1" @if((boolean)($item->status ?? old('status', true)) === true) checked @endif>
                                    <label class="custom-control-label" for="status1">启用</label>
                                </div>
                                <div class="custom-control custom-radio mx-2">
                                    <input type="radio" id="status2" name="status" class="custom-control-input" value="0" @if((boolean)($item->status ?? old('status', true)) === false) checked @endif>
                                    <label class="custom-control-label" for="status2">禁用</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-10">
                                <button type="submit" class="btn btn-primary">保存</button>
                                <a href="{{ URL::previous() }}" class="btn btn-primary mx-sm-2">返回</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

