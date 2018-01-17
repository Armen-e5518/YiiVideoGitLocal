<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "vid_match_players_number".
 *
 * @property integer $id
 * @property integer $match_id
 * @property string $player_id
 * @property integer $number
 */
class MatchPlayersNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_match_players_number';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'number'], 'integer'],
            [['player_id'], 'string', 'max' => 37],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Match ID',
            'player_id' => 'Player ID',
            'number' => 'Number',
        ];
    }

    public static function SavePlayerNumber($data = null)
    {
        if (!empty($data)) {
            $model = self::findOne(['match_id' => $data['match_id'], 'player_id' => $data['player_id']]);
            if (empty($model)) {
                $new_model = new self();
                $new_model->match_id = $data['match_id'];
                $new_model->player_id = $data['player_id'];
                $new_model->number = $data['number'];
                if ($new_model->save()) {
                    return true;
                }
            } else {
                $model->number = $data['number'];
                if ($model->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function GetPlayersByMatch($match_id = null)
    {
        if (!empty($match_id)) {
            $query = new Query();
            return $query->select('n.player_id, n.number')
                ->from(self::tableName() . ' as n')
                ->where(['n.match_id' => $match_id])
                ->leftJoin(Players::tableName() . '  as p', 'p.id  = n.player_id')
                ->createCommand()
                ->queryAll();
        }
        return [];
    }
}
