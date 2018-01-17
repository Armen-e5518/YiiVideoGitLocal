<tr class="event-row new-event"
    data-time_video="<?= $data['time_video'] ?>"
    data-timer_time="<?= $data['time_game'] ?>"
    data-match_id="<?= $data['match_id'] ?>"
    event-id='<?= $event_id ?>'>
    <th class="event-number">
        <en></en>
        <span>|</span></th>
    <th class="event-time"> <?= $data['time'] ?> </th>
    <th class="event-player-d">
        <span class="team-anem"><?= !empty($data['team_name']) ? "(" . $data['team_name'] . ")" : ""; ?></span>
        <span class="event-player-name"><?= !empty($data['player_name']) ? $data['player_name'] : "" ?></span>
    </th>
    <th class="event-name"> <?= !empty($data['event_name']) ? $data['event_name'] : '' ?></th>
    <th class="f-icns">
        <en class="f-cons-start">
            <span class="cursor star  glyphicon glyphicon-star" type-d="disabled"></span>
            <div class="part">
                <en><?= $data['played_T_hrml'] ?></en>
            </div>
            <en class="map-marker-l">
                <span title="Video" class="cursor glyphicon glyphicon-facetime-video"></span>
            </en>
        </en>
        <en class="f-cons-save">
            <span class="cursor delete glyphicon glyphicon-remove-sign"></span>
        </en>
    </th>
</tr>

