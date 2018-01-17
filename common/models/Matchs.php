<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "vid_matchs".
 *
 * @property integer $id
 * @property string $team1_id
 * @property string $team2_id
 * @property string $date
 * @property string $video_src
 * @property string $team1_color
 * @property string $team2_color
 * @property integer $status_end
 *
 */
class Matchs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_matchs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'status_end'], 'integer'],
            [['date'], 'safe'],
            [['team1_id', 'team2_id'], 'string', 'max' => 37],
            [['team1_color', 'team2_color'], 'string', 'max' => 10],
            [['video_src'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team1_id' => 'Team1 ID',
            'team2_id' => 'Team2 ID',
            'date' => 'Date',
            'video_src' => 'Video Src',
            'status_end' => 'Status End',
        ];
    }

    public static function getMatchs()
    {
        $query = new Query();
        return $query->select('m.*,t1.team_name as team1_name, t2.team_name as team2_name')
            ->from(self::tableName() . ' as m')
            ->leftJoin('vid_teams as t1', 't1.id  = m.team1_id')
            ->leftJoin('vid_teams as t2', 't2.id  = m.team2_id')
            ->orderBy('m.id')
            ->createCommand()
            ->queryAll();
    }

    public static function ChangeStatus($match_id = null)
    {
        if (!empty($match_id)) {
            $model = self::findOne(['id' => $match_id]);
            if (!empty($model)) {
                $model->status_end = 1;
                if ($model->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getMatchsByMatchId($match_id = null)
    {
        if (!empty($match_id)) {
            $query = new Query();
            $res = $query->select('m.*,t1.team_name as team1_name, t2.team_name as team2_name')
                ->from(self::tableName() . ' as m')
                ->where(['m.id' => $match_id])
                ->leftJoin('vid_teams as t1', 't1.id  = m.team1_id')
                ->leftJoin('vid_teams as t2', 't2.id  = m.team2_id')
                ->createCommand()
                ->queryOne();
            if (!empty($res)) {
                return $res;
            }
        }
        return [];
    }

    public static function GetVideoSrcByMatchId($match_id)
    {
        if (!empty($match_id)) {
            return self::findOne(['id' => $match_id]);
        }
    }

    public static function SetVideoSrcByMatchId($match_id, $v_src)
    {
        if (!empty($match_id)) {
            $model = self::findOne(['id' => $match_id]);
            if (!empty($model)) {
                $model->video_src = trim($v_src);
                if ($model->save()) {
                    return true;
                } else {
                    return $model->getErrors();
                }
            }
        }
        return false;
    }

    public static function SaveMatchApi($data)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data->gameId]);
            if (empty($model)) {
                $date = new \DateTime($data->date);
                $model = new self();
                $model->id = (int)$data->gameId;
                $model->team1_id = (string)$data->homeTeamId;
                $model->team2_id = (string)$data->awayTeamId;
                $model->date = $date->format('Y-m-d H:i:s');
                if ($model->save()) {
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    public static function SaveMatchColor($data)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data['match_id']]);
            if (empty(!$model)) {
                if (!empty($data['color_1'])) {
                    $model->team1_color = $data['color_1'];
                }
                if (!empty($data['color_2'])) {
                    $model->team2_color = $data['color_2'];
                }
                if ($model->save()) {
                    return true;
                } else {
                    return $model->getErrors();
                }
            }
        }
        return false;
    }

}
