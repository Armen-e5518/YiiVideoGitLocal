//-------------functions-------------------
/**
 *
 * @param event
 * @param ob
 * @param empty
 * @param brack
 * @param player
 * @constructor
 */
function AddEvent(event, ob, empty, brack, player) {
    if (!EndMatch) {
        var played_T_html = played_T;
        if (Goal) {
            Goal = false;
            InActiveRemove();
            InActiveButtons("R")
        }
        var video = videojs("video")
        var curtime = video.currentTime();
        var data = {};
        data.time = formatSecondsAsTime(Timer_time);
        data.time_game = Timer_time;
        data.time_video = curtime;
        data.action_id = (brack) ? ob.attr('data-id') : $(".event button.active").attr("data-id");
        data.player1_id = (!player) ? $(".field-item .active").data("id") : null;
        data.team_name = (!player) ? $(".field-item .active").data("team") : null;
        data.player_name = (!player) ? $(".active .player-name span").html() : null;
        data.played_T_hrml = played_T_html;
        data.event = event;
        data.event_type = (ob.attr("event-type")) ? ob.attr("event-type") : "null";
        data.event_name = (brack) ? ob.html() : $('.event .active').html();
        data.event_id = $(".new-event").attr("event-id")
        data.match_id = getParams('id');
        if (data.action_id == StartId) {
            var video = videojs("video")
            MinTime = video.currentTime();
        }
        // out(data)
        $.ajax({
            type: 'POST',
            dataType: Anuler ? "json" : "html",
            url: "/ajax/get-event-html-by-data",
            data: data,
            success: function (res) {
                if (Anuler) {
                    SetDataUpdate(res)
                    Anuler = false;
                } else {
                    ScrollBottom()
                    if (data.action_id == HalfTimeId) {
                        SetTimButton(HalfOb)
                        StartHalfTime('start')
                        SynchroneTime("Stop")
                    }
                    if (data.action_id == StartId) {
                        SetTimButton(HalfOb)
                        StartHalfTime('end')
                        SynchroneTime("Start")
                    }


                    if (empty) {
                        Empty()
                    }
                    if (event == 'event_update') {
                        $('.new-event .event-name').html(res)
                        Empty()
                    } else {
                        DeleteClassNew();
                        $(".event-column tbody").prepend(res.toString());
                        if (!ob.addClass(".player")) {
                            Empty()
                        }

                        UpdateIndexTable()
                    }
                }
            }
        })
    }
}
function SavePenaltyPosition() {
    var data = {};
    data.id = $('tr.new-event').attr('event-id');
    data.position = $('.goal .position-active').attr('position');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/save-goal-position",
        data: data,
        success: function (res) {
        }
    })
}
/**
 *
 * @param ob_name
 * @param ob
 * @constructor
 */
function AddActive(ob_name, ob) {
    $(ob_name).removeClass("active");
    if (!ob.hasClass('inactive')) {
        ob.addClass("active");
    }
}
/**
 *
 * @constructor
 */
function Empty() {
    $(".event button").removeClass("active");
    $(" .player").removeClass("active");
}
/**
 *
 * @param secs
 * @param format
 * @returns {string}
 */
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
/**
 *
 * @constructor
 */
function UpdateIndexTable() {
    var count = $(".event-column  tbody tr ").length;
    $(".event-column .event-row .event-number en").each(function (index) {
        $(this).html(count - index);
    });
    ScrollBottom()
}
/**
 *
 * @constructor
 */
function DeleteClassNew() {
    $(".event-column .new-event ").removeClass("new-event");
}
/**
 *
 * @param ob
 * @constructor
 */
function ModifyPositionsRemove(ob) {
    $(".player").css("cursor", "pointer");
    $(".field-item div").removeClass("dragdrop1");
    $(".field-item div").removeClass("dragdrop2");
    ob.html("Modifier les positions");
    ob.attr("d-type", "modify");
    $(".field .field-item").css("border", "none")
}
/**
 *
 * @param ob
 * @constructor
 */
function ModifyPositionsStart(ob) {
    $(".player").css("cursor", "move")
    $(".field .field-item .Team1").addClass("dragdrop1");
    $(".field .field-item .Team2").addClass("dragdrop2");
    $(".replacements-item .Team1 ").addClass("dragdrop1");
    $(".replacements-item .Team2 ").addClass("dragdrop2");
    ob.html("Enregistrer");
    ob.attr("d-type", "save");
}
/**
 *
 * @constructor
 */
function ReverseField() {
    for (var i = 1; i <= 20; i++) {
        var ob_idex2 = (41 - i).toString();
        var ob1 = $('[data-index=' + i + ']')
        var ob2 = $('[data-index=' + ob_idex2 + ']')
        var ob1_html = ob1['0']['outerHTML'];
        var ob2_html = ob2['0']['outerHTML'];
        ob1.after(ob2_html);
        ob2.after(ob1_html);
        ob1.remove();
        ob2.remove();
    }
}
/**
 *
 * @param ob1
 * @param ob2
 * @constructor
 */
function ReversLogos(ob1, ob2) {
    var html1 = ob1.html();
    var html2 = ob2.html();
    ob1.html(html2);
    ob2.html(html1);
}
/**
 *
 * @param role
 * @constructor
 */
function Timer(role) {
    if (role == "start") {
        Timer_status = 'Start';
        $(".time-clock").html(formatSecondsAsTime(Timer_time));
        if (!Timeer_Start) {
            Timeer_Start = true;
            Timer_ob = setInterval(function () {
                $(".time-clock").html(formatSecondsAsTime(Timer_time += 0.5));
            }, 500)
        }

    }
    if (role == "stop") {
        Timer_status = 'Stop';
        clearInterval(Timer_ob);
        Timeer_Start = false;
    }
    if (role == "reset") {
        clearInterval(Timer_ob)
        Timer_status = 'Stop'
        Timer_time = 0;
        var time = formatSecondsAsTime(Timer_time);
        $(".time-clock").html(time);
    }
}
/**
 *
 * @param role
 * @constructor
 */
function SynchroneTime(role) {
    if (role == "Start") {
        SynchroneOb = setInterval(function () {
            var currentTime = video.currentTime();
            if (currentTime < MinTime) {
                video.currentTime(MinTime);
            }
            var new_currentTime = video.currentTime();
            Timer_time = new_currentTime - MinTime + MinTimeHalf;
        }, 200);
    }
    if (role == "Stop") {
        clearInterval(SynchroneOb);
    }
}
/**
 * setPositionsPlayers
 */
function setPositionsPlayers() {
    var players = {};
    var arr = [
        getParams('id')
    ];
    for (var i = 1; i <= 40; i++) {
        var ob = $("div[data-index = '" + i + "'] div.player");
        if (ob.attr('class')) {
            players = {};
            players.pos = i;
            players.id = ob.attr('data-id');
            arr.push(players);
        }
    }
    if (arr) {
        ajaxSetPlayerPosityon(toObject(arr))
    }
}

function setMainPositionsPlayers() {
    var players = {};
    var arr = [
        getParams('id')
    ];
    for (var i = 1; i <= 40; i++) {
        var ob = $("div[data-index = '" + i + "'] div.player");
        if (ob.attr('class')) {
            players = {};
            players.pos = i;
            players.id = ob.attr('data-id');
            arr.push(players);
        }
    }
    if (arr) {
        ajaxSetMainPlayerPosityon(toObject(arr))
    }
}

function ajaxSetMainPlayerPosityon(data) {
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/save-player-main-positions",
        data: data,
        success: function (res) {
            if (res == true) {
                $('.save-main-pos').hide();
                LineUpPos = true;
            }
        }
    })
}
/**
 *
 * @param data
 */
function ajaxSetPlayerPosityon(data) {
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/save-player-positions",
        data: data,
        success: function (res) {
        }
    })
}
/**
 *
 * @param arr
 * @returns {{}}
 */
function toObject(arr) {
    var rv = {};
    for (var i = 0; i < arr.length; ++i)
        rv[i] = arr[i];
    return rv;
}

/**
 *
 * @param name
 * @returns {string}
 */
function getParams(name) {
    if (name = (new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)')).exec(location.search))
        return decodeURIComponent(name[1]);
}
/**
 *
 * @param vide
 * @param timer
 * @constructor
 */
function ReturnToLastEventTime(vide, timer) {
    var time_video = $(".event-column tbody tr:first").data("time_video");
    var time_event = $(".event-column tbody tr:first").data("timer_time");
    if (time_video) {
        var video = videojs("video")
        video.currentTime(time_video);
    }
    if (time_event) {
        SynchroneTime("Stop")
        Timer_time = time_event;
        var time = formatSecondsAsTime(Timer_time);
        $(".time-clock").html(time);
        SynchroneTime('Start')
    }
}
/**
 *
 * @param role
 * @constructor
 */
function InActiveButtons(role) {
    $("button[role='" + role + "']").addClass('inactive');
    HalfOb.removeClass('inactive');
    $("button[role='" + CartonId + "']").removeClass('inactive');
}
/**
 *
 * @constructor
 */
function InActiveRemove() {
    $(".event button").removeClass('inactive');
    $(".layer").hide();
    $(".layer").attr('status', 'hide');
    $(".unknow-player").show();
    $(".cancel").show();
}
/**
 *
 * @param ob
 * @constructor
 */
function SetTimButton(ob) {
    if (ob.attr("d-type") == "start") {
        start_T = true;
        ob.attr("d-type", "end");
        Timer("start")
    } else {
        InactiveAllButtons()
        Timer("stop")
        start_T = false;
        played_T++;
        if (played_T == 2) {
            Timer_time = 2700;
            MinTimeHalf = 2700;
        }
        if (played_T == 3) {
            Timer_time = 5400;
            MinTimeHalf = 5400
        }
        if (played_T == 4) {
            Timer_time = 6300;
            MinTimeHalf = 6300
        }
        if (played_T > 4) {
            played_T = 1;
            Timer("reset");
        }
        var time = formatSecondsAsTime(Timer_time);
        $(".time-clock").html(time);
    }
}
/**
 *
 * @constructor
 */
function InactiveAllButtons() {
    $(".event button").addClass('inactive');
    $(".layer").show();
    $(".layer").attr('status', 'show');
    $(".unknow-player").hide();
    $(".cancel").hide();
    HalfOb.removeClass('inactive')
}
/**
 *
 * @param x
 */
function out(x) {
    console.log(x);
}
/**
 *
 * @param role
 * @constructor
 */
function StartHalfTime(role) {
    if (role == 'start') {
        HalfOb.attr("d-type", "start");
        HalfOb.html(HtmlHalfTimeStart);
        HalfOb.attr("data-id", StartId);
    }
    if (role == 'end') {
        HalfOb.attr("d-type", "end");
        HalfOb.html(HtmlHalfTimeEnd);
        HalfOb.attr("data-id", HalfTimeId);
    }

}
/**
 *
 * @param data
 * @constructor
 */
function SetDataUpdate(data) {
    var ob = $(".event-column .new-event");
    ob.find('.team-anem').html(data.team_name);
    ob.find('.event-player-name').html(data.player_name);
    ob.find('.event-name').html(data.event_name);
}
function ProcessRun() {
    if ($('.modify-positions').attr('d-type') == 'modify'
        && $('.Change-swimsuits').attr('d-type') == 'hide'
        && $('.Change-number').attr('d-type') == 'hide'
    ) {
        return true
    }
    return false;
}
function ScrollBottom() {
    var ev = $('.events-list');
    ev.scrollTop(0);
}
function PenaltyTestAndSet() {
    if ($('tr.new-event .event-name').html() == PenaltyNameHtml) {
        ShowGoalPosition()
        var data = {};
        data.id = $('tr.new-event').attr('event-id');
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/ajax/get-goal-position",
            data: data,
            success: function (res) {
                SetGoalPositionHtml(res)
            }
        })
    } else {
        HideGoalPosition();
    }
}
function SetGoalPositionHtml(data) {
    $('.goal .g-box').removeClass('position-active')
    $('.goal .gpos-' + data).addClass('position-active');
}
function ShowGoalPosition() {
    $('.goal .g-box').removeClass('position-active')
    $('.goal-info').show();
    $('.replacements').hide();
}
function HideGoalPosition() {
    $('.goal-info').hide();
}
function ShowReplacements() {
    $('.goal-info').hide();
    $('.replacements').show();
}