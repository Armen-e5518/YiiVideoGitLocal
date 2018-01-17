<?php

$this->title = 'Upload';
$this->registerCssFile("/css/upload.css");
$this->registerJsFile("https://code.jquery.com/jquery-3.1.1.min.js");
$this->registerJsFile("/js/upload.js");
$this->title = 'Upload Video';
?>

<div class="container hader">
    <div class="heder-t row">
        <div class="col-md-3">
            <a class="my-logo" href="/site">
                <img src="/main/images/icons/logo-mycoachfootball.png" alt="">
            </a>
        </div>
    </div>
    <div class="row upload-row">
        <div class="upload row">
            <div class="block col-md-12">
                <form action="/file/file-upload" id="myForm" method="post" name="upload" enctype="multipart/form-data">
                    <div class="col-md-4">
                        <p> Select video to upload</p>
                        <p class="or"> Or</p>
                    </div>
                    <div class="col-md-4 file-inp">
                        <input class="file" type="file" name="fileToUpload" id="fileToUpload">
                    </div>
                    <div class="col-md-4">
                        <input class="submit btn btn-info upload-video" type="submit" value="Upload Video" name="submit"/>
                    </div>
                    <input type="hidden" name="match" value="<?= $match_id ?>">
                </form>
                <div class="url-input">
                    <input type="text" class="video_url" name="video_url"  value="<?= $match['video_src'] ?>"placeholder="Video URL">
                    <input class="submit btn btn-info save-video" type="submit" value="Save Video" name="submit"/>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var Match_id = <?= $match_id ?>;
</script>