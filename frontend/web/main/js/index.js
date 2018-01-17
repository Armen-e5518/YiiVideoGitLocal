var played_T = 1,
    start_T = false,
    Timer_time = 0,
    Timer_ob,
    SynchroneOb,
    Timeer_Start = false,
    Timer_status = 'Stop',
    MinTime = 0,
    MinTimeHalf = 0,
    continue_game = false,
    Anuler = false,
    Goal = false,
    video = videojs("video"),
    video_popup = videojs("video-popup"),
    __DeleteEvent = false;
//-------------------------------------------------//

video_popup.volume(0);

if (EndMatch) {
    $('.modify-positions').hide();
    $('.end-match').show();
    setTimeout(function () {
        video.pause();
    }, 500)

}
// if (MainPos == '1') {
//     $('.save-main-pos').hide();
// }

if ($("button[data-id='" + HalfTimeId + "']").attr('class')) {
    var HalfOb = $("button[data-id='" + HalfTimeId + "']");
} else {
    var HalfOb = $("button[data-id='" + StartId + "']");
}

var Ob_Border_time = $('.event-row .event-name:contains(' + HtmlHalfTimeStart + '):first');
if (Ob_Border_time.length > 0) {
    MinTimeHalf = Ob_Border_time.parent().attr('data-timer_time') * 1;
    if (MinTimeHalf == 0) {
        MinTime = Ob_Border_time.parent().attr('data-time_video') * 1;
    }
    if (MinTimeHalf == 2700) {
        MinTime = Ob_Border_time.parent().attr('data-time_video') * 1;
    }
    if (MinTimeHalf == 5400) {
        MinTime = Ob_Border_time.parent().attr('data-time_video') * 1;
    }
    if (MinTimeHalf == 6300) {
        MinTime = Ob_Border_time.parent().attr('data-time_video') * 1;
    }
}


setTimeout(function () {
    var video = videojs("video");
    video.volume(0);
    UpdateIndexTable();
    var timer_time = $(".event-column .event-row:first").data('timer_time');
    var time_video = $(".event-column .event-row:first").data('time_video');
    var played_time = $(".event-column .event-row .part en:first").html();
    var HtmlHalfTime = $(".event-column .event-row .event-name:first").html();
    if (HtmlHalfTime == HtmlHalfTimeEnd) {
        start_T = false;
        played_T = played_time * 1 + 1;
        if (played_T == 2) {
            Timer_time = 2700;
            MinTimeHalf = 2700;
        }
        if (played_T == 3) {
            Timer_time = 5400;
            MinTimeHalf = 5400;
        }
        if (played_T == 4) {
            Timer_time = 6300;
            MinTimeHalf = 6300;
            MinTimeHalf = 6300;
        }
        $(".time-clock").html(formatSecondsAsTime(Timer_time));
    } else {
        if (played_time) {
            played_T = played_time;
            start_T = true;
            $(".unknow-player").show();
            InActiveButtons("R");
            if (timer_time) {
                continue_game = true;
                Timer_time = timer_time;
                $(".time-clock").html(formatSecondsAsTime(Timer_time));
                Timer('start');
                SynchroneTime("Start")
            }
        }
    }
    if (!start_T) {
        InactiveAllButtons();
        HalfOb.attr('d-type', 'start');
        HalfOb.html(HtmlHalfTimeStart);
        HalfOb.attr("data-id", StartId);
    } else {
        HalfOb.attr('d-type', 'end');
        HalfOb.html(HtmlHalfTimeEnd);
        HalfOb.attr("data-id", HalfTimeId);
    }
    if (time_video * 1 >= 0) {
        var video = videojs("video");
        video.currentTime(time_video);
        video.play()
    }
}, 1);

$(".event button").mouseup(function () {
    var ob = $(this);
    var id = ob.attr('data-id');
    var event_type = ob.attr('event-type');
    var role = ob.attr('role');
    var open = ob.hasClass('inactive');
    if (role == "S" && !open) {
        if (event_type == 'event' && id == ExitId) {
            AddActive(".event button", ob);
            AddEvent("event", ob, true, true, true);
            InActiveRemove();
            InActiveButtons("S");
            InActiveButtons("E")
        } else if ((event_type == 'action') && (id == GoalCSCId || id == GoalId)) {
            AddActive(".event button", ob);
            AddEvent("event_update", ob, true);
            InActiveRemove();
            InActiveButtons("S");
            InActiveButtons("E");
            InActiveButtons("R");
            Goal = true;
        } else {
            AddActive(".event button", ob);
            AddEvent("event_update", ob, true);
            InActiveRemove();
            InActiveButtons("S");
            InActiveButtons("E");
        }
    } else if (role == 'R' && !open) {
        if (id == Penalty) {
            ShowGoalPosition();
        } else {
            HideGoalPosition();
        }
        // if ($('.field .player').hasClass('active') || $('.unknow-player .player').hasClass('active')) {
        AddActive(".event button", ob);
        AddEvent("event_update", ob, true);
        InActiveRemove();
        InActiveButtons("R");
        // }
    } else if (!open) {
        InActiveRemove();
        InActiveButtons("R");
        if (event_type == 'event' && id == ExitId) {
            AddActive(".event button", ob);
            AddEvent("event", ob, true, true, true);
        } else if ((event_type == 'event') && (id == HalfTimeId || id == StartId)) {
            InActiveButtons("R");
            AddActive(".event button", ob);
            AddEvent("event", ob, true, true, true);
        } else {
            AddActive(".event button", ob);
            AddEvent("event_update", ob, true);
        }
    }

});

$(document).on("mouseup", ".field .player,.unknow-player .player", function () {
    HideGoalPosition();
    if ($('.layer').attr('status') == 'hide') {
        if (ProcessRun()) {
            if (Anuler) {
                AddActive('.player', $(this));
                AddEvent("all_update", $(this));
            } else {
                AddActive('.player', $(this));
                AddEvent("event", $(this));
            }
        }
    }
});

$(".time-below button").mouseup(function () {
    var video = videojs("video");
    var curtime = video.currentTime();
    var time = $(this).data('time') * 1;
    video.currentTime(curtime + time);
    if (Timer_status == 'Start') {
        Timer_time = Timer_time + time;
        if (Timer_time < 0) {
            Timer_time = 0;
        }
    }

});

$(".time-up button").mouseup(function () {
    var video = videojs("video");
    var curtime = video.currentTime();
    var time = $(this).data('time') * 1;
    video.currentTime(curtime + time);
    if (Timer_status == 'Start') {
        Timer_time = Timer_time + time;
    }
});

$('body').bind('keypress', function (e) {
    if (e.keyCode == 32) {
        e.preventDefault();
        if (!start_T) {
            video = videojs("video");
            video.play();
            start_T = true;
        } else {
            video = videojs("video");
            video.pause();
            start_T = false;
        }
    }
});

$(".goal-info .g-box").mouseup(function () {
    if ($(this).hasClass('position-active')) {
        $(this).removeClass('position-active')
    } else {
        $(".goal-info .g-box").removeClass("position-active");
        $(this).addClass('position-active')
    }
})

$('.g-box').mouseup(function () {
    SavePenaltyPosition()
})

$('#colorSelectorLeft').ColorPicker({
    color: '#0000ff',
    onShow: function (colpkr) {
        $(colpkr).fadeIn(200);
        return false;
    },
    onHide: function (colpkr) {
        $(colpkr).fadeOut(200);
        $(colpkr).addClass('team1');
        SaveColorTema();
        return false;
    },
    onChange: function (hsb, hex, rgb) {
        $('#colorSelectorLeft div').css('backgroundColor', '#' + hex);
        $('#colorSelectorLeft').attr('color', hex);
    },
});

$('#colorSelectorRight').ColorPicker({
    color: '#0000ff',
    onShow: function (colpkr) {
        $(colpkr).fadeIn(200);
        return false;
    },
    onHide: function (colpkr) {
        $(colpkr).fadeOut(200);
        $(colpkr).addClass('team2');
        SaveColorTema()
        var color = $(colpkr).find('.colorpicker_hex input').val();
        return false;
    },
    onChange: function (hsb, hex, rgb) {
        $('#colorSelectorRight div').css('backgroundColor', '#' + hex);
        $('#colorSelectorRight div').attr('color', hex);
    }
});

$('._6gb').trigger('click')