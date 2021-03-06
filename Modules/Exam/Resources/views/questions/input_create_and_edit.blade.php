@extends('backstage.layouts.app')

@section('after_app_css')
    <style>
        .content-image-preview-image {
            width: 50%;
            max-width: 500px;
        }

        .explain-image-preview-image {
            width: 50%;
            max-width: 500px;
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
                        <li class="breadcrumb-item active">{{isset($question) ? '编辑试题' : '添加试题'}}</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    {{isset($question) ? '编辑填空题' : '添加填空题'}}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="submit-form"
                          method="POST"
                          class="form-horizontal"
                          enctype="multipart/form-data"
                          action="{{ isset($question) ? route('backstage.questions.update', $question) :route('backstage.questions.store') }}"
                    >
                        {{ csrf_field() }}
                        {{ isset($question) ? method_field('PATCH') : null }}

                        <div class="form-group row mb-3">
                            <label for="type" class="col-2 col-form-label text-right">类型</label>
                            <div class="row col-10" style="margin-top: 8px;">
                                @foreach(\Modules\Exam\Entities\Question::$typeMap as $key => $type)
                                    <div class="custom-control custom-radio mx-2">
                                        <input type="radio"
                                               id="type{{ $key }}"
                                               name="type"
                                               class="custom-control-input choice-type"
                                               value="{{ $key }}" @if(($question->type ?? request('type')) === $key) checked @endif
                                               data-href="{{ route('backstage.questions.create', ['type' => $key]) }}"
                                        >
                                        <label class="custom-control-label" for="type{{ $key }}">{{ $type }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="tags" class="col-2 col-form-label text-right">标签</label>
                            <div class="col-10">
                                <select id="tags" name="tags[]" class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="选择标签">
                                    @forelse($tags as $tag)
                                        @if(isset($question))
                                            <option value="{{ $tag->id }}" @if(in_array($tag->id, $question->tags->pluck('id')->toArray())) selected @endif>
                                                {{ $tag->name }}
                                            </option>
                                        @else
                                            <option value="{{ $tag->id }}" @if(in_array($tag->id, old('tags', []))) selected @endif>
                                                {{ $tag->name }}
                                            </option>
                                        @endif
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="content" class="col-2 col-form-label text-right">题干</label>
                            <div class="col-10">
                                <textarea id="content" name="content" data-toggle="maxlength" class="form-control" maxlength="225" rows="3" placeholder="请填写题干内容，请在题目中用（  ）表示需填空的地方">{{ $question->content ?? old('content') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="content_image" class="col-2 col-form-label text-right">题干插图</label>
                            <div class="col-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="content_image" name="content_image" onchange="handleImages(this.files, 'content-image-preview')">
                                    <label class="custom-file-label" for="content_image">点击上传图片</label>
                                </div>
                                <div id="content-image-preview" class="card d-block" style="display: none; margin-bottom: 0">
                                    @if(isset($question) && $question->content_image)
                                        <img class="content-image-preview-image" src="{{ Storage::url($question->content_image) }}" alt="{{ $question->content }}">
                                    @endif
                                    <div class="card-img-overlay" style="display: none;">
                                        <div class="badge badge-secondary p-1" style="display: none;">预览</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-2 col-form-label text-right">答案</label>
                            <div id="options-container" class="col-10">
                                <div class="form-row align-items-center" v-for="(option, key) in options">
                                    <div class="col-auto">
                                        <label v-bind:for="'option_key_' + key">@{{ key+1 }}</label>
                                    </div>
                                    <div class="col-auto">
                                        <input v-bind:id="'option_key_' + key" type="text" class="form-control mb-2" v-model="option.body">
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-danger mb-2" v-on:click="delOption(key)">
                                            删除
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" v-on:click="addOption()">
                                    <i class="dripicons-plus"> </i>添加答案
                                </button>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="explain" class="col-2 col-form-label text-right">试题解析</label>
                            <div class="col-10">
                                <textarea id="explain" name="explain" data-toggle="maxlength" class="form-control" maxlength="225" rows="3" placeholder="请填写题干内容">{{ $question->explain ?? old('explain') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="explain_image" class="col-2 col-form-label text-right">解析插图</label>
                            <div class="col-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="explain_image" name="explain_image" onchange="handleImages(this.files, 'explain-image-preview')">
                                    <label class="custom-file-label" for="explain_image">点击上传图片</label>
                                </div>
                                <div id="explain-image-preview" class="card d-block" style="display: none; margin-bottom: 0">
                                    @if(isset($question) && $question->explain_image)
                                        <img class="explain-image-preview-image" src="{{ Storage::url($question->explain_image) }}" alt="{{ $question->explain }}">
                                    @endif
                                    <div class="card-img-overlay" style="display: none;">
                                        <div class="badge badge-secondary p-1" style="display: none;">预览</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-10">
                                <button id="submit-button" type="submit" class="btn btn-primary">保存</button>
                                @if(!isset($question))
                                    <a href="javascript:window.location.reload();" class="btn btn-info mx-sm-2">重置</a>
                                @endif
                                <input type="hidden" id="options" name="options">
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
            // 限制标签多选数量
            $('.select2').select2({
                maximumSelectionLength: 5,
                language: {
                    maximumSelected: function (e) {
                        return "您只能选择最多 " + e.maximum + " 个标签";
                    }
                }
            });

            // 监听题库类型
            let type = "{{ request('type', 'radio') }}";
            $('.choice-type').click(function (event) {
                if (type !== event.target.value) {
                    globalLoading();
                    window.location.href = $(this).data('href');
                }
            });

            $('#submit-form').submit(function (event) {
                event.stopPropagation();

                let options = app.options;
                options = JSON.parse(JSON.stringify(options));
                if (options.length === 0) {
                    swal({
                        title: '操作失败',
                        text: '答案不能为空',
                        type: 'error',
                        showConfirmButton: false
                    });

                    return false;
                }

                for (let i = 0; i < options.length; i++) {
                    if (!options[i].body) {
                        swal({
                            title: '操作失败',
                            text: '请填写答案内容',
                            type: 'error',
                            showConfirmButton: false
                        });

                        return false;
                    }
                }
                $('#options').val(JSON.stringify(options));
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

        let oldOptions = [];
        let oldRightAnswer = "";
        @if(old('options'))
            oldOptions = {!! old('options') !!};
        for (let i = 0; i < oldOptions.length; i++) {
            if (oldOptions[i].is_right === true) {
                oldRightAnswer = oldOptions[i].code;
            }
        }
        @endif

                @if(isset($question))
            oldOptions = {!! $question->options !!};
        for (let i = 0; i < oldOptions.length; i++) {
            if (oldOptions[i].is_right === true) {
                oldRightAnswer = oldOptions[i].code;
            }
        }
                @endif

        let app = new Vue({
                el: '#options-container',
                data: {
                    options: oldOptions,
                    rightAnswer: oldRightAnswer
                },
                methods: {
                    addOption: function () {
                        return this.options.push({
                            is_right: true,
                            body: "",
                            code: this.options.length + 1
                        });
                    },
                    delOption: function (key) {
                        return this.options.splice(key, 1);
                    },
                    setAnswerRight: function (key, value) {
                        this.rightAnswer = value;
                        for (let i = 0; i < this.options.length; i++) {
                            this.options[i].is_right = false;
                        }

                        return this.options[key].is_right = true;
                    }
                }
            });
    </script>
@stop
