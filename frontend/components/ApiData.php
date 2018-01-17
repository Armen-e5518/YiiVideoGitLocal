<?php

namespace frontend\components;

use common\models\Matchs;
use common\models\Players;
use common\models\PlayerTeam;
use common\models\Teams;

class ApiData
{
    public static function SetDataByMatchId($match_id = null)
    {
        $res = true;
        if (!empty($match_id)) {
            $api = New Api('/analytics/official-game-list');
            $matchs = json_decode($api->GetMatch());
            if (empty($matchs)) {
                return false;
            }
            foreach ($matchs as $match) {
                if ($match->gameId == $match_id) {
                    $o_match = $match;
                }
            }
            if (empty($o_match)) {
                return false;
            }
            if (!empty($o_match)) {
                if (Teams::SaveTeam1DataApi($o_match) && Teams::SaveTeam2DataApi($o_match) && Matchs::SaveMatchApi($o_match)) {
                    $team_1 = $api->GetTeamByTeamId('/football-teams/' . $o_match->homeTeamId);
                    $team_2 = $api->GetTeamByTeamId('/football-teams/' . $o_match->awayTeamId);
                    if (!empty($team_1)) {
                        $team_array = json_decode($team_1);
                        $embedded = (array)$team_array->_embedded;
                        if (!empty($embedded['mc:footballPlayer'])) {
                            foreach ($embedded['mc:footballPlayer'] as $player) {
                                $player_a = (array)$player;
                                $embedded_p = (array)$player_a['_embedded'];
                                $teamStatus = (array)$embedded_p['mc:teamStatus'][0];
                                $linkss = (array)$teamStatus['_links'];
                                $self = (array)$linkss['self'];
                                $href = $self['href'];
                                $player_id_href = explode('/', $href);
                                $player_id = $player_id_href[2];
                                if (!Players::SavePlayerDataApi($player, $player_id, $o_match->homeTeamId) || !PlayerTeam::SavePlayerTeam($match_id, $player_id, $o_match->homeTeamId)) {
                                    $res = false;
                                }
                            }
                        }
                    }
                    if (!empty($team_2)) {
                        $team_array = json_decode($team_2);
                        $embedded = (array)$team_array->_embedded;
                        if (!empty($embedded['mc:footballPlayer'])) {
                            foreach ($embedded['mc:footballPlayer'] as $player) {
                                $player_a = (array)$player;
                                $embedded_p = (array)$player_a['_embedded'];
                                $teamStatus = (array)$embedded_p['mc:teamStatus'][0];
                                $linkss = (array)$teamStatus['_links'];
                                $self = (array)$linkss['self'];
                                $href = $self['href'];
                                $player_id_href = explode('/', $href);
                                $player_id = $player_id_href[2];
                                if (!Players::SavePlayerDataApi($player, $player_id, $o_match->awayTeamId) || !PlayerTeam::SavePlayerTeam($match_id, $player_id, $o_match->homeTeamId)) {
                                    $res = false;
                                };
                            }
                        }
                    }
                } else {
                    return false;
                }
            }
        }
        return $res;
    }
}
