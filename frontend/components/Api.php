<?php

namespace frontend\components;

use yii\httpclient\Client;

class Api
{

    public $api_url;


    public function __construct($api_url)
    {
        $this->api_url = $api_url;
    }

    public function Login($post = null)
    {
        if (!empty($post)) {
            $client = new Client();
            return $client->createRequest()
                ->setMethod('post')
                ->setUrl(\Yii::$app->params['MyCoach']['MyCoachApi'] . '/sessions')
                ->setFormat(Client::FORMAT_JSON)
                ->setData(['principal' => $post['username'], 'credential' => $post['password']])
                ->send();
        }
        return null;

    }

    public function GetMatch()
    {
        $options = [
            'http' => [
                'header' => "MCSession:/sessions/00000c60-8d18-4e28-807b-5b97cf1b62bb\r\n",
                'method' => 'GET',
            ]
        ];
        $res = file_get_contents(\Yii::$app->params['MyCoach']['Api'] . $this->api_url, false, stream_context_create($options));
        if (!empty($res)) {
            return $res;
        }
        return null;
    }

    public function GetPlayersBayTeamId($team_id = null)
    {
        if (!empty($team_id)) {
            $options = [
                'http' => [
                    'header' => "MCSession:/sessions/00000c60-8d18-4e28-807b-5b97cf1b62bb\r\n",
                    'method' => 'GET',
                ]
            ];
            $res = file_get_contents(\Yii::$app->params['MyCoach']['Api'] . $this->api_url, false, stream_context_create($options));
            if (!empty($res)) {
                return $res;
            }
        }
        return null;
    }

    public function GetTeamByTeamId($team_id = null)
    {
        if (!empty($team_id)) {
            $options = [
                'http' => [
                    'header' => "MCSession:/sessions/00000c60-8d18-4e28-807b-5b97cf1b62bb\r\n",
                    'method' => 'GET',
                ]
            ];
            $res = file_get_contents(\Yii::$app->params['MyCoach']['Api'] . $team_id, false, stream_context_create($options));
            if (!empty($res)) {
                return $res;
            }
        }
        return null;
    }

    public function Get($href = null)
    {
        if (!empty($href)) {
            $options = [
                'http' => [
                    'header' => "MCSession:/sessions/00000c60-8d18-4e28-807b-5b97cf1b62bb\r\n X-Api-Access:123456789ABCDEF\r\n",
                    'method' => 'GET',
                ]
            ];
            $res = file_get_contents(\Yii::$app->params['MyCoach']['Api'] . $href, false, stream_context_create($options));
            if (!empty($res)) {
                return $res;
            }
        }
        return null;
    }
}