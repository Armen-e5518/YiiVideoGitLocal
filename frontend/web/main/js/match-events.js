var Event_id = getParams('event')
var Match_id = getParams('id');
var video = videojs("preview-player", {
    fluid: true
});
video.play();
video.volume(0)
start_T = true;

var SliderStatus,
    from,
    to,
    S1_Min,
    S1_Max,
    S1_From,
    S1_To;

GetAllEventsByMatchAndIndexacion()

$("tr[data-id='" + Event_id + "']").addClass('event-active');

$(".e-action-name:contains('Fin  mi-temps')").parent().addClass('end-m');

SetGeolocation(full_geolocation, half_geolocation, sec_full_geolocation, sec_action_id);

ScrollPosition();

$(".event-column-advanced tbody").on("click", ".event-row-advanced", function () {
    SetEventData($(this))
    // $(".event-column-advanced tbody").on("click", ".event-row-advanced", function () {
    //     var ob = $(this);
    //     var event_id = ob.data('id');
    //     var match_id = ob.data('match');
    //     var href = location.protocol + "//" + document.domain + '/site/match-event?id=' + match_id + '&event=' + event_id;
    //     window.location.href = href;
    // });
//     var href = location.protocol + "//" + document.domain + '/site/match-event?id=' + match_id + '&event=' + event_id;
//     window.location.href = href;
});

$('.additional-player-list-search, .actions-list-search').change(function () {
    $("#search-s").submit()
});

$(".event-save-adv").click(function () {
    SaveEvent();
});


$(".delete-event").click(function (e) {
    if (confirm('Êtes vous sûrs de vouloir supprimer cet évènement?')) {
        DeleteEvent($(this));
        e.stopPropagation();
    }
});

$('body').bind('keypress', function (e) {
    if(e.keyCode == 111 || e.keyCode == 79){
        $('#result').val(1);
    }
    if(e.keyCode == 78 || e.keyCode == 110){
        $('#result').val(0);
    }
    if(e.keyCode == 114 || e.keyCode == 82){
        SaveEvent();
    }
});

$(".clone").click(function () {
    CloneEvent($(this))
});

$('body').bind('keypress', function (e) {
    PlayPause(e)
});

$('#action').change(function () {
    SetActionData($(this))
});

$(document).on("mouseover", ".irs-slider", function () {
    if ($(this).hasClass('from')) {
        SliderStatus = 'from'
    }
    if ($(this).hasClass('to')) {
        SliderStatus = 'to'
    }
});

$(".goal-info-event .g-box").mouseup(function () {
    if ($(this).hasClass('position-active')) {
        $(this).removeClass('position-active')
    } else {
        $(".goal-info-event .g-box").removeClass("position-active");
        $(this).addClass('position-active')
    }
});

$("#action").change(function () {
    if ($(this).val() == Penalty) {
        $('.goal .g-box').removeClass('position-active')
        $(".goal-info-event").show();
    } else {
        $('.goal .g-box').removeClass('position-active')
        $(".goal-info-event").hide();
    }
});

$("#range_1").ionRangeSlider({
    min: S1_Min,
    max: S1_Max,
    from: S1_From,
    to: S1_To,
    type: 'double',
    step: 1,
    prefix: "",
    prettify: false,
    hasGrid: true,
    onFinish: function (data) {
        video.currentTime(data.from)
        video.play()
        S1_From = data.from;
        S1_To = data.to;
    },
    onChange: function (data) {
        video.pause()
        if (SliderStatus == 'from') {
            video.currentTime(data.from)
        }
        if (SliderStatus == 'to') {
            video.currentTime(data.to)
        }
    }
});

$("#range_43").ionRangeSlider({
    type: "single",
    min: S1_Min,
    max: S1_Max,
    from: 0,
    keyboard: true,
});

var VideoSlider = $("#range_43").data("ionRangeSlider");

var VideoRang = $("#range_1").data("ionRangeSlider");

//-------------------------function---------------------

function SetActionData(ob) {
    var data = {};
    data.action_id = ob.val();
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/get-range-time-by-action-id",
        data: data,
        success: function (res) {
            if (res) {
                S1_From = time_video - res.time_before;
                S1_To = time_video + res.time_after;
                VideoRang.update({
                    min: S1_Min,
                    max: S1_Max,
                    from: S1_From,
                    to: S1_To,
                });
            }
        }
    })
}

function PlayPause(e) {
    if (e.keyCode == 32) {
        e.preventDefault();
        if (!start_T) {
            video = videojs("preview-player")
            video.play();
            start_T = true;
        } else {
            video = videojs("preview-player")
            video.pause();
            start_T = false;
        }
    }
}

function UpdateIndexTableEvents() {
    $(".event-column-advanced .event-row-advanced .event-number-advanced span en").each(function (index) {
        $(this).html(index * 1 + 1);
    });
}

function getParams(name) {
    if (name = (new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)')).exec(location.search))
        return decodeURIComponent(name[1]);
}

function out(x) {
    console.log(x);
}

function ScrollPosition() {
    var s = $('.event-active').offset();
    if (s) {
        $(".events").scrollTop(s.top - 450)
    }
}

function SaveEvent() {
    var loc_data = {};
    loc_data.player_name = GetPlayerName();
    loc_data.action_name = $("#action option:selected").html()
    $(".img-load").show();
    var data = {};
    data.id = $('.event-active').attr('data-id');
    data.match_id = Match_id;
    data.action_id = $("#action").val();
    data.player1_id = $("#player-1").val();
    data.player2_id = $("#player-2").val();
    // data.time_game = 0
    // data.time_video = 0
    data.success = $("#result").val();
    data.card_yellow = $(".cards-yellow input").prop("checked");
    data.card_red = $(".cards-red input").prop("checked");
    data.visible = $(".visible input").prop("checked");
    data.full_geolocation = $("#main-field .position-active").data('pos')
    data.half_geolocation = $(".field-part-positions .part-positions-active").data('pos')
    data.time_from = $(".js-irs-0 .irs-from").html().replace(/\s/g, "");
    data.time_to = $(".js-irs-0 .irs-to").html().replace(/\s/g, "");
    data.sec_action_id = $("#sec-action").val();
    data.sec_player1_id = $("#sec-player-1").val();
    data.sec_player2_id = $("#sec-player-2").val();
    data.sec_success = $("#sec-result").val();
    data.sec_full_geolocation = $("#sec-field .position-active").data('pos')
    data.goal_position = (data.action_id == Penalty) ? $('.goal .position-active').attr('position') : null;
    // out(data)
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/save-full-event",
        data: data,
        success: function (res) {
            $(".img-load").hide();
            if (res == 1) {
                SetIcons()
                $(".event-active .e-action-name").html(loc_data.action_name)
                $(".event-active .e-player-name").html(loc_data.player_name)
            }
        }
    })
}

function GetPlayerName() {
    var name = $("#player-1 option:selected").html()
    var res = name.split(")");
    return res[1]
}

function SetIcons() {
    if ($("#result").val() == "1") {
        $(".event-active .e-type").html(' <div class="event-type"></div>')
    } else if ($("#result").val() == "0") {
        $(".event-active .e-type").html(' <div class="event-info"></div>')
    } else {
        $(".event-active .e-type").html('')
    }
    if ($(".visible input").prop("checked")) {
        $(".event-active .e-eye div").hide();
    } else {
        $(".event-active .e-eye div").show();
    }
    if ($("#main-field .position-active").data('pos')) {
        $(".event-active .field-type div").show();
    } else {
        $(".event-active .field-type div").hide();
    }
    if ($(".field-part-positions .part-positions-active").data('pos')) {
        $(".event-active .half-field div").show();
    } else {
        $(".event-active .half-field div").hide();
    }
    if ($("#sec-action").val() == "") {
        $(".event-active .x2").html('')
    } else {
        $(".event-active .x2").html(' <div class="event-x2"></div>')
    }
}

function DeleteEvent(ob) {
    var active_ob = $(".event-active");
    var data = {};
    data.event_id = active_ob.data('id');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/delete-event",
        data: data,
        success: function (res) {
            if (res == 1) {
                active_ob.remove();
                GetAllEventsByMatchAndIndexacion();
            }
        }
    })
}

function CloneEvent(ob) {
    var active_ob = $(".event-active");
    var data = {};
    data.event_id = active_ob.data('id');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/clone-event",
        data: data,
        success: function (res) {
            if (res) {
                // out(res)
                active_ob.after(active_ob[0]['outerHTML'])
                $(".event-active:last").attr('data-id', res);
                $(".event-active:last").removeClass('event-active');
                GetAllEventsByMatchAndIndexacion();
            }
        }
    })
}

function SetEventData(ob) {
    setTimeout(function () {
        video.play();
        start_T = true;
    }, 500)
    $('.event-active').removeClass('event-active')
    ob.addClass('event-active')
    var data = {};
    data.event_id = ob.data('id')
    data.match_id = ob.data('match');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/get-event-data-by-id",
        data: data,
        success: function (res) {
            if (res != 0) {
                SetHtmlEventData(res)
            }
        }
    })
}

function SetHtmlEventData(data) {
    $('#action').val(data.action_id);
    $('#player-1').val(data.player1_id);
    $('#player-2').val(data.player2_id);
    $('#result').val(data.success);
    $('#sec-action').val(data.sec_action_id);
    $('#sec-player-1').val(data.sec_player1_id);
    $('#sec-player-2').val(data.sec_player2_id);
    $('#sec-result').val(data.sec_success);
    SetGeolocation(data.full_geolocation, data.half_geolocation, data.sec_full_geolocation, data.sec_action_id)
    if (data.action_id == Penalty) {
        SetGoalPositionHtmlEvent(data.goal_position);
    } else {
        $('.goal-info-event').hide();
    }

    if (data.card_red == '1') {
        $('.cards-red input').prop('checked', true);
    } else {
        $('.cards-red input').prop('checked', false);
    }
    if (data.card_yellow == '1') {
        $('.cards-yellow input').prop('checked', true);
    } else {
        $('.cards-yellow input').prop('checked', false);
    }
    if (data.visible == '1') {
        $('.visible input').prop('checked', true);
    } else {
        $('.visible input').prop('checked', false);
    }
    if (data.time_video) {
        time_video = data.time_video * 1;
        time_up = (data.time_before) ? data.time_before * 1 : 10;
        time_dow = (data.time_after) ? data.time_after * 1 : 10;
        S1_From = data.time_from * 1;
        S1_To = data.time_to * 1;
        SetSliderData();
        VideoRang.update({
            min: S1_Min,
            max: S1_Max,
            from: S1_From,
            to: S1_To,
        });
        VideoSlider.update({
            from: S1_From,
            min: S1_Min,
            max: S1_Max,
        });
        Init()
    }
}

function SetGeolocation(full_geolocation, half_geolocation, sec_full_geolocation, sec_action_id) {
    if (full_geolocation) {
        $("#main-field div").removeClass('position-active')
        $("#main-field div[data-pos='" + full_geolocation + "']").addClass('position-active')
    } else {
        $("#main-field div").removeClass('position-active')
    }
    if (half_geolocation) {
        $(".field-part-positions div").removeClass('part-positions-active')
        $(".field-part-positions div[data-pos='" + half_geolocation + "']").addClass('part-positions-active')
    } else {
        $(".field-part-positions div").removeClass('part-positions-active')
    }
    if (sec_full_geolocation) {
        $("#sec-field div").removeClass('position-active')
        $("#sec-field div[data-pos='" + sec_full_geolocation + "']").addClass('position-active')
    } else {
        $("#sec-field div").removeClass('position-active')
    }
    if (sec_action_id) {
        $('.additional-action-hide').show();
    } else {
        $(".additional-action-hide").hide();
    }
}
function SetSliderData() {
    from = 30,
        to = 30,
        S1_Min = ((time_video - from - time_dow) > 0) ? time_video - from - time_dow : 0,
        S1_Max = time_video + to + time_up;

    if (!S1_From) {
        S1_From = ((time_video - time_dow) > 0) ? time_video - time_dow : 0;
    }
    if (!S1_To) {
        S1_To = time_video + time_up;
    }
}

function Init() {
    videojs("preview-player").ready(function () {
        var video = this;
        video.currentTime(S1_From);
        video.on('timeupdate', function (e) {
            var currentTime = video.currentTime();
            VideoSlider.update({
                from: currentTime
            });
            if (currentTime >= S1_To) {
                video.currentTime(S1_From);
            }
            if (currentTime < S1_From) {
                video.currentTime(S1_From);
            }
        });
    });
}

function SetGoalPositionHtmlEvent(data) {
    $('.goal-info-event').show();
    $('.goal .g-box').removeClass('position-active')
    $('.goal .gpos-' + data).addClass('position-active');
}

function GetAllEventsByMatchAndIndexacion() {
    data = {};
    data.match_id = Match_id;
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/get-all-events-by-match",
        data: data,
        success: function (res) {
            if (res.length > 0) {
                UpdateIndexEvents(res)
            }
        }
    })
}

function UpdateIndexEvents(object) {
    out('Set data')
    $.each(object, function (index, value) {
        var ob = $("tr[data-id='" + value.id + "']");
        if (ob.length > 0) {
            ob.find('.event-number-advanced en').html(index + 1)
        }
    });
}