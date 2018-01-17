var from = 30,
    to = 30,
    myPlayer = videojs('preview-player'),
    S1_Min = ((time_video - from - time_dow) > 0) ? time_video - from - time_dow : 0,
    S1_Max = time_video + to + time_up;

if (!S1_From) {
    S1_From = ((time_video - time_dow) > 0) ? time_video - time_dow : 0;
}
if (!S1_To) {
    S1_To = time_video + time_up;
}


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
        myPlayer.currentTime(data.from)
        S1_From = data.from;
        S1_To = data.to;
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
videojs("preview-player").ready(function () {
    var myPlayer = this;
    myPlayer.currentTime(S1_From);
    myPlayer.on('timeupdate', function (e) {
        if ($(".event-active").hasClass('end-m')) {
            var sources = [{"type": "video/youtube", "src": " "}];
            myPlayer.pause();
            myPlayer.src(sources);
            $(".slider-v").hide();
        } else {
            var currentTime = myPlayer.currentTime();
            VideoSlider.update({
                from: currentTime
            });
            if (currentTime >= S1_To) {
                myPlayer.currentTime(S1_From);
            }
            if (currentTime < S1_From) {
                myPlayer.currentTime(S1_From);
            }
        }
    });
});


