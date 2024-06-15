
const OK =  $('html').attr('lang')==='ar'?'تم':'OK';
const YES =  $('html').attr('lang')==='ar'?'نعم':'Yes';
const NO =  $('html').attr('lang')==='ar'?'لا':'No';

function showAlert(title,message,icon,confirmButton=false,cancelButton=false,callback) {


    let swalBody = {
        title: title,
        text: message,
        icon: icon,
        //cancelButtonColor: "#DD6B55",
        confirmButtonColor: "#C5418D",
        //closeOnConfirm: false,
        //closeOnCancel: false
    }

    if (confirmButton){
        swalBody['confirmButtonText']=YES
    }
    if (cancelButton){
        swalBody['showCancelButton']=true
        swalBody['cancelButtonText']=NO
    }

    Swal.fire(swalBody).then(
        function (result) {
            if (result.isConfirmed) {
                callback(true)
            }else {
                callback(false)
            }
        }
    )

}

function scrollTop(){
    $('#kt_scrolltop').trigger('click')
}

function messageAlert(message,icon){
    Swal.fire("", message, icon)
}

function showLoadingModal(title=null,message=null,progress_color='#138944'){
    let lang = $('html').attr('lang');
    if (!title){
        if (lang==='ar'){
            title = 'جاري تنفيذ طلبك';
        }else {
            title =  'The request is being executed'
        }
    }
    if (!message){
        if (lang==='ar'){
            message = 'الرجاء الإنتظار...';
        }else {
            message =  'Please wait ...'
        }
    }
    $('body').append('<div class="modal fade" id="loading-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">\n' +
        '    <div class="modal-dialog modal-dialog-centered">\n' +
        '        <div class="modal-content">\n' +
        '            <div id="loading-modal-close" class="btn btn-icon btn-sm btn-active-light-primary ms-2 d-none" data-bs-dismiss="modal" aria-label="Close">\n' +
        '                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>\n' +
        '            </div>\n' +
        '            <div class="modal-body">\n' +
        '                <div class="d-flex flex-column align-items-center p-5" >\n' +
        '                    <div class="fw-bold fs-3 mb-10" style="margin-bottom: 20px">'+title+'</div>\n' +
        '                    <div  style="height: 65px;width: 65px;">' +
        '                    <span class="svg-icon svg-icon-5x">' +
        '                       <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="64px" height="64px" viewBox="0 0 128 128" xml:space="preserve"><rect x="0" y="0" width="100%" height="100%" fill="#FFFFFF"/><g><linearGradient id="linear-gradient"><stop offset="0%" stop-color="#ffffff"/><stop offset="100%" stop-color="'+progress_color+'"/></linearGradient><path d="M63.85 0A63.85 63.85 0 1 1 0 63.85 63.85 63.85 0 0 1 63.85 0zm.65 19.5a44 44 0 1 1-44 44 44 44 0 0 1 44-44z" fill="url(#linear-gradient)" fill-rule="evenodd"/><animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1080ms" repeatCount="indefinite"/></g></svg>' +
        '                    </span>' +
        '                 </div>\n' +
        '                    <div class="mt-8 text-gray-700" style="margin-top: 20px">'+message+'</div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>\n')
    $('#loading-modal').modal('show')
}

function hideLoadingModal() {
    let l_modal = $('#loading-modal')
    let body = $('body')
    l_modal.modal('hide')
    // l_modal.modal('dispose')
    l_modal.remove()
    $('.modal-backdrop').remove()
    body.removeClass('modal-open')
    body.removeAttr('style')
}

function initializeDateRangePicker(id = "date_range_picker", range = []) {
    const dateRangePicker = $('#' + id);
    if (dateRangePicker.length>0){
        if (range.length > 0) {
            var rangePickerAttr = {
                startDate: moment(range[0]),
                endDate: moment(range[1]),
                locale: {
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
            };
        }else {
            var rangePickerAttr = {
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
            };
        }
        dateRangePicker.daterangepicker(rangePickerAttr);
        dateRangePicker.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format("YYYY-MM-DD") + ' - ' + picker.endDate.format("YYYY-MM-DD"));
            $('#start_' + id).val(picker.startDate.format("YYYY-MM-DD"));
            $('#end_' + id).val(picker.endDate.format("YYYY-MM-DD"));
        });

        dateRangePicker.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    }

}

function validateAndSubmit(form_class_name) {
    //on event submit form
    $("."+form_class_name).validate();

    $('.'+form_class_name).submit(function (e) {
        e.preventDefault();
        let form_id = '#'+$(this).attr('id');
        //check form is validate
        if ($(form_id).valid()) {
            //get form data
            let formData = new FormData(this);
            //get form action
            let action = $(form_id).attr('action');
            //get form method
            let method = $(form_id).attr('method');
            //show loading from dashboard_assets directory
            showLoadingModal()

            //send ajax
            $.ajax({
                url: action,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    //hide loading
                    hideLoadingModal();
                    //show message
                    Swal.fire({
                        text: data.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText:OK ,
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function () {
                        //reload page
                        location.reload();
                    });
                },
                error: function(response){
                    //hide loading
                    hideLoadingModal();
                    var messages = response.responseJSON.errors;
                    var errorMessage = response.responseJSON.message;
                    //check error messages is array
                    if (Array.isArray(messages)) {
                        //loop on error messages
                        $.each(messages, function (key, value) {
                            //show error message
                            let input = $('input[name="'+value.name+'"]')
                            input.addClass('is-invalid');

                            //add validate message in label
                            let label =  $('label[id="'+value.name+'-error"]')
                            if (label.length === 0){
                                //create label if not found
                                input.parent().append('<label id="'+value.name+'-error" class="is-invalid invalid-feedback" for="'+value.name+'">'+errorMessage+'</label>')
                            }else {
                                //update text if found
                                label.removeAttr('style')
                                label.html(errorMessage)
                            }
                        });
                    }
                    toastr.error(errorMessage);
                }

            });
        }


    });

}


//get data from form and convert data to object
function getFormDataAsObject(formId) {
    let formObj = {};
    let inputs = $('#' + formId).serializeArray();
    $.each(inputs, function (i, input) {
        formObj[input.name] = input.value;
    });
    return formObj;
}


function generateUserName() {
    var numbers = [0, 1];
    var selected_number = numbers[Math.floor(Math.random() * numbers.length)];
    var name = $(".name").val().toLowerCase().replace('  ', ' ').split(" ");
    var year = (new Date).getFullYear();
    var number = parseInt(Math.random() * 100);
    var username = null;
    if (name.length >= 2) {
        if (selected_number === 1) {
             username = name[0] + name[1] + year + number + '@arabic-arabs.com';
        } else {
             username = name[0] + year + number + '@arabic-arabs.com';
        }
    } else {
        username = name[0] + year + number + '@arabic-arabs.com';
    }
    $(".username").val(username);
}

//select or unselect options
function onSelectAllClick(selectId) {
    let select = $('#' + selectId);
    //select all when all is clicked
    $(select).on("select2:clearing", function (e) {
        e.preventDefault();
        //console.log('sc')
        //$('#'+selectId+' option[value="all"]').prop('selected',false).trigger('change')
        // let options = $(select).find('option');
        // options.each(function () {
        //     $(this).trigger('unselect', {
        //         data: $(this).data('data')
        //     });
        // })
        //$(select).val([])
        // $(select).trigger('change');

    })
    $(select).on("select2:select select2:unselect", function (e) {
        //e.preventDefault()
        let data = e.params.data;
        console.log(e.params)
        let options = $(select).find('option');
        if (data.id === 'all') {
            if (data.selected) { //select
                options.each(function () {
                    $(this).prop('selected', true)
                })
                $(select).trigger('change');
            } else { //unselect
                $(select).val([])
                $(select).trigger('change');
            }
        }

    });

}
