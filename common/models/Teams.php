<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vid_teams".
 *
 * @property string $id
 * @property string $team_name
 */
class Teams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_teams';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'string', 'max' => 255],
            [['team_name'], 'string', 'max' => 37],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_name' => 'Team Name',
        ];
    }
    public static function SaveTeam1DataApi($data)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data->homeTeamId]);
            if (empty($model)) {
                $model = new self();
                $model->id = $data->homeTeamId;
                $model->team_name = $data->homeTeam;
                if ($model->save()) {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }

    public static function SaveTeam2DataApi($data)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data->awayTeamId]);
            if (empty($model)) {
                $model = new self();
                $model->id = $data->awayTeamId;
                $model->team_name = $data->awayTeam;
                if ($model->save()) {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }
}
