$(".menu-bar").click(function () {
    if($(this).attr('d-type') == 'show'){
        $(this).attr('d-type','hide');
        $('.heder-t').hide();
        $('.events-list').css('margin-top', '30px')
    }else {
        $(this).attr('d-type','show');
        $('.heder-t').show();
        $('.events-list').css('margin-top', '0px')
    }
})
