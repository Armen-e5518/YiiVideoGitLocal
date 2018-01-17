var fromOb = {};
var toOb = {};
var Src = {};

$(document).on("mousedown", ".field .player, .replacements .player", function () {
    fromOb.pos = $(this).parent().attr('data-index')
});
$(document).on("click", ".delete-ev", function (e) {
    var ob = $(this).parent();
    var data = {};
    data.match_id = MatchId;
    data.id = $(this).parent().attr('data-id');
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/delete-player-event",
        data: data,
        success: function (res) {
            if (res) {
                ob.remove();
                if (!$('.delete-ev').length) {
                    ob.hide();
                    ob.attr('data-type', 'hide')
                    ob.attr('class', 'glyphicon glyphicon-menu-left')
                }
            }
        }
    });
    e.stopPropagation();
});
function AddEventHtml(from, to) {
    if (!ProcessRun()) {
        console.log(Timer_time);
        if (from.player_id && to.player_id && !from.pos && to.pos && (from.player_id != to.player_id)) {
            PlayerEventsShow();
            Src = {};
            Src.player = to.player_id;
            Src.player_name = from.player_name;
            Src.sub_player = from.player_id;
            Src.sub_player_name = to.player_name;
            Src.pos_to = to.pos;
            Src.time = Timer_time;
            Src.half_time = played_T;
            var html =
                '<div class="pl-event" >' +
                '<span class="player-name">' + to.player_name + '</span>' +
                '<span class="line-to">-></span>' +
                '<span class="sub-player-name">' + from.player_name + '</span>' +
                '<span class="pos-from"> </span>' +
                '<span class="pos-from">-></span>' +
                '<span class="pos-to">' + to.pos + '</span>' +
                '<span class="cursor delete-ev glyphicon glyphicon-remove-sign"></span>' +
                '</div>';
            SavePlayerEvents(Src, html)

        } else if (from.player_id && to.player_id && from.pos && !to.pos && (from.player_id != to.player_id)) {
            PlayerEventsShow();
            Src = {};
            Src.player = from.player_id;
            Src.player_name = from.player_name;
            Src.sub_player = to.player_id;
            Src.sub_player_name = to.player_name;
            Src.pos_to = from.pos;
            Src.time = Timer_time;
            Src.half_time = played_T;
            var html =
                '<div class="pl-event" >' +
                '<span class="player-name">' + from.player_name + '</span>' +
                '<span class="line-to">-></span>' +
                '<span class="sub-player-name">' + to.player_name + '</span>' +
                '<span class="pos-from"> </span>' +
                '<span class="pos-from">-></span>' +
                '<span class="pos-to">' + from.pos + '</span>' +
                '<span class="cursor delete-ev glyphicon glyphicon-remove-sign"></span>' +
                '</div>'

            SavePlayerEvents(Src, html)
        } else if (from.player_id && to.player_id && from.pos && to.pos && (from.player_id != to.player_id)) {
            PlayerEventsShow();
            Src = {};
            Src.player = from.player_id;
            Src.player_name = from.player_name;
            Src.pos_from = from.pos;
            Src.pos_to = to.pos;
            Src.time = Timer_time;
            Src.half_time = played_T;
            var html =
                '<div class="pl-event" >' +
                '<span class="player-name">' + from.player_name + '</span>' +
                '<span class="sub-player-name"></span>' +
                '<span class="pos-from"> ' + from.pos + '</span>' +
                '<span class="pos-from">-></span>' +
                '<span class="pos-to">' + to.pos + '</span>' +
                '<span class="cursor delete-ev glyphicon glyphicon-remove-sign"></span>' +
                '</div>';
            SavePlayerEvents(Src, html);
            Src = {};
            Src.player = to.player_id;
            Src.player_name = to.player_name;
            Src.pos_from = to.pos;
            Src.pos_to = from.pos;
            Src.time = Timer_time;
            Src.half_time = played_T;
            var html =
                '<div class="pl-event" >' +
                '<span class="player-name">' + to.player_name + '</span>' +
                '<span class="sub-player-name"></span>' +
                '<span class="pos-from"> ' + to.pos + '</span>' +
                '<span class="pos-from">-></span>' +
                '<span class="pos-to">' + from.pos + '</span>' +
                '<span class="cursor delete-ev glyphicon glyphicon-remove-sign"></span>' +
                '</div>';
            SavePlayerEvents(Src, html)
        } else if (from.player_id && !to.player_id && from.pos && to.pos) {
            PlayerEventsShow();
            Src = {};
            Src.player = from.player_id;
            Src.player_name = to.player_name;
            Src.pos_from = from.pos;
            Src.pos_to = to.pos;
            Src.time = Timer_time;
            Src.half_time = played_T;
            var html =
                '<div class="pl-event" >' +
                '<span class="player-name">' + from.player_name + '</span>' +
                '<span class="sub-player-name"></span>' +
                '<span class="pos-from"> ' + from.pos + '</span>' +
                '<span class="pos-from">-></span>' +
                '<span class="pos-to">' + to.pos + '</span>' +
                '<span class="cursor delete-ev glyphicon glyphicon-remove-sign"></span>' +
                '</div>';
            SavePlayerEvents(Src, html)
        } else if (from.player_id && !to.player_id && from.pos && !to.pos && to.exit_player) {
            PlayerEventsShow();
            Src = {};
            Src.player = from.player_id;
            Src.player_name = to.player_name;
            Src.pos_from = from.pos;
            Src.time = Timer_time;
            Src.half_time = played_T;
            var html =
                '<div class="pl-event" >' +
                '<span class="player-name">' + from.player_name + '</span>' +
                '<span class="sub-player-name"></span>' +
                '<span class="pos-from"> ' + from.pos + '</span>' +
                '<span class="pos-from">-></span>' +
                '<span class="pos-to"></span>' +
                '<span class="cursor delete-ev glyphicon glyphicon-remove-sign"></span>' +
                '</div>';
            SavePlayerEvents(Src, html)
        } else if (from.player_id && !to.player_id && !from.pos && to.pos) {
            PlayerEventsShow();
            Src = {};
            Src.player = from.player_id;
            Src.player_name = to.player_name;
            Src.pos_to = to.pos;
            Src.time = Timer_time;
            Src.half_time = played_T;
            var html =
                '<div class="pl-event">' +
                '<span class="player-name">' + from.player_name + '</span>' +
                '<span class="sub-player-name"></span>' +
                '<span class="pos-from"> </span>' +
                '<span class="pos-from">-></span>' +
                '<span class="pos-to">' + to.pos + '</span>' +
                '<span class="cursor delete-ev glyphicon glyphicon-remove-sign"></span>' +
                '</div>';
            SavePlayerEvents(Src, html)
        }
    }
}
function PlayerEventsShow() {
    var ob = $('.open-pop span');
    $('.player-events').show();
    ob.attr('data-type', 'show');
    ob.attr('class', 'glyphicon glyphicon-menu-right')

}
function PlayerEventsHide() {
    var ob = $('.open-pop span');
    $('.player-events').hide();
    ob.attr('data-type', 'hide');
    ob.attr('class', 'glyphicon glyphicon-menu-left')
}
function SavePlayerEvents(Src, html) {
    var data = {};
    data.event = Src;
    data.id = MatchId;
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "/ajax/save-player-events",
        data: data,
        success: function (res) {
            if (res) {
                $('.pl-events').prepend(
                    html
                );
                $('.pl-event:first').attr('data-id', res)
            }
        }
    })
}
Dragdrop();
function Dragdrop() {
    if (!EndMatch) {
        $.fn.swap = function (b) {
            b = jQuery(b)[0];
            var a = this[0];
            var t = a.parentNode.insertBefore(document.createTextNode(''), a);
            b.parentNode.insertBefore(a, b);
            t.parentNode.insertBefore(b, t);
            t.parentNode.removeChild(t);
            return this;
        };
        $(".dragdrop2").draggable({
            revert: false,
            helper: "clone",
            drag: function () {
                toOb = {};
                $(".field .field-item").css("border", " 1px solid #FFF");
            },
            stop: function (event, d) {
                fromOb.player_id = $(this).attr('data-id')
                fromOb.player_name = $(this).find('.player-name span').html()
                AddEventHtml(fromOb, toOb);


                $(".ui-state-active").removeClass("ui-state-active");
                $(".field .field-item").css("border", "none");
                if ($(event.target).parent().attr("class") == "replacements-item-add") {
                    var html_move = $(this)[0]['outerHTML'];
                    $("#open").after('<div class="replacements-item">' + html_move + '<div')
                    $("#open").html('<div class="empty dragdrop2 ui-draggable ui-draggable-handle ui-droppable"></div>');
                    Dragdrop();
                }
            }
        });
        $(".dragdrop2").droppable({
            accept: ".dragdrop2",
            activeClass: "ui-state-hover",
            hoverClass: "ui-state-active",
            drop: function (event, ui) {
                if ($(this).parent().attr('class') == 'replacements-item-add') {
                    toOb.exit_player = 1;
                }
                toOb.player_id = $(this).attr('data-id')
                toOb.pos = $(this).parent().attr('data-index')
                toOb.player_name = $(this).find('.player-name span').html()
                $(".field .field-item").css("border", "none");
                var draggable = ui.draggable,
                    droppable = $(this),
                    dragPos = draggable.position();
                draggable.swap(droppable);
            }
        });
        $(".dragdrop1").draggable({
            revert: false,
            helper: "clone",
            drag: function () {
                toOb = {};
                $(".field .field-item").css("border", " 1px solid #FFF");
            },
            stop: function (event) {
                fromOb.player_id = $(this).attr('data-id')
                fromOb.player_name = $(this).find('.player-name span').html()
                AddEventHtml(fromOb, toOb);
                $(".ui-state-active").removeClass("ui-state-active");
                $(".field .field-item").css("border", "none");
                if ($(event.target).parent().attr("class") == "replacements-item-add") {
                    var html_move = $(this)[0]['outerHTML'];
                    $("#open").after('<div class="replacements-item">' + html_move + '<div')
                    $("#open").html('<div class="empty dragdrop1 ui-draggable ui-draggable-handle ui-droppable"></div>');
                    Dragdrop();
                }
            }
        });
        $(".dragdrop1").droppable({
            accept: ".dragdrop1",
            activeClass: "ui-state-hover",
            hoverClass: "ui-state-active",
            drop: function (event, ui) {
                if ($(this).parent().attr('class') == 'replacements-item-add') {
                    toOb.exit_player = 1;
                }
                toOb.player_id = $(this).attr('data-id')
                toOb.pos = $(this).parent().attr('data-index')
                toOb.player_name = $(this).find('.player-name span').html()
                $(".field .field-item").css("border", "none");
                var draggable = ui.draggable,
                    droppable = $(this),
                    dragPos = draggable.position();
                draggable.swap(droppable);
            }
        });

    }
}


