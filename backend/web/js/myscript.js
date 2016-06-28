$(document).ready(function () {
    if ($('#question-question_type_id').val() !== 3) {
        $('#question-answers_cnt option').each(function (e) {
            if($(this).val()==1){
                $(this).css('display','none');
            }
            $(this).removeAttr('disabled');

        });
    }
    $('#question-question_type_id').click(function () {
        //console.log('type ' + $('#question-question_type_id').val());
        if ($('#question-question_type_id').val() == 3) {
            $('#question-answers_cnt').val(1);
            $('#question-answers_cnt option').each(function (e) {
                if ($(this).val() != 1) {
                    $(this).attr('disabled', 'disabled');
                }

            });


        } else {
            $('#question-answers_cnt').val(2);
            $('#question-answers_cnt option').each(function (e) {
                if($(this).val()==1){
                    $(this).css('display','none');
                }
                $(this).removeAttr('disabled');

            });
        }
    });

});