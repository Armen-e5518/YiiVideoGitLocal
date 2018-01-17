<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Videos';
$this->registerCssFile("/main/css/match-page.css");
$this->registerCssFile("/main/css/ion/ion.rangeSlider.css");
$this->registerCssFile("/main/css/ion/ion.rangeSlider.skinNice.css");


$this->registerJsFile('/main/js/jquery/jquery-2.2.3.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/videojs/video.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/videojs/ion.rangeSlider.min.js', ['position' => \yii\web\View::POS_END]);
//$this->registerJsFile('/main/js/video-src.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/functionality.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/match-events.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/js/common.js', ['position' => \yii\web\View::POS_END]);
?>
<div class="popup-delete">
    <div class="form-m">
        <div class="content-pop">
            <p>Delete?</p>
            <button type="button" class="btn btn-success">Oui</button>
            <button type="button" class="btn btn-danger">Non</button>
        </div>
    </div>
</div>
<a href="/site/match?id=<?= $match['id'] ?>"><span title="go T1" class="go-back glyphicon glyphicon-arrow-left"></span></a>
<div class="container hader">
    <div class="heder-t row">
        <div class="col-md-3">
            <a class="my-logo" href="/site">
                <img src="/main/images/icons/logo-mycoachfootball.png" alt="">
            </a>
        </div>
        <div class="col-md-5">
            <span>
                <h4 class="title">
                    <?= $match['team1_name'] ?>
                    <span class="m-line">-</span>
                    <?= $match['team2_name'] ?>
                    <span class="title-data"><?= $match['date'] ?></span>
                </h4>
            </span>
        </div>
        <div class="col-md-2"><a href="/site">
                Retour a la liste des Videos
            </a>
        </div>
        <div class="col-md-2">
            <a href="/file/cut-videos?id=<?= $match['id'] ?>" class="treatment act" target="_blank">
                Traitementtermine
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
    <span d-type="show" class=" menu-bar"></span>
</div>
<div class="container-fluid content">
    <div class="row">
        <div class="col-md-7">
            <div id="event-video-f">
                <video id="preview-player"
                       class="video-js vjs-default-skin"
                       controls preload="auto"
                       data-setup=''>
                    <source src="<?= $match['video_src'] ?>" type='video/mp4'/>
                </video>
            </div>
            <div class="slider-v">
                <input id="range_1" type="text" name="range_1" value="">
                <input id="range_43" type="text" name="range_1" value="">
            </div>
            <div class="row block-funk">
                <div class="col-md-4">
                    <div class="players">
                        <span>Joueur 1</span>
                        <select class="player-list" id="player-1">
                            <option value="">--</option>
                            <optgroup label="<?= $match['team1_name'] ?>">
                                <?php if (!empty($players_t1)): ?>
                                    <?php foreach ($players_t1 as $player): ?>
                                        <option <?= ($player['id'] == $event_data['player1_id']) ? "selected" : ""; ?>
                                                value="<?= $player['id'] ?>">
                                            (<?= $player['player_number'] ?>)
                                            <?= $player['player_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <option value="100" <?= ($event_data['player1_id'] == 100) ? "selected" : ""; ?>>
                                    Joueur inconnu
                                </option>
                            </optgroup>
                            <optgroup label="<?= $match['team2_name'] ?>">
                                <?php if (!empty($players_t2)): ?>
                                    <?php foreach ($players_t2 as $player): ?>
                                        <option <?= ($player['id'] == $event_data['player1_id']) ? "selected" : ""; ?>
                                                value="<?= $player['id'] ?>">
                                            (<?= $player['player_number'] ?>)
                                            <?= $player['player_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <option value="101" <?= ($event_data['player1_id'] == 101) ? "selected" : ""; ?>>
                                    Joueurinconnu
                                </option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="actions">
                        <span>Actions</span>
                        <select class="actions-list" id="action">
                            <option value="">--</option>
                            <optgroup label="Main">
                                <?php foreach ($actions_general as $action): ?>
                                    <option <?= ($action['id'] == $event_data['action_id']) ? "selected" : ""; ?>
                                            value="<?= $action['id'] ?>"><?= $action['action_name'] ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label="Goalkeeper">
                                <?php foreach ($actions_goalkeeper as $action): ?>
                                    <option <?= ($action['id'] == $event_data['action_id']) ? "selected" : ""; ?>
                                            value="<?= $action['id'] ?>"><?= $action['action_name'] ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="players">
                        <span>Joueur 2</span>
                        <select class="player-list" id="player-2">
                            <optgroup label="<?= $match['team1_name'] ?>">
                                <option value="">--</option>
                                <?php foreach ($players_t1 as $player): ?>
                                    <option <?= ($player['id'] == $event_data['player2_id']) ? "selected" : ""; ?>
                                            value="<?= $player['id'] ?>">
                                        (<?= $player['player_number'] ?>)
                                        <?= $player['player_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="100" <?= ($event_data['player2_id'] == 100) ? "selected" : ""; ?>>
                                    Joueur inconnu
                                </option>
                            </optgroup>
                            <optgroup label="<?= $match['team2_name'] ?>">
                                <?php foreach ($players_t2 as $player): ?>
                                    <option <?= ($player['id'] == $event_data['player2_id']) ? "selected" : ""; ?>
                                            value="<?= $player['id'] ?>">
                                        (<?= $player['player_number'] ?>)
                                        <?= $player['player_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="101" <?= ($event_data['player2_id'] == 101) ? "selected" : ""; ?>>
                                    Joueur inconnu
                                </option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="results">
                        <span>Reussite</span>
                        <select class="results-list" id="result">
                            <option value="">--</option>
                            <option <?= ($event_data['success'] == '1') ? "selected" : ""; ?> value="1">Oui</option>
                            <option <?= ($event_data['success'] == '0') ? "selected" : ""; ?> value="0">Non</option>
                        </select>
                    </div>
                    <div class="cards">
                        <div class="cards-yellow">
                            <input <?= ($event_data['card_yellow']) ? "checked" : ""; ?> name="card-yellow"
                                                                                         type="checkbox">
                            <span></span>
                        </div>
                        <div class="cards-red">
                            <input <?= ($event_data['card_red']) ? "checked" : ""; ?> name="card-red" type="checkbox">
                            <span></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="fields-full">
                        <span class="field-text-top"> Geolocalisation</span>
                        <span class="field-text-right">Visiteur</span>
                        <div class="field-text-left"><span>Hote</span></div>
                        <div class="field-positions" id="main-field">
                            <div class="field-row field-row_1">
                                <div class="field-position pos-1" data-pos="1"></div>
                                <div class="field-position pos-2" data-pos="2"></div>
                                <div class="field-position pos-3" data-pos="3"></div>
                                <div class="field-position pos-4" data-pos="4"></div>
                            </div>
                            <div class="field-row field-row_2">
                                <div class="field-position pos-1" data-pos="5"></div>
                                <div class="field-position pos-2" data-pos="6"></div>
                                <div class="field-position pos-3" data-pos="7"></div>
                                <div class="field-position pos-4" data-pos="8"></div>
                            </div>
                            <div class="field-row field-row_3">
                                <div class="field-position pos-1" data-pos="9"></div>
                                <div class="field-position pos-2" data-pos="10"></div>
                                <div class="field-position pos-3" data-pos="11"></div>
                                <div class="field-position pos-4" data-pos="12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="field-part">
                        <span class="text-left">Gauche</span>
                        <span class="text-center">Axe</span>
                        <span class="text-right">Droite</span>
                        <div class="field-part-positions">
                            <div class="field-part-row">
                                <div class="field-part-position part-pos-1" data-pos="1"></div>
                                <div class="field-part-position part-pos-2" data-pos="2"></div>
                                <div class="field-part-position part-pos-3" data-pos="3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="visible">
                        <input name="visible" type="checkbox" checked>
                        <span>Séquence Visible</span>
                    </div>
                    <div class="general-buttons">
                        <button type="button" class="btn btn-danger event-save-adv">Enregistrer</button>
                        <button type="button" class="btn btn-default delete-event">Supprimer la sequence</button>
                        <button type="button" class="btn btn-primary clone">Insérer après</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-11 additional-action">
                    <fieldset class=" scheduler-border">
                        <legend class="scheduler-border">Deuxieme Action</legend>
                        <div class="row">
                            <div class="col-md-2">
                                <span class="form-title">Actions</span>
                            </div>
                            <div class="col-md-3">
                                <div class="additional-actions">
                                    <select class="additional-actions-list" id="sec-action">
                                        <option value="">--</option>
                                        <optgroup label="Main">
                                            <?php foreach ($actions_general as $action): ?>
                                                <option <?= ($action['id'] == $event_data['sec_action_id']) ? "selected" : ""; ?>
                                                        value="<?= $action['id'] ?>"><?= $action['action_name'] ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="Goalkeeper">
                                            <?php foreach ($actions_goalkeeper as $action): ?>
                                                <option
                                                <option <?= ($action['id'] == $event_data['sec_action_id']) ? "selected" : ""; ?>
                                                        value="<?= $action['id'] ?>"><?= $action['action_name'] ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row additional-action-hide">
                            <div class="col-md-2">
                                <span class="form-title">Joueur 1</span>
                                <span class="form-title">Joueur 2</span>
                                <span class="form-title">Reussite</span>
                            </div>
                            <div class="col-md-3">
                                <div class="additional-player">
                                    <select class="additional-player-list" id="sec-player-1">
                                        <option value="">--</option>
                                        <optgroup label="<?= $match['team1_name'] ?>">
                                            <?php foreach ($players_t1 as $player): ?>
                                                <option
                                                    <?= ($player['id'] == $event_data['sec_player1_id']) ? "selected" : ""; ?>
                                                        value="<?= $player['id'] ?>">(<?= $player['player_number'] ?>
                                                    ) <?= $player['player_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="100" <?= ($event_data['sec_player1_id'] == 100) ? "selected" : ""; ?>>
                                                Joueur inconnu
                                            </option>
                                        </optgroup>
                                        <optgroup label="<?= $match['team2_name'] ?>">
                                            <?php foreach ($players_t2 as $player): ?>
                                                <option
                                                    <?= ($player['id'] == $event_data['sec_player1_id']) ? "selected" : ""; ?>
                                                        value="<?= $player['id'] ?>">
                                                    (<?= $player['player_number'] ?>)
                                                    <?= $player['player_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="101" <?= ($event_data['sec_player1_id'] == 101) ? "selected" : ""; ?>>
                                                Joueur inconnu
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="additional-player">
                                    <select class="additional-player-list" id="sec-player-2">
                                        <option value="">--</option>
                                        <optgroup label="<?= $match['team1_name'] ?>">
                                            <?php foreach ($players_t1 as $player): ?>
                                                <option <?= ($player['id'] == $event_data['sec_player2_id']) ? "selected" : ""; ?>
                                                        value="<?= $player['id'] ?>">
                                                    (<?= $player['player_number'] ?>)
                                                    <?= $player['player_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="100" <?= ($event_data['sec_player2_id'] == 100) ? "selected" : ""; ?>>
                                                Joueur inconnu
                                            </option>
                                        </optgroup>
                                        <optgroup label="<?= $match['team2_name'] ?>">
                                            <?php foreach ($players_t2 as $player): ?>
                                                <option <?= ($player['id'] == $event_data['sec_player2_id']) ? "selected" : ""; ?>
                                                        value="<?= $player['id'] ?>">
                                                    (<?= $player['player_number'] ?>)
                                                    <?= $player['player_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="101" <?= ($event_data['sec_player2_id'] == 101) ? "selected" : ""; ?>>
                                                Joueur inconnu
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="additional-result">
                                    <select class="additional-player-list" id="sec-result">
                                        <option value="">--</option>
                                        <option <?= ($event_data['sec_success'] == '1') ? "selected" : ""; ?> value="1">
                                            Oui
                                        </option>
                                        <option <?= ($event_data['sec_success'] == '0') ? "selected" : ""; ?> value="0">
                                            Non
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fields-full">
                                    <span class="field-text-top"> Geolocalisation</span>
                                    <span class="field-text-right">Visiteur</span>
                                    <div class="field-text-left"><span>Hote</span></div>
                                    <div class="field-positions" id="sec-field">
                                        <div class="field-row field-row_1">
                                            <div class="additional-field-position pos-1" data-pos="1"></div>
                                            <div class="additional-field-position pos-2" data-pos="2"></div>
                                            <div class="additional-field-position pos-3" data-pos="3"></div>
                                            <div class="additional-field-position pos-4" data-pos="4"></div>
                                        </div>
                                        <div class="field-row field-row_2">
                                            <div class="additional-field-position pos-1" data-pos="5"></div>
                                            <div class="additional-field-position pos-2" data-pos="6"></div>
                                            <div class="additional-field-position pos-3" data-pos="7"></div>
                                            <div class="additional-field-position pos-4" data-pos="8"></div>
                                        </div>
                                        <div class="field-row field-row_3">
                                            <div class="additional-field-position pos-1" data-pos="9"></div>
                                            <div class="additional-field-position pos-2" data-pos="10"></div>
                                            <div class="additional-field-position pos-3" data-pos="11"></div>
                                            <div class="additional-field-position pos-4" data-pos="12"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row selections">
                <form id="search-s" method="post" action="">
                    <div class="col-md-6">
                        <select name="player" class="additional-player-list-search">
                            <option value="">Tous les joueurs</option>
                            <optgroup label="<?= $match['team1_name'] ?>">
                                <?php if (!empty($players_t1)): ?>
                                    <?php foreach ($players_t1 as $player): ?>
                                        <option <?= ($player['id'] == $post_data['player']) ? "selected" : ""; ?>
                                                value="<?= $player['id'] ?>">
                                            (<?= $player['player_number'] ?>)
                                            <?= $player['player_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="100" <?= ($post_data['player'] == 100) ? "selected" : ""; ?>>Joueur
                                        inconnu
                                    </option>
                                <?php endif; ?>
                            </optgroup>
                            <optgroup label="<?= $match['team2_name'] ?>">
                                <?php foreach ($players_t2 as $player): ?>
                                    <option <?= ($player['id'] == $post_data['player']) ? "selected" : ""; ?>
                                            value="<?= $player['id'] ?>">
                                        (<?= $player['player_number'] ?>)
                                        <?= $player['player_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="101" <?= ($post_data['player'] == 101) ? "selected" : ""; ?>>Joueur
                                    inconnu
                                </option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="action" class="actions-list-search">
                            <option value="">Toutes les actions</option>
                            <optgroup label="Main">
                                <?php foreach ($actions_general as $action): ?>
                                    <option <?= ($action['id'] == $post_data['action']) ? "selected" : ""; ?>
                                            value="<?= $action['id'] ?>"><?= $action['action_name'] ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label="Goalkeeper">
                                <?php foreach ($actions_goalkeeper as $action): ?>
                                    <option <?= ($action['id'] == $post_data['action']) ? "selected" : ""; ?>
                                            value="<?= $action['id'] ?>"><?= $action['action_name'] ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </div>
                </form>
            </div>
            <div class="events">
                <table class="event-column-advanced">
                    <?php foreach ($events as $event): ?>
                        <tr class="event-row-advanced" data-id="<?= $event['id'] ?>" data-match="<?= $match['id'] ?>">
                            <th class="event-number-advanced">
                        <span>
                             <en></en>
                        </span>
                            </th>
                            <th><?= $event['time_game_html'] ?></th>
                            <th class="e-action-name"><?= $event['action_name'] ?></th>
                            <th class="e-player-name"><?= $event['player_name'] ?></th>
                            <th><?= !empty($event['team_id']) ? $match_name[$event['team_id']] : "" ?></th>
                            <th class="event-type-field half-field">
                                <div class="event-arrow <?= $event['half_geolocation'] ? '' : 'event-icon-hide' ?>"></div>
                            </th>
                            <th class="event-type-field e-type">
                                <?= ($event['success'] == '1') ? '<div class="event-type"></div>' : '' ?>
                                <?= ($event['success'] == '0') ? '<div class="event-info"></div>' : '' ?>
                            </th>
                            <th class="field-type">
                                <div class="event-field <?= $event['full_geolocation'] ? '' : 'event-icon-hide' ?>"></div>
                            </th>
                            <th class="event-type-field e-eye">
                                <div class="event-eye <?= $event['visible'] ? 'event-icon-hide' : '' ?>"></div>
                            </th>
                            <th class="event-type-field x2">
                                <div class="event-x2 <?= $event['sec_action_id'] ? '' : 'event-icon-hide' ?>"></div>
                            </th>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="goal-info-event">
                <div class="goal">
                    <div class="g-box gpos-1" position="1"></div>
                    <div class="goal-row">
                        <div class="g-box gpos-2" position="2"></div>
                        <div class="g-field">
                            <div class="goal-row">
                                <div class="g-box gpos-4" position="4"></div>
                                <div class="g-box gpos-5" position="5"></div>
                                <div class="g-box gpos-6" position="6"></div>
                            </div>
                            <div class="goal-row">
                                <div class="g-box gpos-7" position="7"></div>
                                <div class="g-box gpos-8" position="8"
                                ">
                            </div>
                            <div class="g-box gpos-9" position="9"></div>
                        </div>
                    </div>
                    <div class="g-box gpos-3" position="3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var time_video = <?=!empty($event_data['time_video']) ? $event_data['time_video'] : 0?>;
    var time_up = <?=!empty($event_data['time_before']) ? $event_data['time_before'] : 10?>;
    var time_dow = <?=!empty($event_data['time_after']) ? $event_data['time_after'] : 10?>;
    var S1_From = <?=!empty($event_data['time_from']) ? $event_data['time_from'] : 0?>;
    var S1_To = <?=!empty($event_data['time_to']) ? $event_data['time_to'] : 0?>;

    var full_geolocation = <?=!empty($event_data['full_geolocation']) ? $event_data['full_geolocation'] : 0;?>;
    var half_geolocation = <?=!empty($event_data['half_geolocation']) ? $event_data['half_geolocation'] : 0;?>;
    var sec_full_geolocation = <?=!empty($event_data['sec_full_geolocation']) ? $event_data['sec_full_geolocation'] : 0?>;
    var sec_action_id = <?=!empty($event_data['sec_action_id']) ? $event_data['sec_action_id'] : 0?>;

    var Penalty =   <?=$actions['PENALTY']['id']?>;
</script>