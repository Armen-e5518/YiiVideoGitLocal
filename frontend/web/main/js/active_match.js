var data = {};
data.match_id = MatchId
$.ajax({
    type: 'POST',
    dataType: "json",
    url: "/ajax/check-active-match",
    data: data,
    success: function (res) {

    }
})