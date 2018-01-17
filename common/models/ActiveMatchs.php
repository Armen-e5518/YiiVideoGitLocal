<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vid_active_matchs".
 *
 * @property integer $id
 * @property integer $match_id
 * @property string $date
 */
class ActiveMatchs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_active_matchs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id'], 'required'],
            [['match_id'], 'integer'],
            [['date'], 'safe'],
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
            'date' => 'Date',
        ];
    }

    public static function ActiveMatch($data)
    {
        if (!empty($data)) {
            $model = self::findOne(['match_id' => $data['match_id']]);
            if (empty(!$model)) {
                $model->date = date("Y-m-d H:i:s");
                if ($model->save()) {
                    return true;
                } else {
                    return $model->getErrors();
                }
            } else {
                $model->match_id = $data['match_id'];
                $model->date = date("Y-m-d H:i:s");
                if ($model->save()) {
                    return true;
                } else {
                    return $model->getErrors();
                }
            }
        }
        return false;
    }

    public static function GetTimeByMatchId($match_id)
    {
        if (!empty($match_id)) {
            $model = self::findOne(['match_id' => $match_id]);
            if (!empty(!$model)) {
              return $model['date'];
            }
        }
        return false;
    }
}
