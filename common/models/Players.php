<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "vid_players".
 *
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property integer $player_number
 * @property string $team_id
 * @property string $date
 */
class Players extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_players';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date'], 'required'],
            [['player_number'], 'integer'],
            [['id', 'first_name', 'team_id'], 'string', 'max' => 37],
            [['last_name'], 'string', 'max' => 100],
            [['date'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'player_number' => 'Player Number',
            'team_id' => 'Team ID',
            'date' => 'Date',
        ];
    }

    public static function GetPlayersBayTeamId($team_id = null, $match_id)
    {
        if (!empty($team_id)) {
            $query = new Query();
            return $query->select('p.*, pos.player_position,t.team_name, sub.id as sub_id, ')
                ->from(self::tableName() . ' as p')
                ->where('p.team_id ="' . $team_id . '" AND (pos.main IS NULL AND (pos.match_id = "' . $match_id . '" OR pos.match_id IS NULL)) AND (sub.match_id = "' . $match_id . '" OR sub.match_id IS NULL)')
                ->leftJoin(PlayerPositions::tableName() . '  as pos', 'pos.player_id  = p.id')
                ->leftJoin(Substitutions::tableName() . '  as sub', 'sub.player_id  = p.id')
                ->leftJoin(Teams::tableName() . '  as t', 't.id  = p.team_id')
                ->createCommand()
                ->queryAll();
        }
        return [];
    }

    public static function GetPlayersBayTeamIdNew($team_id = null, $match_id)
    {
        if (!empty($team_id)) {
            $query = new Query();
            return $query->select('p.*, pos.player_position,t.team_name, sub.id as sub_id, ')
                ->from(self::tableName() . ' as p')
                ->where(['p.team_id' => $team_id])
                ->leftJoin(PlayerPositions::tableName() . '  as pos', 'pos.player_id  = p.id AND pos.match_id = ' . $match_id . ' AND (pos.main IS NULL)')
                ->leftJoin(Substitutions::tableName() . '  as sub', 'sub.player_id  = p.id AND sub.match_id = ' . $match_id)
                ->leftJoin(Teams::tableName() . '  as t', 't.id  = p.team_id')
                ->createCommand()
                ->queryAll();
        }
        return [];
    }

    public static function GetPlayersBayTeam($team_id = null)
    {
        if (!empty($team_id)) {
            $query = new Query();
            return $query->select('*')
                ->from(self::tableName() . ' as p')
                ->where(['p.team_id' => $team_id])
                ->createCommand()
                ->queryAll();
        }
        return [];
    }

    public static function SavePlayerDataApi($data, $player_id, $team_id)
    {
        if (!empty($data) && !empty($player_id)) {
            $model_p = self::findOne(['id' => $player_id]);
            if (empty($model_p)) {
                $model = new self();
                $model->id = $player_id;
                $model->first_name = (string)$data->givenName;
                $model->last_name = (string)$data->familyName;
                $model->player_number = empty($data->number) ? "" : (int)$data->number;
                $model->team_id = $team_id;
                $model->date = date("Y-m-d H:i:s");
                if ($model->save()) {
                    return true;
                }
            } else {
                $model_p->first_name = (string)$data->givenName;
                $model_p->last_name = (string)$data->familyName;
                $model_p->player_number = empty($data->number) ? "" : (int)$data->number;
                $model_p->team_id = $team_id;
                $model_p->date = date("Y-m-d H:i:s");
                if ($model_p->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function DeletePlayersByTeamId($team_id)
    {
        if (!empty($team_id)) {
            return self::deleteAll(['team_id' => $team_id]);
        }
        return false;
    }
}
