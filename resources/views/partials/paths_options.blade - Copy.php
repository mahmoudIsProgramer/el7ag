<option>--- Select ---</option>
@if(!empty($drivers))
  @foreach($drivers as $key => $value)
  
    <option value="{{ $value->id }}">{!! $value->translate(App::getLocale())->name !!}</option>
  @endforeach
@endif