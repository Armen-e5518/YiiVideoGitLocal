<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vid_substitutions".
 *
 * @property integer $id
 * @property integer $match_id
 * @property string $player_id
 *
 */
class Substitutions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_substitutions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'player_id'], 'required'],
            [['match_id'], 'integer'],
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
        ];
    }

    public static function GetSubstitutionsByMatchId($match_id){
        if(!empty($match_id)){
            return self::findAll(['match_id' => $match_id]);
        }
        return [];
    }

    public static function SaveSubstitutionsMatch($data = null)
    {
        $flag = true;
        if (!empty($data)) {
            $model = new self();
            if (!empty($data['match_id'])) {
                $model->deleteAll(['match_id' => $data['match_id']]);
            }
            if (!empty($data['team1'])) {
                foreach ($data['team1'] as $player){
                    $model = new self();
                    $model->match_id = $data['match_id'];
                    $model->player_id = $player;
                   if(!$model->save()){
                       $flag =false;
                   }
                }
            }
            if (!empty($data['team2'])) {
                foreach ($data['team2'] as $player){
                    $model = new self();
                    $model->match_id = $data['match_id'];
                    $model->player_id = $player;
                    if(!$model->save()){
                        $flag =false;
                    }
                }
            }
        }
        return $flag;
    }

    public static function CheckSubstitutionsByMatchId($match_id){
        if(!empty($match_id)){
            return !empty(self::findOne(['match_id' => $match_id]));
        }
        return false;
    }
}
