@extends('backstage.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">测试管理</a></li>
                        <li class="breadcrumb-item active">新建测试</li>
                    </ol>
                </div>
                <h4 class="page-title">新建测试</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="submit-form"
                                  method="POST"
                                  action="{{ isset($test) ? route('backstage.tests.update', $test) : route('backstage.tests.store') }}"
                            >
                                {{ csrf_field() }}
                                {{ isset($test) ? method_field('PATCH') : null }}
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label text-right" for="name">测试名称</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $test->name ?? old('name') }}" placeholder="例如：数学期中考试">
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label text-right" for="total_score">设置总分</label>
                                    <div class="col-md-10">
                                        <input type="text" id="total_score" name="total_score" class="form-control" placeholder="设置试卷总分数" v-model="totalScore">
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label text-right" for="options">设置试题</label>
                                    <div class="col-md-10">
                                        <div class="form-row align-items-center" v-for="(option, key) in options">
                                            <div class="col-auto">
                                                <select class="from-control custom-select mb-2" v-model="option.type">
                                                    <option disabled value="">请选择</option>
                                                    <option
                                                            v-for="(title, type) in types"
                                                            v-bind:value="type"
                                                    >@{{ title }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" class="form-control mb-2" v-model="option.num" placeholder="填写试题数量">
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" class="form-control mb-2" v-model="option.score" placeholder="填写试题分数/题">
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-danger mb-2" v-on:click="delOption(key)">
                                                    删除
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" v-on:click="addOption">
                                            <i class="dripicons-plus"> </i>添加选项
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col-md-2 text-right">
                                        <label>添加类型</label>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio1" name="mode" class="custom-control-input"
                                                   v-model="mode"
                                                   value="{{ \Modules\Exam\Entities\Test::MODE_TAGS}}"
                                                   @if (isset($test))
                                                       disabled
                                                   @endif
                                            >
                                            <label class="custom-control-label" for="customRadio1">标签抽题</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio2" name="mode" class="custom-control-input"
                                                   v-model="mode"
                                                   value="{{ \Modules\Exam\Entities\Test::MODE_QUESTIONS }}"
                                                   @if (isset($test))
                                                       disabled
                                                   @endif
                                            >
                                            <label class="custom-control-label" for="customRadio2">题库选题</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-3" v-show="mode === 'tag'">
                                    <label for="tags" class="col-2 col-form-label text-right">选择标签</label>
                                    <div class="col-10">
                                        <select id="tags" name="tags[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="请选择...">
                                            @forelse($tags as $tag)
                                                @if(isset($test))
                                                    <option value="{{ $tag->id }}" @if(in_array($tag->id, $test->tags->pluck('id')->toArray())) selected @endif>
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
                                    <label for="status" class="col-2 col-form-label text-right">是否禁用</label>
                                    <div class="row col-10" style="margin-top: 8px;">
                                        <div class="custom-control custom-radio mx-2">
                                            <input type="radio" id="status1" name="status" class="custom-control-input" value="1"
                                                   @if((boolean)($test->status ?? old('status', true)) === true) checked @endif>
                                            <label class="custom-control-label" for="status1">启用</label>
                                        </div>
                                        <div class="custom-control custom-radio mx-2">
                                            <input type="radio" id="status2" name="status" class="custom-control-input" value="0"
                                                   @if((boolean)($test->status ?? old('status', true)) === false) checked @endif>
                                            <label class="custom-control-label" for="status2">禁用</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0 row">
                                    <div class="col-10">
                                        <button id="submit-button" type="submit" class="btn btn-primary">保存</button>
                                        <input type="hidden" id="options" name="options">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_app_js')
    <script src="{{ asset('js/vendor/vue.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2();

            $('#submit-form').submit(function (event) {
                event.stopPropagation();

                let options = app.options;
                let totalScore = 0;
                options = JSON.parse(JSON.stringify(options));
                if (!options) {
                    alertError('设置试题不能为空');
                }

                for (let i = 0; i < options.length; i++) {
                    if (!options[i].type) {
                        alertError('请选择设置试题题型');
                        return false;
                    }

                    if (!options[i].num) {
                        alertError('请填写试题数量');
                    }

                    if (!options[i].score) {
                        alertError('请填写试题分数');
                    }

                    totalScore += parseInt(options[i].num) * parseInt(options[i].score)
                }

                if (totalScore !== parseInt(app.totalScore)) {
                    alertError('试题总分与测试总分不一致');
                    return false;
                }

                $('#options').val(JSON.stringify(options));
                globalLoading();
            });
        });

        let oldOptions = [];
        @if(old('options'))
            oldOptions = {!! old('options') !!};
        @endif

        @if(isset($test) && !empty($test->options))
            oldOptions = {!! $test->options !!}
        @endif

        let app = new Vue({
                el: '#app',
                data: {
                    mode: "{{ $test->mode ?? old('mode') }}",
                    totalScore: "{{ $test->total_score ?? old('total_score') }}",
                    options: oldOptions,
                    types: {
                        radio: '单选题',
                        checkbox: '多选题',
                        boolean: '判断题',
                        input: '填空题',
                        textarea: '简答题'
                    }
                },
                methods: {
                    addOption: function () {
                        let obj = {
                            type: "",
                            num: null,
                            score: null,
                        };

                        return this.options.push(obj);
                    },
                    delOption: function (key) {
                        return this.options.splice(key, 1);
                    },
                }
            });
    </script>
@stop
