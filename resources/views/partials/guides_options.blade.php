<option>--- Select ---</option>
@if(!empty($guides))
  @foreach($guides as $key => $value)
  
    <option value="{{ $value->id }}">{!! $value->translate(App::getLocale())->name !!}</option>
  @endforeach
@endif