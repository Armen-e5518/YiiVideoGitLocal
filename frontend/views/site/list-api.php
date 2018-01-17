<?php
use common\widgets\Alert;
use yii\helpers\Html;

$this->title = 'Matchs List';
$this->registerCssFile('/main/css/list.css');
$this->registerCssFile('/main/css/index-page.css');

$this->registerCssFile('https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css');
$this->registerJsFile('/main/js/jquery/jquery-3.1.1.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/list.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js', ['position' => \yii\web\View::POS_END]);
echo Alert::widget();
?>
<div class="container hader">
    <div class="heder-t row">
        <div class="col-md-3">
            <a class="my-logo" href="/site">
                <img src="/main/images/icons/logo-mycoachfootball.png" alt="">
            </a>
        </div>
        <div class="log-out" data-toggle="tooltip" title="Log-out">
            <?= Html::beginForm(['/site/logout'], 'post') ?>
            <?= Html::submitButton(
                "_",
                ['class' => 'btn btn-link logout']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>
    <div class="videos-list table-data">
        <div class="row list-d">
            <table class="event-column">
                <thead>
                <tr class="event-row first-row">
                    <th>ID</th>
                    <th>Match Name</th>
                    <th>Date</th>
                    <th><span class="glyphicon glyphicon-open"></span></th>
                    <th><span class="glyphicon glyphicon-cloud-upload"></span></th>
                    <th><span class="glyphicon glyphicon-scissors"></span></th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($matchs_api)): ?>
                    <?php foreach ($matchs_api as $match_api): $date = new DateTime($match_api->date); ?>
                        <?php $href = (!empty($matchs[$match_api->gameId]) && $matchs[$match_api->gameId]['status_end'] == 1) ? "/site/match-event?id=" . $match_api->gameId : "/site/match?id=" . $match_api->gameId ?>
                        <tr class="event-row" href="<?= $href ?>">
                            <th class="match-id"> <?= $match_api->gameId ?></th>
                            <th class="match-name"><?= $match_api->homeTeam ?>--<?= $match_api->awayTeam ?></th>
                            <!--                            <th class="match-events-count">1174 Seq.</th>-->
                            <th class="match-data"><?= $date->format('Y-m-d H:i:s'); ?></th>
                            <?php if (!empty($matchs[$match_api->gameId]) && $matchs[$match_api->gameId]['status_end'] == 1): ?>
                                <th>
                                </th>
                            <?php else: ?>
                                <th>
                                    <a href="/file/upload?id=<?= $match_api->gameId ?>">
                                        <span class="glyphicon glyphicon-open"></span>
                                    </a>
                                </th>
                            <?php endif; ?>
                            <?php if (!empty($matchs[$match_api->gameId]) && $matchs[$match_api->gameId]['status_end'] == 1): ?>
                                <th class="list-icon">
                                    <a href="/file/upload-videos?id=<?= $match_api->gameId ?>" target="_blank">
                                        <span class="glyphicon glyphicon-cloud-upload"></span>
                                    </a>
                                </th>
                            <?php else: ?>
                                <th></th>
                            <?php endif; ?>
                            <?php if (!empty($matchs[$match_api->gameId]) && $matchs[$match_api->gameId]['status_end'] == 1): ?>
                                <th class="list-icon">
                                    <a href="/file/cut-videos?id=<?= $match_api->gameId ?>" target="_blank">
                                        <span class="glyphicon glyphicon-scissors"></span>
                                    </a>
                                </th>
                            <?php else: ?>
                                <th></th>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>