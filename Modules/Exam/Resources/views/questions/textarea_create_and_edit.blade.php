@extends('backstage.layouts.app')

@section('after_app_css')
    <style>
        .content-image-preview-image {
            width: 50%;
            max-width: 500px;
        }

        .explain-image-preview-image {
            width: 50%;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">题库管理</a></li>
                        <li class="breadcrumb-item active">添加试题</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    添加简答题
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="submit-form" action="{{ route('backstage.questions.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ isset($item) ? method_field('PATCH') : null }}

                        <div class="form-group row mb-3">
                            <label for="type" class="col-2 col-form-label">类型</label>
                            <div class="row col-10" style="margin-top: 8px;">
                                @foreach(\Modules\Exam\Entities\Question::$typeMap as $key => $type)
                                    <div class="custom-control custom-radio mx-2">
                                        <input type="radio"
                                               id="type{{ $key }}"
                                               name="type"
                                               class="custom-control-input choice-type"
                                               value="{{ $key }}" @if(($item->type ?? request('type')) === $key) checked @endif
                                               data-href="{{ route('backstage.questions.create', ['type' => $key]) }}"
                                        >
                                        <label class="custom-control-label" for="type{{ $key }}">{{ $type }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="tags" class="col-2 col-form-label">标签</label>
                            <div class="col-10">
                                <select id="tags" name="tags[]" class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="选择标签">
                                    @forelse($tags as $tag)
                                        <option value="{{ $tag->id }}" @if(($item->tags ?? old('tags')) === $tag->id) selected @endif>
                                            {{ $tag->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="content" class="col-2 col-form-label">题干</label>
                            <div class="col-10">
                                <textarea id="content" name="content" data-toggle="maxlength" class="form-control" maxlength="225" rows="3" placeholder="请填写题干内容"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="content_image" class="col-2 col-form-label">题干插图</label>
                            <div class="col-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="content_image" name="content_image" onchange="handleImages(this.files, 'content-image-preview')">
                                    <label class="custom-file-label" for="content_image">点击上传图片</label>
                                </div>
                                <div id="content-image-preview" class="card d-block" style="display: none; margin-bottom: 0">
                                    <div class="card-img-overlay" style="display: none;">
                                        <div class="badge badge-secondary p-1" style="display: none;">预览</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="options" class="col-2 col-form-label">答案</label>
                            <div class="col-10">
                                <textarea id="options" name="options" data-toggle="maxlength" class="form-control" maxlength="225" rows="3" placeholder="此内容会显示在试卷报告中，方便阅卷老师进行参考，不会展示给学生"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="explain" class="col-2 col-form-label">试题解析</label>
                            <div class="col-10">
                                <textarea id="explain" name="explain" data-toggle="maxlength" class="form-control" maxlength="225" rows="3" placeholder="请填写题干内容"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="explain_image" class="col-2 col-form-label">解析插图</label>
                            <div class="col-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="explain_image" name="explain_image" onchange="handleImages(this.files, 'explain-image-preview')">
                                    <label class="custom-file-label" for="explain_image">点击上传图片</label>
                                </div>
                                <div id="explain-image-preview" class="card d-block" style="display: none; margin-bottom: 0">
                                    <div class="card-img-overlay" style="display: none;">
                                        <div class="badge badge-secondary p-1" style="display: none;">预览</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-10">
                                <button id="submit-button" type="submit" class="btn btn-primary">保存</button>
                                <a href="javascript:window.location.reload();" class="btn btn-info mx-sm-2">重置</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_app_js')
    <script src="{{ asset('js/vendor/vue.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            // 监听题库类型
            let type = "{{ request('type', 'radio') }}";
            $('.choice-type').click(function (event) {
                if (type !== event.target.value) {
                    globalLoading();
                    window.location.href = $(this).data('href');
                }
            });
        });

        function handleImages(files, element) {

            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                let imageType = /^image\//;

                if (!imageType.test(file.type)) {
                    continue;
                }

                let img = document.createElement("img");
                img.classList.add(element + "-image");
                img.file = file;
                let preview = document.getElementById(element);
                let previewElements = document.getElementsByClassName(element + "-image");
                for (let i = 0; i < previewElements.length; i++) {
                    preview.removeChild(previewElements[i]);
                }
                preview.appendChild(img);

                let reader = new FileReader();
                reader.onload = (function (aImg) {
                    return function (e) {
                        aImg.src = e.target.result;
                    };
                })(img);
                reader.readAsDataURL(file);
            }
        }
    </script>
@stop