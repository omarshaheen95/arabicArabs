function examFormSubmit() {
    let form = $('#term_form');
    let URL = form.attr('action');
    let METHOD = form.attr('method');
    let fd = new FormData(form[0]);

    $('#submit-term-modal').modal('hide');

    showLoadingModal('Save Assessment','Saving the assessment...');

    $.ajax({
        type: METHOD,
        url: URL,
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            hideLoadingModal()
            toastr.success('Assessment Saved Successfully', 'Assessment Saved')
            setTimeout(function () {
                window.location.replace(data.data);
            },1000)
        },
        error: function (xhr, status, error) {
            hideLoadingModal()
            showAlert('Error in saving the assessment!',error,'error','Try Again','Cancel',(callback)=>{
                if (callback) {
                    examFormSubmit()
                }
            })
        }

    })
}

$(function () {
    $(".sortable1, .sortable2").sortable({
        connectWith: ".connectedSortable"
    }).disableSelection();
});


$(".sortable2").droppable({
    drop: function () {

        let questionId = $(this).attr('question-id');
        //alert($questionId);


        setTimeout(function () {
            let i = 1;
            $('.sortable2[question-id = ' + $questionId + '] li span').each(function () {
                //$(this).html($i++ );
            });
        }, 1);
        setTimeout(function () {
            let i = 1;
            $('.sortable2[question-id = ' + $questionId + '] li input').each(function () {
                $(this).val($i++);
            });
        }, 1);
    }
});


$(".sortable1").droppable({
    drop: function () {
        setTimeout(function () {
            $('.sortable1 li span').each(function (i) {
                var humanNum = i + 1;
                $(this).html('');
            });
        }, 1);
        setTimeout(function () {
            $('.sortable1 li input').each(function (i) {
                var humanNum = i + 1;
                $(this).val('');
            });
        }, 1);
    }
});

$(document).ready(function () {

    /*---------------------------------------------------
        timer
    ---------------------------------------------------*/
    let clock = $('#clock');
    if (clock.length > 0 && typeof TIME !== 'undefined' && TIME) {
        var qnt = TIME,
            val = (qnt * 60 * 60 * 1000),
            selectedDate = new Date().valueOf() + val;

        clock.countdown(selectedDate.toString())
            .on('update.countdown', function (event) {
                var format = '%H:%M:%S';
                $(this).html(event.strftime(format));
                $("#timer-ago").val(event.strftime(format));
                //localStorage.setItem("timer_val", event.offset.totalSeconds);
            })
            .on('finish.countdown', function (event) {
                $(this).parent().addClass('disabled').html('This Time has expired!');
                toastr.warning('The Time has expired!', 'Warning')
                 examFormSubmit()
            });

    }

});
