<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vid_actions".
 *
 * @property integer $id
 * @property string $action_name
 * @property string $action_type
 * @property integer $type
 * @property integer $time_before
 * @property integer $time_after
 * @property integer $event_type
 * @property integer $main_type
 * @property string $timer_role
 * @property integer $sorting
 *
 * @property VidEvents[] $vidEvents
 */
class Actions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'time_before', 'time_after', 'event_type', 'main_type', 'sorting'], 'integer'],
            [['action_name'], 'string', 'max' => 50],
            [['action_type'], 'string', 'max' => 10],
            [['timer_role'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_name' => 'Action Name',
            'action_type' => 'Action Type',
            'type' => 'Type',
            'time_before' => 'Time Before',
            'time_after' => 'Time After',
            'event_type' => 'Event Type',
            'main_type' => 'Main Type',
            'timer_role' => 'Timer Role',
            'sorting' => 'Sorting',
        ];
    }

    public static function getActionsType()
    {
        $arr = [];
        $datas = self::find()->all();
        foreach ($datas as $data) {
            if(!empty($data['action_type'])){
                $arr[$data['action_type']]  =  $data;
            }
        }
        return $arr;
    }

    public static function getActionsMain()
    {
        return self::find()
            ->where(['main_type' => 1])
            ->orderBy('sorting')
            ->all();
    }

    public static function getActionsGeneral()
    {
        return self::find()
            ->where(['type' => 0])
            ->all();
    }

    public static function getActionsGoalkeeper()
    {
        return self::find()
            ->where(['type' => 1])
            ->all();
    }

    public static function GetActionNameById($id = null)
    {
        if (!empty($id)) {
            $model = self::findOne(['id' => $id]);
            if (!empty($model)) {
                return $model['action_name'];
            }
        }
        return null;
    }

    public static function GetTimesById($id = null)
    {
        if (!empty($id)) {
            $model = self::findOne(['id' => $id]);
            if (!empty($model)) {
                return $model;
            }
        }
        return null;
    }
}
