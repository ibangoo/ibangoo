@if(isset($data) && $data->isNotEmpty() && $data->total() > config('modules.paginator.per_page'))
    <div class="card-footer">
        <div class="row">
            <div class="col-md-12" style="display: flex;">
                {{ $data->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
@endif