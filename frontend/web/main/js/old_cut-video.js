var data = {};
var count_a = 0;
data.match_id = MatchId;
var Count_check = 0;
CountStartEnd();
Cut();

$('.cut-v').click(function () {
    CountStartEnd();
    Cut();
});

$('body').click(function () {
    $('.popup-m').hide();
});

$('.delete-all-video').click(function (e) {
    $('.popup-m').show();
    e.stopPropagation()
});

$(document).on('click', '.delete-video', function () {
    var data = {};
    var ob = $(this).parent();
    var url = '/ajax/delete-video-in-local';
    data.url = $(this).parent().find('a').html();
    if (data.url.indexOf('http') >= 0) {
        data.url = $(this).parent().find('a').attr('href');
        url = '/ajax/delete-video-in-server';
    }
    data.id = $(this).parent().parent().attr('data-id');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: url,
        data: data,
        success: function (res) {
            if (res) {
                ob.html('');
                var cont = $('.done').html() * 1 - 1;
                $('#upload-link').hide();
                $('.done').html(cont)
            }
        }
    })
});

$('.content-pop .btn-success').click(function (e) {
    var data = {};
    data.match_id = MatchId;
    $('.img-loader-del').show();
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/ajax/delete-video',
        data: data,
        success: function (res) {
            if (res) {
                $('.video-src').html('')
                $('.img-loader-del').hide();
                $('.done').html(0);
                $('#upload-link').hide();
            }
        }
    })
});

//----------------------------------------

function DownloadVideo() {
    console.log('Start Download');
    var data = {};
    data.match_id = MatchId;
    data.file_info = false;
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/ajax/download-video',
        data: data,
        // processData: false,
        success: function (res) {

            if (res) {
                // CountStartEnd();
                // Cut(res.url);
            }
        },
    })
    setInterval(function () {
        // DownloadVideoProgress()
    },5000)
}

function DownloadVideoProgress() {
    console.log('Start progress');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/ajax/download-video-progress',
        data: data,
        // processData: false,
        success: function (res) {
            if (res) {
                // CountStartEnd();
                // Cut(res.url);
            }
        },
    })
}

// function DownloadVideo() {
//     console.log('Start Download')
//     var data = {};
//     data.match_id = MatchId;
//     var that = this;
//     var page_url = '/ajax/download-video';
//
//     var req = new XMLHttpRequest();
//     req.open("POST", page_url, true);
//     req.onreadystatechange = function (e) {
//         console.log(e)
//     };
//     req.addEventListener("progress", function (evt) {
//         console.log(evt);
//         if (evt.lengthComputable) {
//             var percentComplete = evt.loaded / evt.total;
//             console.log(percentComplete);
//         }
//     }, false);
//
//     // req.responseType = "blob";
//
//     req.send();
// }

function progress(e) {

    if (e.lengthComputable) {
        var max = e.total;
        var current = e.loaded;

        var Percentage = (current * 100) / max;
        console.log(Percentage);


        if (Percentage >= 100) {
            console.log('END');
        }
    }
}

function Cut(file_phat) {
    count_a = 0;
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/get-events-by-match",
        data: data,
        success: function (res) {
            VideoCut(res, file_phat)
        }
    })
}

function VideoCut(res, file_phat) {
    var count = res.length;
    if (res != 0) {
        $('.img-loader').show();
        var SInter = setInterval(function () {
            res.splice(0, 6).forEach(function (r, index) {
                // r.m_video = file_phat;
                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: "/ajax/cut-video",
                    data: r,
                    success: function (resp) {
                        if (resp.done == 1) {
                            var count_done = parseInt($(".done").html());
                            $(".done").html(count_done + 1)
                            $(".event-row[data-id='" + r.id + "']").children('.video-src').html('<a target="_blank" href="' + Url + resp.d_url + '">' + resp.d_url + '</a><span class="delete-video glyphicon glyphicon-trash"></span>')
                        }
                    },
                    complete: function () {
                        count_a++;
                        if (count_a == count) {
                            $('.img-loader').hide();
                            if ($('.done').html() == $('.all-count').html()) {
                                $('#upload-link').show();
                            } else {
                                Count_check++;
                                if (Count_check == 1) {
                                    var error_event_ids = [];
                                    $('.event-row  .video-src').each(function () {
                                        if (!$.trim($(this).html()) && !$(this).closest('.event-row').find('th:contains("' + HtmlHalfTimeEnd + '")').length) {
                                            var error_event_id = $(this).closest('.event-row').find('th:first').html();
                                            error_event_ids.push(error_event_id);
                                        }
                                    });
                                    console.log(error_event_ids)
                                    var error_event_ids_strings = '';
                                    error_event_ids.forEach(function (val, index) {
                                        if (index == 0) {
                                            error_event_ids_strings += val;
                                        } else {
                                            error_event_ids_strings += ' , ' + val;
                                        }
                                    });
                                    alert('Oops! problem occurred during cutting, please try again.            Problèmes - id  (' + error_event_ids_strings + ')')
                                }
                            }
                        }
                    },
                    error: function (e) {
                        var c_error = $('.error-count').html();
                        $('.error-count').html(c_error * 1 + 1);
                    }
                })
            })
        }, 5000)
    }
}

function formatSecondsAsTime(secs, format) {
    secs = Math.floor(secs);
    var hr = Math.floor(secs / 3600);
    var min = Math.floor((secs - (hr * 3600)) / 60);
    var sec = Math.floor(secs - (hr * 3600) - (min * 60));
    if (min < 10) {
        min = "0" + min;
    }
    if (sec < 10) {
        sec = "0" + sec;
    }
    if (hr < 10) {
        hr = "0" + hr;
    }
    return hr + ':' + min + ':' + sec;
}

function CountStartEnd() {
    // var Count_start = $('.event-row  th:contains("' + HtmlHalfTimeStart + '")').length;
    var Count_start = 0;
    var Count_end = $('.event-row  th:contains("' + HtmlHalfTimeEnd + '")').length;
    var count_done = parseInt($(".all-count").html());
    $(".all-count").html(count_done * 1 - Count_start * 1 - Count_end * 1);
    if ($('.done').html() == $('.all-count').html()) {
        $('#upload-link').show();
    }
}
