<?php
use common\widgets\Alert;

$this->registerCssFile('/main/css/list.css');
$this->registerJsFile('/main/js/jquery/jquery-3.1.1.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/upload-video.js', ['position' => \yii\web\View::POS_END]);
$this->title = 'Upload Video';
//$this->params['breadcrumbs'][] = $this->title;
echo Alert::widget();
?>
<div class="popup-m">
    <div class="form-m">
        <div class="content-pop">
            <p>Do you want to delete all videos?</p>
            <button type="button" class="btn btn-success">Oui</button>
            <button type="button" class="btn btn-danger">Non</button>
        </div>
    </div>
</div>
<div class="container hader">
    <div class="heder-t row">
        <div class="col-md-3">
            <a class="my-logo" href="/site">
                <img src="/main/images/icons/logo-mycoachfootball.png" alt="">
            </a>
        </div>
        <div class="count-v col-md-6">
            Envoie des vidéos en cours
            <span class="done"><?= $events_done ?></span> sur <span class="all-count">
                <?= $events_count ?>
            </span>
        </div>
        <a id="cut-link" href="/file/cut-videos?id=<?= $match['id']?>">Retour à l'écran de découpe de vidéos</a>
    </div>
    <div class="videos-list">
        <div class="row search-block">
            <div class=" col-md-6">
                <div class="title-n">
                    <?php if (!empty($match)) : ?>
                        <span><?= $match['team1_name'] ?></span>
                        <span>-</span>
                        <span><?= $match['team2_name'] ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class=" col-md-6">
                <img class="img-loader" width="40px" src="/main/images/loader/ball-triangle.svg" alt="">
                <button type="button" class="btn btn-success upload-v">Upload All Videos</button>
            </div>
        </div>
        <div class="row list">
            <table id="video" class="event-column">
                <thead>
                <tr class="event-row first-row">
                    <th>ID</th>
                    <th>Player Name</th>
                    <th>Action Name</th>
                    <th>Video
                        <img class="img-loader-del" width="20px" src="/main/images/loader/ball-triangle.svg" alt="">
                        <span title="Delete All" class="delete-all-video glyphicon glyphicon-trash"></span>
                    </th>
                    <th>Time From</th>
                    <th>Time To</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($events as $event):
                $href = (strripos($event['video_src'], 'http') > -1) ? $event['video_src'] : Yii::$app->params['domain'] . "/videos/data/" . $event['video_src']; ?>
                <tr class="event-row" data-id='<?= $event['id'] ?>'>
                    <th><?= $event['id'] ?></th>
                    <th>
                        <?php if ($event['p_id'] == 100): ?>
                            <?= $match['team1_name'] ?>
                        <?php elseif ($event['p_id'] == 101): ?>
                            <?= $match['team2_name'] ?>
                        <?php else: ?>
                            <?= $event['first_name'] ?>
                            <?= $event['last_name'] ?>
                        <?php endif; ?>
                    </th>
                    <th><?= $event['action_name'] ?></th>
                    <th class="video-src">
                        <?php if (!empty($event['video_src'])): ?>
                            <a target="_blank" href="<?= $href ?> "><?= $event['video_src'] ?></a>
                            <span title="Delete this" class="delete-video glyphicon glyphicon-trash"></span>
                        <?php endif; ?>
                    </th>
                    <th><?= gmdate("H:i:s", $event['time_from']); ?></th>
                    <th><?= gmdate("H:i:s", $event['time_to']); ?></th>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    var MatchId = <?=$match['id']?>;
    var Url = "<?= Yii::$app->params['domain'] . "/videos/data/"?>";
    var HtmlHalfTimeEnd = "<?=$actions['END']['action_name']?>";
    var HtmlHalfTimeStart = "<?=$actions['START']['action_name']?>";
</script>