<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Videos';
$this->registerCssFile('/css/login.css');
$this->registerCssFile('/css/colorpicker.css');


$this->registerJsFile('/main/js/jquery/jquery-2.2.3.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/videojs/video.js', ['position' => \yii\web\View::POS_END]);
//$this->registerJsFile('/main/js/videojs/ion.rangeSlider.min.js', ['position' => \yii\web\View::POS_END]);
//$this->registerJsFile('/main/js/video-src.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/js/colorpicker.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/index.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/index-functions.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/index-substitutions.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/src.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/functionality.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/popup.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/main/js/check-number.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('/js/common.js', ['position' => \yii\web\View::POS_END]);
?>
<div class="load-b">
    <div class="img-load"></div>
</div>
<div class="popup-m">
    <div class="form-m">
        <div class="content-pop">
            <p>Êtes-vous sûr de vouloir terminer ce match?</p>
            <button type="button" class="btn btn-success">Oui</button>
            <button type="button" class="btn btn-danger">Non</button>
        </div>
    </div>
</div>
<div class="popup-info">
    <div class="form-m">
        <div class="content-pop">
            <p>Voulez vous sauvegarder vos modifications?</p>
            <button type="button" class="btn btn-success">Oui</button>
            <button type="button" class="btn btn-danger">Non</button>
        </div>
    </div>
</div>
<div class="popup-delete">
    <div class="form-m">
        <div class="content-pop">
            <p>Êtes vous sûrs de vouloir supprimer cet évènement?</p>
            <button type="button" class="btn btn-success">Oui</button>
            <button type="button" class="btn btn-danger">Non</button>
        </div>
    </div>
</div>
<div class="popup-colors">
    <div class="form-m">
        <div class="content-pop">
            <p>Vous devez choisir une couleur pour chaque équipe</p>
            <button type="button" class="btn btn-success">OK</button>
            <!--            <button type="button" class="btn btn-danger">Ok</button>-->
        </div>
    </div>
</div>
<div class="popup-number">
    <div class="form-m">
        <div class="content-pop">
            <p> Un des joueurs a qui une action a été attribuée n’a pas de numéro</p>
            <button type="button" class="btn btn-success">OK</button>
            <!--            <button type="button" class="btn btn-danger">Ok</button>-->
        </div>
    </div>
</div>
<div class="popup-line-up">
    <div class="form-m">
        <div class="content-pop">
            <p>Vous n'avez pas sauvegardé le line up</p>
            <button type="button" class="btn btn-success">OK</button>
            <!--            <button type="button" class="btn btn-danger">Ok</button>-->
        </div>
    </div>
</div>
<div class="popup-enregistrer">
    <div class="form-m">
        <div class="content-pop">
            <p>Certains paramètres n'ont pas été sauvegardés</p>
            <button type="button" class="btn btn-success">OK</button>
            <!--            <button type="button" class="btn btn-danger">Ok</button>-->
        </div>
    </div>
</div>
<div class="popup-video">
    <div class="pop-video">
        <span class="glyphicon glyphicon-remove hide-video"></span>
        <video id="video-popup"
               class="video-js vjs-default-skin"
               controls preload="auto"
               data-setup=''>
            <source src="<?= $match['video_src'] ?>" type='video/mp4'/>
        </video>
    </div>
</div>
<div class="body-c">
    <div class="container-fluid hader">
        <div class="heder-t row">
            <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2 logo-mycoach">
                <a class="my-logo" href="/site">
                    <img src="/main/images/icons/logo-mycoachfootball.png" alt="">
                </a>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-5 col-lg-5 match-title">
            <span>
                <h4 class="title">
                    <?= $match['team1_name'] ?>
                    <span class="m-line">-</span>
                    <?= $match['team2_name'] ?>
                    <span class="title-data"><?= $match['date'] ?></span>
                </h4>
            </span>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2"><a href="/site">Retour a la liste des Videos</a></div>
            <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3 advanced-link">
                <a class="statistics ">Traitement Statistiques</a>
            </div>
            <div class="log-out" data-toggle="tooltip" title="Log-out">
                <?= Html::beginForm(['/site/logout'], 'post') ?>
                <?= Html::submitButton(
                    "_",
                    ['class' => 'btn btn-link logout']) ?>
                <?= Html::endForm() ?>
            </div>
        </div>
        <span d-type="show" class="menu-bar"></span>
    </div>
    <div class="container-fluid content">
        <div class="end-match" style="display: none"></div>
        <div class="row top-block">
            <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7 col-lg-7 video-block">
                <div class="video">
                    <video id="video"
                           class="video-js vjs-default-skin"
                           controls preload="auto"

                           data-setup=''>
                        <source src="<?= $match['video_src'] ?>" type='video/mp4'/>
                    </video>
                </div>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-5 col-lg-5 event-block">
                <div class="events-list">
                    <table class="event-column">
                        <tbody>
                        <?php foreach ($events as $event): ?>
                            <tr class="event-row"
                                event-id='<?= $event['id'] ?>'
                                data-timer_time="<?= $event['time_game'] ?>"
                                data-match_id="<?= $match['id'] ?>"
                                data-time_video="<?= $event['time_video'] ?>">
                                <th class="event-number">
                                    <en></en>
                                    <span>|</span>
                                </th>
                                <th class="event-time">  <?= $event['time_game_html'] ?> </th>
                                <th class="event-player-d">
                                    <span class="team-anem"><?= !empty($event['team_id']) ? $match_name[$event['team_id']] : '' ?></span>
                                    <span class="event-player-name"><?= $event['player_name'] ?></span>
                                </th>
                                <th class="event-name"><?= $event['action_name'] ?></th>
                                <th class="f-icns">
                                    <en class="f-cons-start">
                                        <span class="cursor star <?= ($event['favorite']) ? "enable" : "" ?> glyphicon glyphicon-star"
                                              type-d="disabled"></span>
                                        <div class="part">
                                            <en>  <?= $event['half_time'] ?>  </en>
                                        </div>
                                        <en class="map-marker-l">
                                            <span title="Video"
                                                  class="cursor glyphicon glyphicon-facetime-video"></span>
                                        </en>
                                    </en>
                                    <en class="f-cons-save">
                                        <span class="cursor delete glyphicon glyphicon-remove-sign"></span>
                                        <!--                                    <span class="cursor glyphicon glyphicon-floppy-disk"></span>-->
                                    </en>
                                </th>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row menage-block">
            <div class="col-md-5 col-sm-5 col-xs-5 col-lg-5 block-actions">
                <div class="end-match" style="display: none"></div>
                <div class="time row">
                    <div class="time-below col-md-3 col-sm-3 col-xs-3 col-lg-3">
                        <button type="button" class="btn btn-default btn-xs time-10" data-time="-10">-10 sec</button>
                        <button type="button" class="btn btn-default btn-xs time-5" data-time="-5">-5 sec</button>
                    </div>
                    <div class="time-up col-md-3 col-sm-3 col-xs-3 col-lg-3">
                        <button type="button" class="btn btn-default btn-xs time5" data-time="5">+5 sec</button>
                        <button type="button" class="btn btn-default btn-xs time10" data-time="10">+10 sec</button>
                    </div>
                    <div class="Last-Sequence col-md-3 col-sm-3 col-xs-3 col-lg-3">
                        <button type="button" class="btn btn-default btn-xs">Dernière séquence</button>
                    </div>
                </div>
                <div class=" row time-block">
                    <div class="time-clock col-md-4 col-sm-4 col-xs-4 col-lg-4">
                        <span>00:00:00</span>
                    </div>
<!--                    <div class="end-match col-md-4 col-sm-4 col-xs-4 col-lg-4">-->
                        <!--                    <button type="button" class="btn btn-default btn-xs" d-type="start">End Match</button>-->
<!--                    </div>-->
                </div>
                <div id="event-id" class="event">
                    <?php foreach ($actions_main as $action): ?>
                        <button event-type="<?= ($action['event_type']) ? "event" : "action" ?>"
                                type="button"
                                data-id="<?= $action->id ?>"
                                role="<?= $action->timer_role ?>"
                                class="btn btn-lg btn-success"><?= $action->action_name ?></button>
                    <?php endforeach; ?>
                </div>
                <div class="cancel">
                    <button type="button" class="btn btn-lg  btn-danger ">Annuler</button>
                </div>
            </div>
            <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7 field-block">
                <div class="team-info">
                    <button type="button" class="btn btn-default btn-xs modify-positions" d-type="modify">Modifier les
                        positions
                    </button>
                    <button type="button" class="btn btn-default btn-xs Invert-land">Inverser les terrains</button>
                    <button type="button" class="btn btn-default btn-xs Change-swimsuits" d-type="hide">Remplacements
                    </button>
                    <button type="button" class="btn btn-default btn-xs Change-number" d-type="hide">Changer number
                    </button>
                    <?php if (!$line_up): ?>
                        <button type="button" class="btn btn-default btn-xs save-main-pos" d-type="hide">Save Line up
                        </button>
                    <?php endif; ?>
                </div>
                <div class="field row">
                    <span class="img-logo-left">
                        <img title="<?= $match['team1_name'] ?>"
                             src="/main/images/icon-teams/1.svg"
                             alt="logo">
                    </span>

                    <span class="img-logo-right">
                        <img title="<?= $match['team2_name'] ?>"
                             src="/main/images/icon-teams/2.svg"
                             alt="logo">
                    </span>
                    <div class="layer" status="hide"></div>
                    <div id="colorSelectorLeft" class="team-1" match-id="<?= $match['id'] ?>">
                        <div style="background-color: #<?= $match['team1_color'] ?>;"></div>
                    </div>
                    <div id="colorSelectorRight" class="team-2" match-id="<?= $match['id'] ?>">
                        <div style="background-color: #<?= $match['team2_color'] ?>;"></div>
                    </div>

                    <?php for ($i = 1;
                               $i <= 5;
                               $i++): ?>
                        <?php if ($i == 1): ?>
                            <?php echo '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 field-line field-first" data-name="field-game">' ?>
                        <?php else: ?>
                            <?php echo '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 field-line" data-name="field-game">' ?>
                        <?php endif; ?>
                        <?php for ($j = 1; $j <= 40; $j += 5): $index = $i + $j - 1;
                            $e_class = ($index <= 20) ? "Team2" : "Team1" ?>
                            <div data-index="<?= $index ?>" class="field-item">
                                <?php if (!empty($players_pos[$index])): ?>
                                    <div class="player <?= $e_class ?>"
                                         data-id="<?= $players_pos[$index]['id'] ?>"
                                         data-team="<?= $players_pos[$index]['team_name'] ?>">
                                        <div class="p-popup">
                                            <input type="text" class="p-popup-text">
                                            <span class="glyphicon glyphicon-save p-number-save"></span>
                                        </div>
                                        <div class="player-number">
                                            <span><?= $players_pos[$index]['player_number'] ?></span>
                                        </div>
                                        <div class="player-name">
                                            <span><?= $players_pos[$index]['player_name'] ?></span>
                                        </div>
                                        <div class="substitutions">
                                            <input type="checkbox" value="<?= $players_pos[$index]['id'] ?>">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="empty <?= $e_class ?>"></div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                        <?= '</div>' ?>
                    <?php endfor; ?>

                </div>
                <div class="replacements">
                    <div class="replacements-info">
                    <span class="title-1 active">
                      <img title="<?= $match['team1_name'] ?>"
                           src="/main/images/icon-teams/1.svg"
                           alt="logo">
                    </span>
                        <span class="cursor remove-substitutions glyphicon glyphicon-trash" title="Remove all substitutions"></span>
                        <span class="title-2">
                           <img title="<?= $match['team2_name'] ?>"
                                src="/main/images/icon-teams/2.svg"
                                alt="logo">
                    </span>
                        <span class="drag-here">Drag here</span>
                    </div>
                    <div class="replacements-col command-1">
                        <div class="replacements-item-add" id="open">
                            <div class="empty dragdrop2"></div>
                        </div>
                        <?php foreach ($players_free_t1 as $player_free_t1): ?>
                            <div class="replacements-item">
                                <div class="player Team2 sub"
                                     data-id="<?= $player_free_t1['id'] ?>"
                                     data-team="<?= $player_free_t1['team_name'] ?>">
                                    <div class="p-popup">
                                        <input type="text" class="p-popup-text">
                                        <span class="glyphicon glyphicon-save p-number-save"></span>
                                    </div>
                                    <div class="player-number">
                                        <span><?= $player_free_t1['player_number'] ?></span>
                                    </div>
                                    <div class="player-name">
                                        <span><?= $player_free_t1['player_name'] ?></span>
                                    </div>
                                    <div class="substitutions">
                                        <input <?= !empty($player_free_t1['sub_id']) ? "checked" : ""; ?>
                                                type="checkbox" value="<?= $player_free_t1['id'] ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="replacements-col command-2">
                        <div class="replacements-item-add" id="closed">
                            <div class="empty dragdrop1"></div>
                        </div>
                        <?php foreach ($players_free_t2 as $player_free_t2): ?>
                            <div class="replacements-item">
                                <div class="player Team1 sub"
                                     data-id="<?= $player_free_t2['id'] ?>"
                                     data-team="<?= $player_free_t2['team_name'] ?>">
                                    <div class="p-popup">
                                        <input type="text" class="p-popup-text">
                                        <span class="glyphicon glyphicon-save p-number-save"></span>
                                    </div>
                                    <div class="player-number">
                                        <span><?= $player_free_t2['player_number'] ?></span>
                                    </div>
                                    <div class="player-name">
                                        <span><?= $player_free_t2['player_name'] ?></span>
                                    </div>
                                    <div class="substitutions">
                                        <input <?= !empty($player_free_t2['sub_id']) ? "checked" : ""; ?>
                                                type="checkbox" value="<?= $player_free_t2['id'] ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="unknow-player">
                    <div class="field-item player-left">
                        <div class="player" data-id="100" data-team="<?= $match['team1_name'] ?>">
                            <div class="player-number">
                                <span><?= $match['team1_name'] ?></span>
                            </div>
                            <div class="player-name">
                                <span>joueur inconnu</span>
                            </div>
                        </div>
                    </div>
                    <div class="field-item player-right">
                        <div class="player" data-id="101" data-team="<?= $match['team2_name'] ?>">
                            <div class="player-number">
                                <span><?= $match['team2_name'] ?> </span>
                            </div>
                            <div class="player-name">
                                <span>joueur inconnu</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="goal-info">
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
                                    <div class="g-box gpos-8" position="8"></div>
                                    <div class="g-box gpos-9" position="9"></div>
                                </div>
                            </div>
                            <div class="g-box gpos-3" position="3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="player-events">
        <div class="pl-events">
            <?php if (!empty($p_events)): ?>
                <?php foreach ($p_events as $event): ?>
                    <div class="pl-event" data-id="<?= $event['id'] ?>">
                        <span class="player-name"><?= $event['player'] ?></span>
                        <span class="line-to"><?= !empty($event['substitute_player']) ? "->" : "" ?></span>
                        <span class="sub-player-name"><?= $event['substitute_player'] ?></span>
                        <span class="pos-from"> <?= $event['pos_from'] ?></span>
                        <span class="pos-from">-></span>
                        <span class="pos-to"><?= $event['pos_to'] ?></span>
                        <span class="cursor delete-ev glyphicon glyphicon-remove-sign"></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="open-pop" title="Player events list"><span class="glyphicon glyphicon-menu-left"
                                                           data-type='hide'></span></div>
</div>
<script>
    var MatchId = <?=$match['id']?>;
    var HalfTimeId =   <?=$actions['END']['id']?>;
    var StartId =   <?=$actions['START']['id']?>;
    var ExitId =  <?=$actions['EXIT']['id']?>;
    var GoalId =   <?=$actions['GOAL']['id']?>;
    var GoalCSCId =   <?=$actions['GOALC']['id']?>;
    var Penalty =   <?=$actions['PENALTY']['id']?>;
    var PenaltyNameHtml = "<?=$actions['PENALTY']['action_name']?>";
    var HtmlHalfTimeEnd = "<?=$actions['END']['action_name']?>";
    var HtmlHalfTimeStart = "<?=$actions['START']['action_name']?>";
    var CartonId = "<?=$actions['CARTON']['id']?>";
    var MainPos = "<?=(!empty($players_pos)) ? "1" : "0";?>";
    var LineUpPos =<?=($line_up) ? 1 : 0;?>;
    var CheckSubstitutions =<?=($check_substitutions) ? 1 : 0;?>;
    var EndMatch =<?=($match['status_end'] == 1) ? 1 : 0;?>;
</script>