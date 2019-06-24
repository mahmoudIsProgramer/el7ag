@if(Auth::guard('admin')->user()->hasPermission('delete_admins'))
<button type="button" class="btn btn-info  btn btn-danger" data-toggle="modal"
        data-target="#deleteAdmin{!! $id !!}"><i class="fa fa-trash"></i></button>
@else
    <button type="button" class="btn btn-info  btn btn-danger disabled" ><i class="fa fa-trash"></i></button>
@endif

<!-- Modal -->
<div id="deleteAdmin{!! $id !!}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('admin.Delete')</h4>
            </div>
            {!! Form::open(['route'=>['admin.admin.destroy',$id],'method'=>'delete']) !!}
            <div class="modal-body">
                <h4>@lang('admin.Are your sure delete this ') {!! $firstName.' '.$lastName !!}</h4>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" >@lang('admin.Yes')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
            </div>
            {!! Form::close() !!}
        </div>

    </div>
</div>