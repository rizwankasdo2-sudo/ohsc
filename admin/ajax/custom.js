jQuery(function($){
    $(document).on('change', '.info_status', function(){
        var status = $(this).val();
        var info_id = $(this).parent('td').find('.info_id_found').val();
        $.ajax({
            type: "post",
            url: "info_status_change_ajax.php",
            data: "status="+status+"&info_id="+info_id,
            success: function(msg){

            }
        });
    });

    $("#status_select, #from_date, #to_date, #course_type").change(function(){
        var status = $("#status_select").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var course_type = $("#course_type").val();

        $.ajax({
            type: "post",
            url: "search_info_ajax.php",
            data: "status="+status+"&from_date="+from_date+"&to_date="+to_date+"&course_type="+course_type,
            success: function(msg){
                $('.d_found_course').html(msg);
            }
        });

    });

    $('#student_name').keyup(function(){
        var name = $(this).val();
        $.ajax({
            type: "post",
            url: "search_info_by_name_ajax.php",
            data: "name="+name,
            success: function(msg){
                $('.d_found_course').html(msg);
            }
        });
    });

    $('#course_name').keyup(function(){
        var course = $(this).val();
        $.ajax({
            type: "post",
            url: "search_info_by_course_ajax.php",
            data: "course="+course,
            success: function(msg){
                $('.d_found_course').html(msg);
            }
        });
    });
});
