@extends('backend.layouts.master')

@section('title',trans('admin.Companies'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Companies')
            <small>@lang('admin.Control panel')</small>
        </h1>

    </section>
@endsection()

@section('content')


    <section class="content">
        <div class= 'box'>
            <div class='border-box'>
                <div id='calendar'></div>
            </div>
        </div>
    </section>


    <script>
        $(document).ready(function() {
            
            // page is now ready, initialize the calendar...
            $('#calendar').fullCalendar({
                locale: 'ar-sa', 
                // put your options and callbacks here
                events : [
                    @foreach($trips as $trip)
                    {
                        title :" {{ $trip->translate(\App::getLocale())->name}}",
                        start : '{{ $trip->start_date }}',
                        url : "{{ route('company.trip.edit_by_calendar', $trip->id) }}"
                    },
                    @endforeach
                ]
                
            }); 


            $(function() {

                $('#calendar').fullCalendar({
                lang: 'ar'
                });

            }); 

        });
        

    </script>

@endsection


