<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vid_player_team".
 *
 * @property integer $id
 * @property integer $match_id
 * @property string $player_id
 * @property string $team_id
 */
class PlayerTeam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_player_team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'player_id', 'team_id'], 'required'],
            [['match_id'], 'integer'],
            [['player_id', 'team_id'], 'string', 'max' => 37],
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
            'team_id' => 'Team ID',
        ];
    }


    public static function SavePlayerTeam($match_id = null, $player_id = null, $team_id = null)
    {
        if (!empty($player_id) && !empty($team_id)) {
            $model = self::findOne(['player_id' => $player_id, 'match_id' => $match_id, 'team_id' => $team_id,]);
            if (empty($model)) {
                $model = new self();
                $model->match_id = $match_id;
                $model->player_id = $player_id;
                $model->team_id = $team_id;
                return $model->save();
            }
        }
        return true;
    }
}
