    'use strict';
    let dir = $("html").attr("dir");

    /*---------------------------------------------------
        Form validation
    ---------------------------------------------------*/

        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        });

    /*---------------------------------------------------
        Show Toastify
    ---------------------------------------------------*/

        function showToastify(text_msg, status){
            var color = "#203359";
            if(status == "success"){
                color = "#61C3BB";
            } else if(status == "error"){
                color = "#E37281";
            } else if(status == "warning"){
                color = "#E7AF4B";
            } else if(status == "info"){
                color = "#0F9DC2";
            } else {
                color = "#203359";
            }

            Toastify({
                text: text_msg,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: color,
                },
                onClick: function(){} // Callback after click
            }).showToast();
        }


    /*---------------------------------------------------
        countUp
    ---------------------------------------------------

        $('.count').countUp({
            'time': 2000,
            'delay': 15
        });
        */


    /*---------------------------------------------------
        green-audio-player
    ---------------------------------------------------*/

        // https://github.com/greghub/green-audio-player
        GreenAudioPlayer.init({
            selector: '.audio-player', // inits Green Audio Player on each audio container that has class "audio-player"
            stopOthersOnPlay: true,
            showTooltips: true,
        });

    /*---------------------------------------------------
        countdown
    ---------------------------------------------------*/

        var qnt = 1,
        val = qnt * 60 * 60 * 1000,
        selectedDate = new Date().valueOf() + val;

        $('#clock').countdown(selectedDate.toString())
        .on('update.countdown', function(event){
            var format = '%H:%M:%S';
            $(this).html(event.strftime(format));
            $("#timer-ago").val(event.strftime(format));
        //    localStorage.setItem("timer_val", event.offset.totalSeconds);
        })
        .on('finish.countdown', function(event) {
            $(this).parent().addClass('disabled').html('This offer has expired!');
            showToastify("The Time has expired!","error");
        });

    /*---------------------------------------------------
        word count
    ---------------------------------------------------*/

        $(document).on("keyup", ".textarea textarea", function(){

            var value = $.trim($(this).val()),
                count = value == '' ? 0 : value.split(' ').length;
            $(this).parent().find(".word-count span").text(count);
        });


    /*---------------------------------------------------
        btn-upload
    ---------------------------------------------------*/

        var i = 0;
        $(document).on("click", ".btn-upload-file", function(e){
            var partner_div = $(this).parent(),
                file_id		= $(this).attr("for");

            $("#"+file_id).change(function(e){
                var filename = e.target.files[0].name;
                var filesize = e.target.files[0].size;
            //	var fSExt = new Array('بايت', 'كيلو بايت', ' ميجا بايت', ' جيجا بايت'),
                var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
                    x = 0;
                    while(filesize > 900){
                        filesize /= 1024;
                        x++;
                    }
                    var exactSize = (Math.round(filesize*100)/100) +' '+ fSExt[x];

                var html = '\
                    <div class="files-upload-box" id="file_no_'+i+'">\
                        <div class="file-title">\
                            <div class="name"> '+ filename+'</div>\
                            <div class="action">\
                                <a href="#!" class="btn-option trash" onclick=remove_file('+i+')>\
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">\
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>\
                                    </svg>\
                                </a>\
                            </div>\
                        </div>\
                        <div class="file-size">'+ exactSize +'</div>\
                    </div>\
                ';
                partner_div.find(".files-upload").html(html);
            });
            i++;
        });

        function remove_file(i){
            $("#file_no_" + i).parent().parent().find(".form-control").val("");
            $("#file_no_" + i).remove();
        }

    /*---------------------------------------------------
        phone-input
    ---------------------------------------------------*/

        $('.phone-input').each(function(){
            var input = document.querySelector("#"+ this.id);
            var country = $("#"+ this.id).data("country");
            var iti = window.intlTelInput(input, {
                initialCountry: 'ae',
                // geoIpLookup: function(callback) {
                //     $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                //         var countryCode = (resp && resp.country) ? resp.country : "ae";
                //         callback(countryCode);
                //     });
                // },
                separateDialCode: true,
                utilsScript: "web_assets/intlTelInput/utils.js"
            });
        });

        function getPhoneKey(id){
            var input = document.querySelector('#'+ id);
            var iti = window.intlTelInputGlobals.getInstance(input);
            $('#'+ id + "-code").val(iti.getSelectedCountryData().dialCode);
            $('#'+ id + "-country").val(iti.getSelectedCountryData().iso2);
            $("#"+ id).attr("data-country",iti.getSelectedCountryData().iso2);
        }

    /*---------------------------------------------------
        form-range
    ---------------------------------------------------*/

        $(document).on("change",".form-range", function(){
            var range_val = $(this).val();
            $(this).parents(".form-group").find("#range_count").text(range_val);
        });


    /*---------------------------------------------------
        Change Pic User
    ---------------------------------------------------*/

    $('#remove_pic').hide();
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#profile-user-pic').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#change_pic").change(function(){
        if( $('#change_pic').val() != ""){
            $('#remove_pic').show();
        } else{
            $('#remove_pic').hide();
        }
        readURL(this);
    });

    $('#remove_pic').click(function(){
        $('#change_pic').val('');
        $(this).hide();
        $('#profile-user-pic').attr('src','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHfHdfcQ1cDWzgVLJr32Z3mVYq20D6pir7fKupEKB6fhvQWGZ5xVx76ydUx9nQJiJlKL0&usqp=CAU');
    });
