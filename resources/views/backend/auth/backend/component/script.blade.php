

<!-- jQuery 3 -->
<script src="{!! asset('backend/bower_components/jquery/dist/jquery.min.js') !!}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{!! asset('backend/bower_components/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
<!-- iCheck -->
<script src="{!! asset('backend/plugins/iCheck/icheck.min.js') !!}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>

<script>
    $(document).ready(function () {

        $("#formLock").submit(function (e) {

            //stop submitting the form to see the disabled button effect
            //  e.preventDefault();

            //disable the submit button
            $("#btnSubmit").attr("disabled", true);

            //disable a normal button
            $("#btnLock").attr("disabled", true);

            return true;

        });
    });
</script>