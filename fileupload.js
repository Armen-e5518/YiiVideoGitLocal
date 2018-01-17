  //todo report file upload
    $(document).on('change', '#fideo-file', function (e) {
        $('.error-msg').remove();
        var fileObj = $(this);
        var isValid = true;
        if ($.trim($(this).val()).length) {


            var file_data = $(this).prop('files');


            if (file_data[0].type != 'video/mp4') {
                isValid = false;
                currentFileBtn.after('<div class="error-msg">Invalid file type</div>')
            }

            if (file_data[0].size > VIDEO_UPLOAD_SIZE) {
                isValid = false;
                currentFileBtn.after('<div class="error-msg">Large file size</div>')
            }

            var form_data = new FormData();

            if (isValid) {


                form_data.append('file', file_data[0]);
                $.ajax({
                    headers: {
                        'Authorization': "Basic " + btoa("abced:becd")
                    },
                    //url: '/upload.php?width=1024&height=768', // point to server-side PHP script
                    url: '/report/file_upload/' + sessionId, // point to server-side PHP script
                    dataType: 'json',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    beforeSend: function (data) {
                        $('#video-progress-bar').fadeIn(300);
                    },
                    success: function (php_script_response) {
                        if (php_script_response.success == true) {

                        }
                        $('#video-progress-bar').fadeOut(300);
                    },
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload) {
                            // For handling the progress of the upload
                            myXhr.upload.addEventListener('progress', function (e) {
                                if (e.lengthComputable) {

                                    var max = e.total;
                                    var curentProcressStatus = e.loaded / max * 100;

                                    $('#video-progress-bar .progress').css({
                                        "width": curentProcressStatus + '%'
                                    });

                                    $('#video-progress-bar .progress-percent em').html(parseInt(curentProcressStatus));
                                }
                            }, false);
                        }
                        return myXhr;
                    }, error: function (data) {
                        $('#video-progress-bar').fadeOut(300);
                    }
                });
            }
        }
    });
