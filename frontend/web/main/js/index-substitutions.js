var player_from;
var player_to;


$(document).ready(function () {
    if (CheckSubstitutions) {
        $('.substitutions input').attr("disabled", true);
        $('.substitutions input:checked').closest('.replacements-item').addClass('substitutions-active');
    }
});

$(document).on('click', '.remove-substitutions', function () {
    if (confirm("Do you want to delete all substitutions?!") == true) {
        $('.replacements-item').removeClass('substitutions-active');
        $('.substitutions input').removeAttr("disabled").prop('checked', false);
        CheckSubstitutions = false;
    }
});


$('.command-1 .substitutions input').change(function () {
    var count = $('.command-1 .substitutions input:checkbox:checked').length;
    var ob = $(this);
    if (count > 5) {
        ob.parent().css('background', 'red');
        setTimeout(function () {
            ob.prop('checked', false);
            ob.parent().css('background', 'none');
        }, 200)
    }
})
$('.command-2 .substitutions input').change(function () {
    var count = $('.command-2 .substitutions input:checkbox:checked').length;
    var ob = $(this);
    if (count > 5) {
        ob.parent().css('background', 'red');
        setTimeout(function () {
            ob.prop('checked', false);
            ob.parent().css('background', 'none');
        }, 200)
    }
})

$('.Change-swimsuits').mouseup(function () {
    if ($(this).attr('d-type') == 'show' && !CheckSubstitutions) {
        // substitutions_t1 =
        var substitutions_t1 = $('.command-1 .substitutions input:checkbox:checked').map(function () {
            return this.value;
        }).get();
        var substitutions_t2 = $('.command-2 .substitutions input:checkbox:checked').map(function () {
            return this.value;
        }).get();
        var data = {};
        data.team1 = substitutions_t1;
        data.team2 = substitutions_t2;
        data.match_id = MatchId;
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/ajax/save-match-substitutions",
            data: data,
            success: function (res) {
                if (res) {
                    CheckSubstitutions = true;
                    $('.substitutions input').attr("disabled", true);
                    $('.substitutions input:checked').closest('.replacements-item').addClass('substitutions-active');
                }
            }
        })
    }
});

$('.command-1 , .command-2').scroll(function () {
    if ($(this).scrollTop() * 1 > 10) {
        $('.drag-here').hide();
    } else {
        $('.drag-here').show();
    }
})
$('.open-pop span').click(function () {
    if ($(this).attr('data-type') == 'hide') {
        $('.player-events').show();
        $(this).attr('data-type', 'show')
        $(this).attr('class', 'glyphicon glyphicon-menu-right')
    } else {
        $('.player-events').hide();
        $(this).attr('data-type', 'hide')
        $(this).attr('class', 'glyphicon glyphicon-menu-left')
    }
})
