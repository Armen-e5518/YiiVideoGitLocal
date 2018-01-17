$('.save-video').click(function () {
   var url = $(".video_url").val();
   if(url){
       data = {};
       data.match_id = Match_id;
       data.src = url;
       $.ajax({
           type: 'POST',
           dataType: "json",
           url: "/ajax/save-match-video-url",
           data: data,
           success: function (res) {
               if (res == 1) {
                   var href = location.protocol + "//" + document.domain + '/site/match?id=' + Match_id;
                   window.location.href = href;
               }
           }
       })
   }
})