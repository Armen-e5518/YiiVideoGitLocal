$(document).ready(function () {

    $(document).on("click", ".field-block", function (e) {
        e.stopPropagation();
    })
    $(document).on("click", "body", function (e) {
        if(!ProcessRun()){
           $('.Change-swimsuits').css('background','rgb(217, 83, 79)')
        }else {
            $('.Change-swimsuits').css('background','rgb(255, 255, 255)')
        }
    });

    $(document).on("click", ".popup-info .btn-danger", function (e) {
        $('.popup-info').hide();
        e.stopPropagation();
    });

    $(document).on("click", ".popup-number button", function (e) {
        $('.popup-number').hide();
        e.stopPropagation();
    });

    $('.popup-colors button').click(function () {
        $('.popup-colors').hide();
    });
    $('.popup-line-up button').click(function () {
        $('.popup-line-up').hide();
    });
    $('.popup-enregistrer button').click(function () {
        $('.popup-enregistrer').hide();
    });
    $(document).on("click", ".popup-info .btn-success", function (e) {
        if ($('.modify-positions').attr("d-type") == "save") {
            setPositionsPlayers();
            $(".dragdrop1").draggable('disable');
            $(".dragdrop2").draggable('disable');
            Dragdrop();
            ModifyPositionsRemove($('.modify-positions'))
        }
        if ($('.Change-swimsuits').attr("d-type") == "show") {
            setPositionsPlayers();
            HideGoalPosition();
            $(".replacements").hide();
            $('.Change-swimsuits').attr("d-type", "hide")
            $('.Change-swimsuits').html("Remplacements")
            $(".player").removeClass("dragdrop1");
            $(".player").removeClass("dragdrop2");
            $(".dragdrop1").draggable('disable');
            $(".dragdrop2").draggable('disable');
            Dragdrop();
        }
        if($('.Change-number').attr("d-type") == "show"){
            $(".player .player-number").removeClass('change');
            $('.Change-number').attr("d-type", "hide")
            $('.Change-number').html("Changer number")
        }
    });

    $(document).on("click", ".popup-delete .btn-success", function (e) {
        var id = $(this).attr('data-id');
        __DeleteEvent = true;
        $(".event-row[event-id='" + id + "']").find('.delete').trigger('click');
    });

    $(document).on("click", ".popup-delete .btn-danger", function (e) {
        $('.popup-delete').hide();
    })

});
