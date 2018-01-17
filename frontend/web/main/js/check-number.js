function CheckPlayerNumber() {
    var array_player_names = [];
    var flag = true;
    $('.event-column .event-player-name').each(function (e) {
        var player_name = $(this).html();
        if (player_name != '' && player_name != 'joueur inconnu') {
            if (array_player_names.indexOf(player_name) == -1) {
                array_player_names.push(player_name);
                var player_field_number = $('.field .player .player-name span:contains(' + player_name + ')').closest('.player').find('.player-number span').html();
                var player_replacements_number = $('.replacements  .player .player-name span:contains(' + player_name + ')').closest('.player').find('.player-number span').html();
                if (player_name != 'joueur inconnu') {
                    if (
                        (player_field_number == undefined
                        ||
                        player_field_number == '' )
                        &&
                        (player_replacements_number == ''
                        ||
                        player_replacements_number == undefined)
                    ) {
                        out(player_name);
                        flag = false;
                    }
                }
            }
        }
    });
    return flag;
}