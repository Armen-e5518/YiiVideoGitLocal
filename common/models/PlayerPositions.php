<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vid_player_positions".
 *
 * @property integer $id
 * @property integer $match_id
 * @property string $player_id
 * @property integer $player_position
 * @property integer $main
 *
 */
class PlayerPositions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_player_positions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'player_position', 'main'], 'integer'],
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
            'player_position' => 'Player Position',
            'main' => 'Main',
        ];
    }

    public static function SetPlayerPosition($data)
    {
        $flag = true;
        if (!empty($data[0])) {
            $model = new self();
            $model->deleteAll(['match_id' => $data[0], 'main' => null]);
            foreach ($data as $kay => $d) {
                if ($kay >= 1) {
                    $model = new self();
                    $model->match_id = $data[0];
                    $model->player_position = $d['pos'];
                    $model->player_id = $d['id'];
                    if (!$model->save()) {
                        $flag = false;
                    };
                }
            }
        }
        return $flag;
    }

    public static function SetPlayerMainPosition($data)
    {
        $flag = true;
        if (!empty($data[0])) {
            $model = new self();
            $model->deleteAll(['match_id' => $data[0], 'main' => 1]);
            foreach ($data as $kay => $d) {
                if ($kay >= 1) {
                    $model = new self();
                    $model->match_id = $data[0];
                    $model->player_position = $d['pos'];
                    $model->player_id = $d['id'];
                    $model->main = 1;
                    if (!$model->save()) {
                        $flag = false;
                    };
                }
            }
        }
        return $flag;
    }

    public static function CheckLineUpByMatchId($match_id)
    {
        if (!empty($match_id)) {
            return !empty(self::findOne(['match_id' => $match_id, 'main' => 1])) ? true : false;
        }
        return false;
    }
}
