@extends("backstage.layouts.app")

@section("content")
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">私人订制</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">私人订制班型</a></li>
                        <li class="breadcrumb-item active">新增</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    新增
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ isset($item) ? method_field('PATCH') : null }}
                        <div class="form-group row mb-3">
                            <label for="param" class="col-2 col-form-label">Param</label>
                            <div class="col-10">
                                <input type="text" id="param" name="param" class="form-control"  placeholder="公开课底薪" value="{{ $item->param ?? old('param') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="options" class="col-2 col-form-label">Options</label>
                            <div class="col-10">
                                <select class="custom-select" name="options" id="options">
                                    <option value="">Select the Option</option>
                                    @foreach($options as $option)
                                        <option value="{{ $option->id }}" @if(($item->param ?? old('param')) == $option->id) selected @endif>
                                            {{ $option->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="status" class="col-2 col-form-label">IsShow</label>
                            <div class="row col-10" style="margin-top: 8px;">
                                <div class="custom-control custom-radio mx-2">
                                    <input type="radio" id="status1" name="status" class="custom-control-input" value="1" @if(($item->status ?? old('status')) === 1) checked @endif>
                                    <label class="custom-control-label" for="status1">Show</label>
                                </div>
                                <div class="custom-control custom-radio mx-2">
                                    <input type="radio" id="status2" name="status" class="custom-control-input" value="0" @if(($item->status ?? old('status')) === 0) checked @endif>
                                    <label class="custom-control-label" for="status2">Hide</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-10">
                                <button type="submit" class="btn btn-primary">保存</button>
                                <a href="javascript:void(0);" class="btn btn-primary mx-sm-2">返回</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

