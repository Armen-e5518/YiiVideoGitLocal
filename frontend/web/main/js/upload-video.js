var data = {};
var count_a = 0;
var Count_check = 0;
data.match_id = MatchId;
CountStartEnd()
Upload()
$('.upload-v').click(function () {
    Upload()
})
$(document).on('click', '.delete-video', function () {
    var data = {};
    var ob = $(this).parent();
    data.url = $(this).parent().find('a').attr('href');
    data.id = $(this).parent().parent().attr('data-id');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/delete-video-in-server",
        data: data,
        success: function (res) {
            if(res){
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

$('body').click(function () {
    $('.popup-m').hide();
});

$('.delete-all-video').click(function (e) {
    $('.popup-m').show();
    e.stopPropagation()
});

function Upload() {
    count_a = 0;
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/get-events-by-match-upload",
        data: data,
        success: function (res) {
            VideoUpload(res)
        }
    })
}
function VideoUpload(res) {
    var count = res.length;
    var count_res = 0;
    var time = 2000;
    if (res != 0) {
        $('.img-loader').show();
        var SInter = setInterval(function () {
            count_res = 0;
            res.splice(0, 6).forEach(function (r) {
                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: "/ajax/upload-video",
                    data: r,
                    success: function (resp) {
                        if (resp.done == 1) {
                            var count_done = parseInt($(".done").html());
                            $(".done").html(count_done + 1)
                            $(".event-row[data-id='" + r.id + "']").children('.video-src').html('<a target="_blank" href="' + resp.d_url + '">' + resp.d_url + '</a> <span class="delete-video glyphicon glyphicon-trash"></span>')
                        }
                        count_res++;
                    },
                    complete: function () {
                        count_a++;
                        if (count_a == count) {
                            $('.img-loader').hide();
                            if ($('.done').html() == $('.all-count').html()) {
                                setTimeout(function () {
                                    alert('Toutes les vidéos ont été envoyées sur le serveur. Le traitement est terminé.')
                                }, 500)
                            } else {
                                Count_check++;
                                Upload()
                                if (Count_check == 3) {
                                    alert('Oops! problem occurred during uploading, please try again.')
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
        }, time)
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
    $(".all-count").html(count_done * 1 - Count_start * 1 - Count_end * 1)
}