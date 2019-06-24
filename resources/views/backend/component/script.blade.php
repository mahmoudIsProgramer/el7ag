<!-- jQuery 3 -->
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('backend/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('backend/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{asset('backend/bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{asset('backend/bower_components/morris.js/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('backend/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{asset('backend/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('backend/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('backend/bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('backend/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{asset('backend/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('backend/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('backend/dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/dist/js/demo.js')}}"></script>


<script src="{{asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('backend/bower_components/datatables.net-bs/js/dataTables.buttons.min.js')}}"></script>


<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>

<script src="{{asset('backend/dist/js/myFunction.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('backend/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
{{--js tree--}}
<script src="{{asset('backend/jstree\jstree.js')}}"></script>
<script src="{{asset('backend/jstree\jstree.checkbox.js')}}"></script>

<script src="{!! asset('backend/plugins/timepicker/bootstrap-timepicker.min.js') !!}"></script>

<script src="{!! asset('backend/plugins/input-mask/jquery.inputmask.js') !!}"></script>
<script src="{!! asset('backend/plugins/input-mask/jquery.inputmask.date.extensions.js') !!}"></script>
<script src="{!! asset('backend/plugins/input-mask/jquery.inputmask.extensions.js') !!}"></script>

<!-- date-range-picker -->
<!-- bootstrap color picker -->
<script src="{!! asset('backend/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') !!}"></script>


{{-- full calendar  --}}

<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

{{-- <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/locale/hi.js'></script> --}}
{{--<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/locale/ar-sa.js'></script>--}}


<script>
    $(function () {
        $('.select2').select2()
    });

    $(function () {
        $('#example1').DataTable()
        $('#example2').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false
        })
    });

    $('#datemask').inputmask('dd-mm-yyyy', { 'placeholder': 'dd/mm/yyyy' });

    $('#reservationtime').daterangepicker(
        { timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' });
    $('.timepicker').timepicker({
        showInputs: false
    });
    $('[data-mask]').inputmask();

</script>
<script>
    $(document).ready(function () {

        $("#formABC").submit(function (e) {

//stop submitting the form to see the disabled button effect
//  e.preventDefault();

//disable the submit button
$("#btnSubmit").attr("disabled", true);

//disable a normal button
$("#btnTest").attr("disabled", true);

return true;

});
});
</script>



@stack('js')

@stack('css')

