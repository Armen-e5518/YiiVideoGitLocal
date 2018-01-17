<?php

namespace frontend\components;

use common\models\ActiveMatchs;
use common\models\Matchs;

class Helper
{

    public static function out($s)
    {
        echo '<pre>';
        print_r($s);
        echo '</pre>';
    }

    public static function vout($s)
    {
        echo '<pre>';
        var_dump($s);
        echo '</pre>';
    }

    public static function NormalizationEventData($datas = null, $match)
    {
        if (!empty($datas)) {
            $new_array = [];
            foreach ($datas as $data) {
                if ($data['last_name'] == 'unknow_player_t2') {
                    $name = $match['team2_name'];
                } elseif ($data['last_name'] == 'unknow_player_t1') {
                    $name = $match['team1_name'];
                } elseif (empty($data['last_name'])) {
                    $name = $data['action_name'];
                } else {
                    $name = (!empty($data['first_name'])) ? (string)$data['first_name']{0} . '. ' . $data['last_name'] : (string)$data['last_name'];
                    if (empty($data['last_name'])) {
                        $name = (string)$data['first_name'];
                    }
                }
                $new_array[] = [
                    'id' => $data['id'],
                    'name' => $name,
                    'half_time' => $data['half_time'],
                    'time_video' => $data['time_video'],
                    'time_game' => $data['time_game'],
                    'time_game_html' => gmdate("H:i:s", (int)$data['time_game']),
                ];
            }
            return $new_array;
        }
        return [];
    }

    public static function NormalizationEventDataNew($datas = null, $match)
    {
        if (!empty($datas)) {
            $new_array = [];
            foreach ($datas as $data) {
                if ($data['last_name'] == 'unknow_player_t1') {
                    $name = "joueur inconnu";
                    $team_id = $match[1];
                } elseif ($data['last_name'] == 'unknow_player_t2') {
                    $name = "joueur inconnu";
                    $team_id = $match[2];
                } else {
                    $name = (!empty($data['first_name'])) ? (string)$data['first_name']{0} . '. ' . $data['last_name'] : (string)$data['last_name'];
                    if (empty($data['last_name'])) {
                        $name = (string)$data['first_name'];
                    }
                    $team_id = $data['team_id'];
                }
                $new_array[] = [
                    'id' => $data['id'],
                    'player_name' => $name,
                    'action_name' => $data['action_name'],
                    'half_time' => $data['half_time'],
                    'team_id' => $team_id,
                    'time_video' => $data['time_video'],
                    'time_game' => $data['time_game'],
                    'success' => $data['success'],
                    'favorite' => $data['favorite'],
                    'full_geolocation' => $data['full_geolocation'],
                    'visible' => $data['visible'],
                    'sec_action_id' => $data['sec_action_id'],
                    'half_geolocation' => $data['half_geolocation'],
                    'time_game_html' => gmdate("H:i:s", (int)$data['time_game']),
                ];
            }
            return $new_array;
        }
        return [];

    }

    public static function GetMatchData($data = null)
    {
        if (!empty($data)) {
            return [
                $data['team1_id'] => '(' . $data['team1_name'] . ')',
                $data['team2_id'] => '(' . $data['team2_name'] . ')'
            ];
        }
        return [];
    }

    public static function GetMatchDataDI($data = null)
    {
        if (!empty($data)) {
            return [
                1 => $data['team1_id'],
                2 => $data['team2_id']
            ];
        }
        return [];
    }

    public static function MarcgePlayersData($players, $numbers)
    {
        foreach ($players as $kay => $player) {
            if (!empty($numbers[$player['id']])) {
                $name = (!empty($player['first_name'])) ? (string)$player['first_name']{0} . '. ' . $player['last_name'] : (string)$player['last_name'];
                if (empty($player['last_name'])) {
                    $name = (string)$player['first_name'];
                }
                $players[$kay] = [
                    'id' => $player['id'],
                    'player_name' => $name,
                    'player_number' => $numbers[$player['id']]['number'],
                    'team_id' => $player['team_id'],
                    'player_position' => $player['player_position'],
                    'team_name' => $player['team_name'],
                    'sub_id' => $player['sub_id'],
                ];
            } else {
                $name = (!empty($player['first_name'])) ? (string)$player['first_name']{0} . '. ' . $player['last_name'] : (string)$player['last_name'];
                if (empty($player['last_name'])) {
                    $name = (string)$player['first_name'];
                }
                $players[$kay] = [
                    'id' => $player['id'],
                    'player_name' => $name,
                    'player_number' => $player['player_number'],
                    'team_id' => $player['team_id'],
                    'player_position' => $player['player_position'],
                    'team_name' => $player['team_name'],
                    'sub_id' => $player['sub_id'],
                ];
            }
        }
        return $players;
    }

    public static function PlayersOnlyNumber($players)
    {
        $arr = [];
        if (!empty($players)) {
            foreach ($players as $kay => $player) {
                if (!empty($player['player_number'])) {
                    $arr[] = $player;
                }
            }
        }
        return $arr;
    }

    public static function SortingPlayers($players_t1, $players_t2)
    {
        $players_free_t1 = [];
        $players_free_t2 = [];
        $player_list_by_pos = [];
        foreach ($players_t1 as $player) {
            if ($player['player_position'] != null) {
                $player_list_by_pos[$player['player_position']] = $player;
            } else {
                $players_free_t1[] = $player;
            }
        }
        foreach ($players_t2 as $player) {
            if ($player['player_position'] != null) {
                $player_list_by_pos[$player['player_position']] = $player;
            } else {
                $players_free_t2[] = $player;
            }
        }
        return [
            'team_1' => $players_free_t1,
            'team_2' => $players_free_t2,
            'player_list_by_pos' => $player_list_by_pos,
        ];
    }

    public static function GetUserData()
    {
        return [
            'username' => \Yii::$app->params['login'],
            'password' => \Yii::$app->params['password'],
            'rememberMe' => 1,
        ];
    }

    public static function GetMatchArrayById($matchs = null)
    {
        $new_matchs = [];
        if (!empty($matchs)) {
            foreach ($matchs as $match) {
                $new_matchs[$match['id']] = $match;
            }
        }
        return $new_matchs;
    }

    public static function CheckActiveMatch($match_id)
    {
        if (!empty($match_id)) {
            $time_active = ActiveMatchs::GetTimeByMatchId($match_id);
            if (!empty($time_active)) {

            }
        }
        return false;
    }

    public static function DownloadVideoByMatchId($match_id, $path)
    {
//        $match = Matchs::GetVideoSrcByMatchId($match_id);
        $match['video_src'] = 'http://cdn.mycoachfootball.com/videos/a97cbd1d-024b-4f3d-bc5a-6c4d0901ae9a.mp4';

        if (!empty($match['video_src'])) {
            $arra = explode("/", $match['video_src']);
            $file_name = array_pop($arra);
            if(!file_exists($path . $file_name)){

                $fiel_d = new DownloadFile($path . $file_name, $match['video_src']);
                if ($fiel_d->DownloadCurl()) {
                    return $path . $file_name;
                };
            }else{
                return $path . $file_name;
            }
        }
        return false;
    }
}