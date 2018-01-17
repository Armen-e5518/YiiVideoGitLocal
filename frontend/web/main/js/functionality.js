$(".event-column tbody").on("click", ".event-row", function () {
    $(".event-column tbody .event-row").removeClass('new-event');
    $(this).addClass('new-event');
    PenaltyTestAndSet()
    InActiveRemove();
});

$(".statistics").mouseup(function () {
    var flag = true;
    if (!ProcessRun()) {
        flag = false;
        $('.popup-enregistrer').show();
        return false;
    }
    if ($('#colorSelectorLeft div').css('background-color') == 'rgba(0, 0, 0, 0)' || $('#colorSelectorRight div').css('background-color') == 'rgba(0, 0, 0, 0)') {
        flag = false;
        $('.popup-colors').show()
        return false;
    }
    if (!CheckPlayerNumber()) {
        flag = false;
        $('.popup-number').show();
        return false;
    }
    if (!LineUpPos) {
        flag = false;
        $('.popup-line-up').show();
        return false;
    }
    if (flag) {
        $(".popup-m").show();
    }
});

$(".popup-m").mouseup(function () {
    $(this).hide()
});

$(".popup-m .content-pop button.btn-success").mouseup(function (event) {
    var data = {};
    data.match_id = getParams('id')
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/change-status-match",
        data: data,
        success: function (res) {
            if (res == 1) {
                var href = location.protocol + "//" + document.domain + '/site/match-event?id=' + MatchId;
                window.location.href = href;
            } else {
                $(".popup-m").hide();
            }
        }
    });
    event.stopPropagation()
});

$(".modify-positions").mouseup(function () {
    if ($(this).attr("d-type") == "modify") {
        ModifyPositionsStart($(this))
        Dragdrop();
        $(".dragdrop1").draggable('enable');
        $(".dragdrop2").draggable('enable');
        // $(".layer").hide();
    } else {
        setPositionsPlayers();
        $(".dragdrop1").draggable('disable');
        $(".dragdrop2").draggable('disable');
        Dragdrop();
        ModifyPositionsRemove($(this))
        // $(".layer").show();
    }
});

$(".save-main-pos").mouseup(function () {
    setMainPositionsPlayers();
});

$(".Last-Sequence button").mouseup(function () {
    ReturnToLastEventTime(true, true)
});

$(".event-column tbody").on("click", ".delete", function (event) {
    var ob = $(this).parent().parent().parent();
    if (__DeleteEvent) {
        InActiveRemove();
        InActiveButtons("R")
        var data = {};
        data.event_id = ob.attr('event-id');
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/ajax/delete-event",
            data: data,
            success: function (res) {
                if (res == 1) {
                    ob.remove();
                    CheckEventsList();
                    UpdateIndexTable();
                    $('.popup-delete').hide();
                }
            }
        });
        __DeleteEvent = false;
        event.stopPropagation()
    } else {
        var event_id = ob.attr('event-id');
        $('.popup-delete').show();
        $('.popup-delete .btn-success').attr('data-id', event_id);
    }
});

$(".cancel button").mouseup(function (event) {
    $('.goal-info').hide();
    InActiveRemove();
    InActiveButtons("R")
    var data = {};
    var ob = $(".event-column .new-event:first");
    data.event_id = ob.attr('event-id');
    if (ob.attr('class')) {
        Empty();
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/ajax/empty-event",
            data: data,
            success: function (res) {
                if (res == 1) {
                    ob.children('.event-player-d').children('.team-anem').html('')
                    ob.children('.event-player-d').children('.event-player-name').html('')
                    ob.children('.event-name').html('')
                    Anuler = true;
                    InActiveRemove()
                }
            }
        })
    }
    event.stopPropagation()
});

$(".Invert-land").mouseup(function () {
    setPositionsPlayers();
    $(".field .field-item").css("border", "none")
    ModifyPositionsRemove($(".modify-positions"));
    ReversLogos($(".img-logo-left"), $(".img-logo-right"))
    ReversLogos($('.player-left'), $('.player-right'));
    ReversLogos($('#colorSelectorLeft'), $('#colorSelectorRight'));
    ReverseField();
});

$(".time-played button").mouseup(function () {
    SetTimButton($(".time-played button"))
});

$(".title-1").click(function (e) {
    $(this).addClass("active")
    $(".title-2").removeClass("active")
    $(".command-1").show();
    $(".command-2").hide();
    $(".command-1 .replacements-item-add").attr("id", "open");
    $(".command-2 .replacements-item-add").attr("id", "closed");
    e.stopPropagation();
});

$(".title-2").click(function (e) {
    $(this).addClass("active")
    $(".title-1").removeClass("active");
    $(".command-2").show();
    $(".command-1").hide();
    $(".command-1 .replacements-item-add").attr("id", "closed");
    $(".command-2 .replacements-item-add").attr("id", "open");
    e.stopPropagation();
});

$(document).on("click", ".star", function () {
    Favorite($(this))

});

$(".Change-swimsuits").mouseup(function () {
    $('.Change-swimsuits').css('background','rgb(255, 255, 255)')
    setPositionsPlayers();
    HideGoalPosition();
    if ($(this).attr("d-type") == "hide") {
        $(".replacements").show();
        $(this).attr("d-type", "show")
        $(this).html("Enregistrer")
        $(".field .Team1, .replacements .Team1").addClass("dragdrop1");
        $(".field .Team2, .replacements .Team2").addClass("dragdrop2");
        Dragdrop();
        $(".dragdrop1").draggable('enable');
        $(".dragdrop2").draggable('enable');
    } else {
        $(".replacements").hide();
        $(this).attr("d-type", "hide")
        $(this).html("Remplacements")
        $(".player").removeClass("dragdrop1");
        $(".player").removeClass("dragdrop2");
        $(".dragdrop1").draggable('disable');
        $(".dragdrop2").draggable('disable');
        Dragdrop();
    }
});

$(document).on("click", ".player .change", function (event) {
    $('.p-popup').hide();
    $(this).prev('.p-popup').show();
    var number = $(this).children('span').html();
    $(this).prev('.p-popup').children('input').val(number)
    event.stopPropagation()
});

$(document).on("click", ".p-popup input", function (event) {
    event.stopPropagation()
});

$(document).on("click", "body", function (event) {
    $('.p-popup').hide();
});

$(document).on("click", ".p-number-save", function (event) {
    var ob = $(this);
    var data = {};
    data.number = $(this).prev('input').val();
    data.player_id = $(this).parent().parent().attr('data-id');
    data.match_id = MatchId;
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/add-player-number",
        data: data,
        success: function (res) {
            if (res == 1) {
                ob.parent().next('.player-number').children('span').html(data.number)
                ob.parent().hide()
            }
        }
    });
    event.stopPropagation()
});

$(".Change-number").mouseup(function () {
    if ($(this).attr("d-type") == "hide") {
        $(".field .player .player-number").addClass('change');
        $(".replacements .player .player-number").addClass('change');
        $(this).attr("d-type", "show")
        $(this).html("Enregistrer")
    } else {
        $(".player .player-number").removeClass('change');
        $(this).attr("d-type", "hide")
        $(this).html("Changer number")
    }
});

$(".field-position").hover(function () {
    $(".field-position").css("border", "1px solid");
});

$(".field-position").mouseout(function () {
    $(".field-position").css("border", "none");
});

$(".field-position").mouseup(function () {
    if ($(this).hasClass('position-active')) {
        $(this).removeClass('position-active')
    } else {
        $(".field-position").removeClass("position-active");
        $(this).addClass('position-active')
    }
});

$(".field-part-position").hover(function () {
    $(".field-part-position").css("border", "1px solid");
});

$(".field-part-position").mouseout(function () {
    $(".field-part-position").css("border", "none");
})

$(".field-part-position").mouseup(function () {
    if ($(this).hasClass('part-positions-active')) {
        $(this).removeClass('part-positions-active')
    } else {
        $(".field-part-position").removeClass("part-positions-active");
        $(this).addClass('part-positions-active')
    }
})

$(".additional-field-position").hover(function () {
    $(".additional-field-position").css("border", "1px solid");
});

$(".additional-field-position").mouseout(function () {
    $(".additional-field-position").css("border", "none");
})

$(".additional-field-position").mouseup(function () {
    if ($(this).hasClass('position-active')) {
        $(this).removeClass('position-active')
    } else {
        $(".additional-field-position").removeClass("position-active");
        $(this).addClass('position-active')
    }
    // $(".additional-field-position").removeClass("position-active");
    // $(this).addClass("position-active");
})

$(".additional-actions-list").change(function () {
    if ($(this).val() == "") {
        $(".additional-action-hide").hide();
    } else {
        $(".additional-action-hide").show();
        $("html,body").animate({scrollTop: 1000}, "slow");
        var player1_id = $("#player-1").val();
        $('#sec-player-1 option[value="' + player1_id + '"]').prop('selected', true);
    }
})

$('.pop-video').click(function (event) {
    event.stopPropagation()
})

$('.hide-video').click(function () {
    ClosePopupVideo($(this))
})

$(".event-column").on("click", ".map-marker-l", function (event) {
    SetVideo($(this))
    event.stopPropagation()
})

$("body").on("click", ".popup-video", function () {
    ClosePopupVideo($(this))
})

function Favorite(ob) {
    var data = {};
    var p_ob = ob.parent().parent().parent();
    data.event_id = p_ob.attr('event-id');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/favorite-event",
        data: data,
        success: function (res) {
            if (res == 1) {
                ob.attr("type-d", "disabled")
                ob.removeClass("enable");
            }
            if (res == 2) {
                ob.attr("type-d", "enable")
                ob.addClass("enable");
            }
        }
    })
}

function ClosePopupVideo(ob) {
    video_popup.pause();
    $('.popup-video').hide();
}

function SetVideo(ob) {
    var time = ob.parent().parent().parent().attr('data-time_video');
    $('.popup-video').show();
    video_popup.currentTime(time - 7);
    video_popup.play();
}

function SaveColorTema() {
    var data = {};
    data.match_id = MatchId;
    data.color_1 = $('.team1').find('.colorpicker_hex input').val();
    data.color_2 = $('.team2').find('.colorpicker_hex input').val();
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/save-team-color",
        data: data,
        success: function (res) {
        }
    })
}

function CheckEventsList() {
    var event = $('.event-column tbody tr').length
    if (event == 0) {
        location.reload();
    }
}