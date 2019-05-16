@extends('backstage.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">题库管理</a></li>
                        <li class="breadcrumb-item active">批量导入试题</li>
                    </ol>
                </div>
                <h4 class="page-title">批量导入试题</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">批量导入说明：</h4>
                    <span>请不要随意变更模版文件中单元格的样式，例如合并单元格，随意增删列等</span><br>
                    <span>支持题型：单选题，多选题，不定项选择题，填空题，问答题，判断题，文字拼写务必以此为准</span><br>
                    <span>选择题的选项可以 2-8 均可；每天的选项数量务必与选项内容数量保持一致，否则不能导入</span><br>
                    <span>选择题答案务必大写格式，多选项字母务必连着拼写，不要有空格或者符号间隔</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">1.请先下载模版</h4>
                    <span>建议按多次分批导入，导入前建议试题编号分类，方便题库管理</span><br>
                    <span>请使用微软的 Office 编辑，不要使用 WPS</span>
                    <form action="{{ route('backstage.questions.download-excel-template') }}" class="form-inline mt-2">
                        {{ csrf_field() }}
                        <button class="btn btn-success">下载 Excel 格式模版</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="md-2">2.导入试题</h4>
                    <form action="{{ route('backstage.questions.import.handle') }}" class="form-inline mt-2" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group" style="width: 400px;">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="upload_excel" name="excel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                <label class="custom-file-label" for="upload_excel" style="justify-content: left;">请上传
                                    Excel 格式试题文件</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary ml-2">上传</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="info-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-to-do h1 text-success"> </i>
                        <h4 class="mt-2">导入文件成功</h4>
                        @if ($typeCounts = request()->type_counts)
                            <div class="table-responsive-sm">
                                <table class="table mb-0">
                                    @foreach($typeCounts as $type => $count)
                                        <tr>
                                            <td class="text-left">{{ \Modules\Exam\Entities\Question::$typeMap[$type] }}</td>
                                            <td class="text-right">
                                            <span class="badge badge-success badge-pill">
                                                {{ $count }}
                                            </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-left">总计</td>
                                        <td class="text-right">
                                            <span class="badge badge-info badge-pill">{{ array_sum($typeCounts) }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                        <button type="button" class="btn btn-success my-2" data-dismiss="modal">我知道了</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_app_js')
    <script>
        let excelUploadElement = document.getElementById('upload_excel');
        excelUploadElement.addEventListener('change', handleFiles, false);

        function handleFiles() {
            let files = this.files;
            $('.custom-file-label').text(files[0].name);
        }

        @if(request()->type_counts)
        $('#info-alert-modal').modal();
        @endif
    </script>
@stop