
function check_all() {
    //item_checkbox
    $('input[class="item_checkbox"]:checkbox').each(function () {
        if ( $('input[class="check_all"]:checkbox:checked').length === 0 ){
            $(this).prop('checked',false)
        }else {
            $(this).prop('checked',true)
        }

    })
}
function delete_all() {
    $(document).on('click','.del_all',function (){
        $('#form_data').submit();

    });
    $(document).on('click','.delBtn',function () {
        var item_checked =  $('input[class="item_checkbox"]:checkbox').filter(":checked").length;
        if (item_checked > 0) {
            $('.record_count').text(item_checked);
            $('.not_empty_record').removeClass('hidden');
            $('.empty_record').addClass('hidden');
        }
        else {
            $('.record_count').text('');
            $('.not_empty_record').addClass('hidden');
            $('.empty_record').removeClass('hidden');
        }
       $('#multipleDelete').modal('show');
    })
    
}

$(".logo").change(function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.logo-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
});

$(".icon").change(function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.icon-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
});

$(".image").change(function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.image-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
});

$(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

function myFunction() {
    var x = document.getElementById("password_confirmation");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}


