<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "vid_events".
 *
 * @property integer $id
 * @property integer $match_id
 * @property integer $action_id
 * @property string $player1_id
 * @property string $player2_id
 * @property integer $time_game
 * @property integer $time_video
 * @property integer $success
 * @property integer $card_yellow
 * @property integer $card_red
 * @property integer $visible
 * @property integer $full_geolocation
 * @property integer $half_geolocation
 * @property integer $half_time
 * @property integer $favorite
 * @property integer $sec_action_id
 * @property string $sec_player1_id
 * @property string $sec_player2_id
 * @property integer $sec_success
 * @property integer $sec_full_geolocation
 * @property integer $time_from
 * @property integer $time_to
 * @property string $video_src
 * @property integer $status_video
 * @property integer $goal_position
 */
class Events extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vid_events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'action_id', 'time_game', 'time_video', 'success', 'card_yellow', 'card_red', 'visible', 'full_geolocation', 'half_geolocation', 'half_time', 'favorite', 'sec_action_id', 'sec_success', 'sec_full_geolocation', 'time_from', 'time_to', 'status_video', 'goal_position'], 'integer'],
            [['player1_id', 'player2_id', 'sec_player1_id', 'sec_player2_id'], 'string', 'max' => 37],
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
            'match_id' => 'Match ID',
            'action_id' => 'Action ID',
            'player1_id' => 'Player1 ID',
            'player2_id' => 'Player2 ID',
            'time_game' => 'Time Game',
            'time_video' => 'Time Video',
            'success' => 'Success',
            'card_yellow' => 'Card Yellow',
            'card_red' => 'Card Red',
            'visible' => 'Visible',
            'full_geolocation' => 'Full Geolocation',
            'half_geolocation' => 'Half Geolocation',
            'half_time' => 'Half Time',
            'favorite' => 'Favorite',
            'sec_action_id' => 'Sec Action ID',
            'sec_player1_id' => 'Sec Player1 ID',
            'sec_player2_id' => 'Sec Player2 ID',
            'sec_success' => 'Sec Success',
            'sec_full_geolocation' => 'Sec Full Geolocation',
            'time_from' => 'Time From',
            'time_to' => 'Time To',
            'video_src' => 'Video Src',
            'status_video' => 'Status Video',
            'goal_position' => 'goal Position',
        ];
    }

    public static function GetEventsByMatch($match_id = null, $order = null, $post = null, $match = null)
    {
        if (!empty($match_id)) {
            $query = new Query();
            $query->select(" ev.id as id ,
                             ev.half_time, 
                             ev.time_game,
                             ev.time_video,
                             ev.success,
                             ev.full_geolocation,
                             ev.visible,
                             ev.favorite,
                             ev.half_geolocation,
                             ev.sec_action_id,
                             ev.time_from,
                             ev.time_to,
                             ev.video_src,
                             p.last_name, 
                             p.first_name, 
                             pt.team_id, 
                             act.action_name")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.match_id' => $match_id])
                ->leftJoin(Actions::tableName() . ' as act', 'act.id = ev.action_id')
                ->leftJoin(PlayerTeam::tableName() . ' as pt', 'pt.player_id = ev.player1_id AND pt.match_id = ' . $match_id)
                ->leftJoin(Players::tableName() . ' as p', 'p.id = pt.player_id');

            if (!empty($order)) {
                $query->orderBy('ev.time_video ' . $order);
            }

            if (!empty($post['player'])) {
                $query->andFilterWhere(['or',
                    ['ev.player1_id' => $post['player']],
                    ['ev.player2_id' => $post['player']],
                    ['ev.sec_player1_id' => $post['player']],
                    ['ev.sec_player2_id' => $post['player']],
                ]);
            }
            if (!empty($post['action'])) {
                $query->andFilterWhere(['or',
                    ['ev.action_id' => $post['action']],
                    ['ev.sec_action_id' => $post['action']],
                ]);
            }
            return $query->createCommand()
                ->queryAll();
        }
        return [];
    }

    public static function GetEventsByMatchByVideo($match_id = null, $order = null, $post = null)
    {
        if (!empty($match_id)) {
            $query = new Query();
            $query->select(" ev.id as id ,
                             ev.half_time, 
                             ev.time_game,
                             ev.time_video,
                             ev.success,
                             ev.full_geolocation,
                             ev.visible,
                             ev.favorite,
                             ev.half_geolocation,
                             ev.sec_action_id,
                             ev.time_from,
                             ev.time_to,
                             ev.video_src,
                             p.first_name, 
                             p.last_name, 
                             p.id as p_id, 
                             p.team_id, 
                             act.action_name")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.match_id' => $match_id])
//                ->andWhere(['not', ['ev.video_src' => '']])
                ->leftJoin(Actions::tableName() . ' as act', 'act.id = ev.action_id')
                ->leftJoin(Players::tableName() . ' as p', 'p.id = ev.player1_id');
            if (!empty($order)) {
                $query->orderBy('ev.time_game ' . $order . ', ev.id ASC');
            }

            if (!empty($post['player'])) {
                $query->andWhere(['ev.player1_id' => $post['player']]);
            }
            if (!empty($post['action'])) {
                $query->andWhere(['ev.action_id' => $post['action']]);
            }
            return $query->createCommand()
                ->queryAll();
        }
        return [];
    }

    public static function GetCountEventsByMatchAndVideo($match_id = null)
    {
        if (!empty($match_id)) {
            $query = new Query();
            return $query->select("count(*) as count")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.match_id' => $match_id])
                ->andWhere(['not', ['ev.video_src' => '']])
                ->createCommand()
                ->queryOne();
        }
        return [];
    }

    public static function GetCountEventsByMatchAndVideoServer($match_id = null)
    {
        if (!empty($match_id)) {
            $query = new Query();
            return $query->select("count(*) as count")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.match_id' => $match_id])
                ->andWhere(['not', ['ev.video_src' => '']])
                ->andWhere(['ev.status_video' => 1])
                ->createCommand()
                ->queryOne();
        }
        return [];
    }

    public static function GetEventsByMatchAndVideo($match_id = null)
    {
        if (!empty($match_id)) {
            $query = new Query();
            return $query->select(" ev.id as id ,
                             ev.time_video,
                             ev.time_from,
                             ev.time_to,
                             ev.video_src,
                             m.video_src as m_video")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.match_id' => $match_id])
                ->andWhere(['ev.status_video' => 0])
                ->leftJoin(Matchs::tableName() . ' as m', 'm.id = ev.match_id')
                ->createCommand()
                ->queryAll();
        }
        return [];
    }

    public static function GetEventsByMatchAndVideoLocal($match_id = null)
    {
        if (!empty($match_id)) {
            $query = new Query();
            return $query->select(" ev.id as id ,
                             ev.time_video,
                             ev.time_from,
                             ev.time_to,
                             ev.video_src,
                             m.video_src as m_video")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.match_id' => $match_id])
                ->andWhere(['ev.video_src' => null])
                ->leftJoin(Matchs::tableName() . ' as m', 'm.id = ev.match_id')
                ->createCommand()
                ->queryAll();
        }
        return [];
    }

    public static function SaveEvent($post = null)
    {
        if (!empty($post)) {
            $model = new self();
            $model->time_video = !empty($post['time_video']) ? (int)$post['time_video'] : 0;
            $model->action_id = !empty($post['action_id']) ? (int)$post['action_id'] : null;
            $model->match_id = !empty($post['match_id']) ? (int)$post['match_id'] : null;
            $model->player1_id = !empty($post['player1_id']) ? (string)$post['player1_id'] : null;
            $model->time_game = !empty($post['time_game']) ? (int)$post['time_game'] : 0;
            $model->half_time = !empty($post['played_T_hrml']) ? (int)$post['played_T_hrml'] : null;
            if (!empty($post['action_id'])) {
                $a_model = Actions::GetTimesById($post['action_id']);
                if (!empty($a_model)) {
                    $model->time_from = ((int)$model->time_video - (int)$a_model['time_before'] > 0) ? (int)($model->time_video - (int)$a_model['time_before']) : 0;
                    $model->time_to = (int)$model->time_video + (int)$a_model['time_after'];
                }
            } else {
                $model->time_from = (((int)$model->time_video - 5) > 0) ? (int)$model->time_video - 5 : 0;
                $model->time_to = (int)$model->time_video + 5;
            }
            if ($model->save()) {
                return $model->id;
            };
        }
        return null;
    }

    public static function UpdateEvent($post = null)
    {
        if (!empty($post)) {
            $model = self::findOne(['id' => $post['event_id']]);
            $model->action_id = !empty($post['action_id']) ? (int)$post['action_id'] : null;
            $a_model = Actions::GetTimesById($post['action_id']);
            if (!empty($a_model)) {
                $model->time_from = ((int)$model->time_video - (int)$a_model['time_before'] > 0) ? (int)($model->time_video - (int)$a_model['time_before']) : 0;
                $model->time_to = (int)$model->time_video + (int)$a_model['time_after'];
            }
            if ($model->save()) {
                return Actions::GetActionNameById($model->action_id);
            }
        }
        return false;
    }

    public static function AllUpdateEvent($post = null)
    {
        if (!empty($post)) {
            $model = self::findOne(['id' => $post['event_id']]);
            if (!empty($post['action_id'])) {
                $model->action_id = $post['action_id'];
            }
            if (!empty($post['player1_id'])) {
                $model->player1_id = $post['player1_id'];
            }
            if ($model->save()) {
                return true;
            };
        }
        return false;
    }

    public static function DeleteEvent($data = null)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data['event_id']]);
            if (!empty($model)) {
                if ($model->delete()) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function FavoriteEvent($data = null)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data['event_id'], 'favorite' => 1]);

            if (!empty($model)) {
                $model->favorite = 0;
                if ($model->save()) {
                    return 1;
                }
            } else {
                $model = self::findOne(['id' => $data['event_id']]);
                if (!empty($model)) {
                    $model->favorite = 1;
                    if ($model->save()) {
                        return 2;
                    } else {
                        return $model->getErrors();
                    }
                }
            }
        }

        return false;
    }

    public static function CloneEvent($data = null)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data['event_id']]);
            if (!empty($model)) {
                $clone = new self();
                $clone->attributes = $model->attributes;
                $clone->video_src = null;
                $clone->status_video = null;
                if ($clone->save()) {
                    return $clone->id;
                }
            }
        }

        return false;
    }

    public static function EmptyEvent($data = null)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data['event_id']]);
            if (!empty($model)) {
                $model->action_id = null;
                $model->player1_id = null;
                if ($model->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function GetEventById($event_id = null)
    {
        if (!empty($event_id)) {
            $query = new Query();
            $query->select(" ev.*,
                             p.first_name, 
                             p.last_name, 
                             p.team_id, 
                             act.time_before, 
                             act.time_after, 
                             act.action_name")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.id' => $event_id])
                ->leftJoin(Actions::tableName() . ' as act', 'act.id = ev.action_id')
                ->leftJoin(Players::tableName() . ' as p', 'p.id = ev.player1_id');
//                ->orderBy('ev.time_game');
            $command = $query->createCommand();
            $d = $command->queryAll();
            if (!empty($d)) {
                return $command->queryOne();
            }
        }
        return [
            'id' => null,
            'match_id' => null,
            'action_id' => null,
            'player1_id' => null,
            'player2_id' => null,
            'time_game' => null,
            'time_video' => null,
            'success' => null,
            'card_yellow' => null,
            'card_red' => null,
            'visible' => null,
            'full_geolocation' => null,
            'half_geolocation' => null,
            'half_time' => null,
            'favorite' => null,
            'sec_action_id' => null,
            'sec_player1_id' => null,
            'sec_player2_id' => null,
            'sec_success' => null,
            'sec_full_geolocation' => null,
            'time_from' => null,
            'time_to' => null,
            'video_src' => null,
            'first_name' => null,
            'last_name' => null,
            'team_id' => null,
            'time_before' => null,
            'time_after' => null,
            'action_name' => null,
            'goal_position' => null
        ];

    }

    public static function GetEventByIdAjax($event_id = null)
    {
        if (!empty($event_id)) {
            $query = new Query();
            $query->select("*")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.id' => $event_id]);
            $command = $query->createCommand();
            $d = $command->queryAll();
            if (!empty($d)) {
                return $command->queryOne();
            }
        }
        return [];

    }

    public static function SaveFullEvent($data = null)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data['id'], 'match_id' => $data['match_id']]);
            if (!empty($model)) {
                $model->action_id = !empty($data['action_id']) ? (int)$data['action_id'] : null;
                $model->player1_id = !empty($data['player1_id']) ? $data['player1_id'] : null;
                $model->player2_id = !empty($data['player2_id']) ? $data['player2_id'] : null;
                $model->time_from = !empty($data['time_from']) ? (int)$data['time_from'] * 1 : $model->time_video;
                $model->time_to = !empty($data['time_to']) ? (int)$data['time_to'] * 1 : $model->time_video + 10;
                $model->success = isset($data['success']) ? $data['success'] : null;
                $model->card_yellow = ($data['card_yellow'] == "true") ? 1 : 0;
                $model->card_red = ($data['card_red'] == "true") ? 1 : 0;
                $model->visible = ($data['visible'] == "true") ? 1 : 0;
                $model->full_geolocation = !empty($data['full_geolocation']) ? (int)$data['full_geolocation'] : 0;
                $model->half_geolocation = !empty($data['half_geolocation']) ? (int)$data['half_geolocation'] : 0;
                $model->sec_action_id = !empty($data['sec_action_id']) ? (int)$data['sec_action_id'] : null;
                $model->sec_player1_id = !empty($data['sec_player1_id']) ? $data['sec_player1_id'] : null;
                $model->sec_player2_id = !empty($data['sec_player2_id']) ? $data['sec_player2_id'] : null;
                $model->sec_success = isset($data['sec_success']) ? $data['sec_success'] : null;
                $model->sec_full_geolocation = !empty($data['sec_full_geolocation']) ? (int)$data['sec_full_geolocation'] : 0;
                $model->goal_position = !empty($data['goal_position']) ? (int)$data['goal_position'] : 0;
                if ($model->save()) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function GetEventsByMatchId($id = null)
    {
        if (!empty($id)) {
            return self::find()->where(['match_id' => $id])->all();
        }
        return null;
    }

    public static function SaveVideoSrcByEventId($event_id, $src)
    {
        $model = self::findOne(['id' => $event_id]);
        if (!empty($model)) {
            $model->video_src = $src;
            $model->status_video = 0;
            if ($model->save()) {
                return true;
            }
        }
        return false;
    }

    public static function SetVideoSrcByEventId($event_id, $v_src)
    {
        if (!empty($event_id)) {
            $model = self::findOne(['id' => $event_id]);
            if (!empty($model)) {
                $model->video_src = trim($v_src);
                $model->status_video = 1;
                if ($model->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function SaveGoalPosition($data)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data['id']]);
            if (!empty($model)) {
                $model->goal_position = isset($data['position']) ? $data['position'] : 0;
                if ($model->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function GetGoalPositionByEventId($data)
    {
        if (!empty($data)) {
            $model = self::findOne(['id' => $data['id']]);
            if (!empty($model)) {
                return $model['goal_position'];
            }
        }
        return false;
    }

    public static function GetAllEventsByMatch($data)
    {
        if (!empty($data)) {
            $query = new Query();
            return $query->select("ev.id")
                ->from(self::tableName() . ' as ev')
                ->where(['ev.match_id' => $data['match_id']])
                ->orderBy('ev.time_video')
                ->createCommand()
                ->queryAll();
        }
        return false;
    }

    public static function SetNullVideoSrcByEventId($id)
    {
        if (!empty($id)) {
            $model = self::findOne(['id' => $id]);
            $model->video_src = null;
            $model->status_video = null;
            if ($model->save()) {
                return true;
            }
        }
    }

}
