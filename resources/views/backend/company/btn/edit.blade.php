
@if(Auth::guard('admin')->user()->hasPermission('update_company'))
<a href="{!! route('admin.company.edit',$id) !!}" class="btn btn-info"> <i class="fa fa-edit"></i></a>
@else
    <button  class="btn btn-info" disabled> <i class="fa fa-edit"></i></button>
@endif