<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\swiftmailer\Mailer;

/**
 * This is the model class for table "vid_player_events".
 *
 * @property integer $id
 * @property integer $match_id
 * @property string $player_id
 * @property string $substitute_player
 * @property integer $pos_from
 * @property integer $pos_to
 * @property integer $time
 * @property integer $half_time
 */
class PlayerEvents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_player_events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id'], 'required'],
            [['match_id', 'pos_from', 'pos_to', 'time', 'half_time'], 'integer'],
            [['player_id', 'substitute_player'], 'string', 'max' => 37],
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
            'substitute_player' => 'Substitute Player',
            'pos_from' => 'Pos From',
            'pos_to' => 'Pos To',
            'time' => 'Time',
            'half_time' => 'Half Time',
        ];
    }

    public static function SavePlayerEvents($data)
    {
        if (!empty($data['event'])) {
            $event = $data['event'];
            if (!empty($event)) {
                $model = new self();
                $model->match_id = (int)$data['id'];
                $model->player_id = (string)$event['player'];
                $model->substitute_player = !empty($event['sub_player']) ? (string)$event['sub_player'] : null;
                $model->pos_from = !empty($event['pos_from']) ? (int)$event['pos_from'] : null;
                $model->pos_to = !empty($event['pos_to']) ? (int)$event['pos_to'] : null;
                $model->time = (int)$event['time'];
                $model->half_time = (int)$event['half_time'];
                if (!$model->save()) {
                    return $model->getErrors();
                } else {
                    return $model->id;
                }
            }
        }
        return false;
    }

    public static function DeletePlayerEvent($data)
    {
        if (!empty($data)) {
            $model = self::findOne(['match_id' => $data['match_id'], 'id' => $data['id']]);
            if (!empty($model)) {
                return $model->delete();
            }
        }
        return false;
    }

    /**
     * @param $match_id
     * @return array
     */
    public static function GetPlayerEventsByMatchId($match_id)
    {
        if (!empty($match_id)) {
            $query = new Query();
            return $query->select( new Expression(" e.id,
                                    e.pos_from,
                                    e.pos_to,
                                    CONCAT (LEFT ( p1.first_name , 1 ), '.' , `p1`.`last_name` ) as player,
                                    CONCAT (LEFT ( p2.first_name , 1 ), '.' , `p2`.`last_name` ) as substitute_player
                                    "))
                ->from(self::tableName() . ' as e')
                ->where(['e.match_id' => $match_id])
                ->leftJoin(Players::tableName() . ' as p1', 'p1.id  = e.player_id')
                ->leftJoin(Players::tableName() . ' as p2', 'p2.id  = e.substitute_player')
                ->orderBy(['e.id' => SORT_DESC])
                ->createCommand()
                ->queryAll();
        }
        return [];
    }

}
