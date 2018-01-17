<?php
namespace frontend\controllers;

use common\models\Actions;
use common\models\ActiveMatchs;
use common\models\Events;
use common\models\MatchPlayersNumber;
use common\models\Matchs;
use common\models\PlayerEvents;
use common\models\PlayerPositions;
use common\models\Substitutions;
use frontend\components\Crop;
use frontend\components\Helper;
use frontend\components\UploadVideo;
use Yii;
use \yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * Site controller
 */
class AjaxController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->params['domain'] = Yii::$app->urlManager->getHostInfo();
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionSavePlayerPositions()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return PlayerPositions::SetPlayerPosition($post);
            }
        }
    }

    public function actionSavePlayerMainPositions()
    {
        if (Yii::$app->request->isAjax) {

            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return PlayerPositions::SetPlayerMainPosition($post);
            }
        }
    }

    public function actionGetEventHtmlByData()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (($post['event'] == "event_update") && ($post['event_type'] == "action")) {
                $action_name = Events::UpdateEvent($post);
                if (!empty($action_name)) {
                    \Yii::$app->response->format = Response::FORMAT_HTML;
                    return $action_name;
                }
            } elseif ($post['event'] == "all_update") {
                if (Events::AllUpdateEvent($post)) {
                    return [
                        'event_name' => !empty($post['event_name']) ? $post['event_name'] : null,
                        'player_name' => !empty($post['player_name']) ? $post['player_name'] : null,
                        'team_name' => !empty($post['team_name']) ? $post['team_name'] : null,
                    ];
                }
            } else {
                $event_id = Events::SaveEvent($post);
                if (!empty($event_id)) {
                    \Yii::$app->response->format = Response::FORMAT_HTML;
                    return $this->renderAjax("get-event", [
                        'data' => $post,
                        'event_id' => $event_id
                    ]);
                }
            }
            return null;
        }
    }

    public function actionDeleteEvent()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                if (Events::DeleteEvent($post)) {
                    return 1;
                }
            }
            return 0;
        }
    }

    public function actionFavoriteEvent()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                $res = Events::FavoriteEvent($post);
                if ($res) {
                    return $res;
                }
            }
            return 0;
        }
    }

    public function actionCloneEvent()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                $res = Events::CloneEvent($post);
                if (!empty($res)) {
                    return $res;
                }
            }
            return 0;
        }
    }

    public function actionEmptyEvent()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                if (Events::EmptyEvent($post)) {
                    return 1;
                }
            }
            return 0;
        }
    }

    public function actionAddPlayerNumber()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                if (MatchPlayersNumber::SavePlayerNumber($post)) {
                    return 1;
                }
            }
            return 0;
        }
    }

    public function actionChangeStatusMatch()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post['match_id'])) {
                if (Matchs::ChangeStatus($post['match_id'])) {
                    return 1;
                }
            }
            return 0;
        }
    }

    public function actionGetEventBayMatch()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                if (Events::DeleteEvent($post)) {
                    return 1;
                }
            }
            return 0;
        }
    }

    public function actionSaveFullEvent()
    {

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                if (Events::SaveFullEvent($post)) {
                    return 1;
                }
            }
            return 0;
        }
    }

    public function actionCutVideo()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                $data = Crop::CutVideosByEventExec($post);
                if (!empty($data)) {
                    return [
                        'done' => 1,
                        'd_url' => $data['video'],
                        'command' => $data['command'],
                        'command2' => $data['command2'],
                    ];
                }
            }
            return 0;
        }
    }

    public function actionGetEventsByMatch()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                $data = Events::GetEventsByMatchAndVideoLocal($post['match_id']);
                if (!empty($data)) {
                    return $data;
                }
            }
            return 0;
        }
    }

    public function actionGetEventsByMatchUpload()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                $data = Events::GetEventsByMatchAndVideo($post['match_id']);
                if (!empty($data)) {
                    return $data;
                }
            }
            return 0;
        }
    }

    public function actionUploadVideo()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                $data = UploadVideo::UploadVideoByMatch($post);
                if (!empty($data)) {
                    return [
                        'done' => 1,
                        'd_url' => $data
                    ];
                }
            }
            return 0;
        }
    }

    public function actionSaveMatchVideoUrl()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                $res = Matchs::SetVideoSrcByMatchId($post['match_id'], $post['src']);
                if ($res) {
                    return 1;
                } else {
                    return $res;
                }
            }
            return 0;
        }
    }

    public function actionGetEventDataById()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return Events::GetEventById($post['event_id']);
            }
            return 0;
        }
    }

    public function actionSaveGoalPosition()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                if (Events::SaveGoalPosition($post)) {
                    return 1;
                };
            }
            return 0;
        }
    }

    public function actionGetGoalPosition()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return Events::GetGoalPositionByEventId($post);
            }
            return 0;
        }
    }

    public function actionSaveTeamColor()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return Matchs::SaveMatchColor($post);
            }
            return 0;
        }
    }

    public function actionCheckActiveMatch()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return ActiveMatchs::ActiveMatch($post);
            }
            return 0;
        }
    }

    public function actionGetAllEventsByMatch()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                $data = Events::GetAllEventsByMatch($post);
                if (!empty($data)) {
                    return $data;
                }
            }
            return 0;
        }
    }

    public function actionSaveMatchSubstitutions()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return Substitutions::SaveSubstitutionsMatch($post);
            }
            return 0;
        }
    }

    public function actionSavePlayerEvents()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return PlayerEvents::SavePlayerEvents($post);
            }
            return 0;
        }
    }

    public function actionDeletePlayerEvent()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post)) {
                return PlayerEvents::DeletePlayerEvent($post);
            }
            return 0;
        }
    }

    public function actionGetRangeTimeByActionId()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post['action_id'])) {
                return Actions::GetTimesById($post['action_id']);
            }
            return 0;
        }
    }

    public static function actionDeleteVideoInServer()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post['url'])) {
                if (Events::SetNullVideoSrcByEventId($post['id']) && (UploadVideo::DeleteVideoInServer($post['url']) != 0)) {
                    return 1;
                }
            }
            return 0;
        }
    }

    public static function actionDeleteVideoInLocal()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post['url'])) {
                $path = \Yii::$app->basePath . "/web/videos/data/" . $post['url'];
                if (Events::SetNullVideoSrcByEventId($post['id']) && UploadVideo::DeleteLocalVideo($path)) {
                    return 1;
                }
            }
            return 0;
        }
    }

    public function actionDeleteVideo()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (!empty($post['match_id'])) {
                $events = Events::GetEventsByMatchId($post['match_id']);
                $f = true;
                foreach ($events as $event) {
                    if ($event['status_video'] == 1) {
                        UploadVideo::DeleteVideoInServer($event['video_src']);
                        if (!Events::SetNullVideoSrcByEventId($event['id'])) {
                            $f = false;
                        }
                    } else {
                        $path = \Yii::$app->basePath . "/web/videos/data/" . $event['video_src'];
                        UploadVideo::DeleteLocalVideo($path);
                        if (!Events::SetNullVideoSrcByEventId($event['id'])) {
                            $f = false;
                        }
                    }
                }
                return $f;
            }
        }
    }

    public static function actionDownloadVideo()
    {
        if (Yii::$app->request->isAjax) {
            $headers = Yii::$app->response->headers;
            $headers->add('Content-Length', '55');
//            header("Content-Transfer-Encoding: Binary");
//            header("Content-Length:". 55);
//            header("Content-Disposition: attachment");
            $post = Yii::$app->request->post();
            $post['match_id'] = 8452;
            if (!empty($post['match_id'])) {
                $path = \Yii::$app->basePath . "/web/videos/src/";
                $name = Helper::DownloadVideoByMatchId($post['match_id'], $path);
                if (!empty($name)) {
                    return [
                        'url' => $name
                    ];
                }
            }
            return 0;
        }
    }

    public static function actionDownloadVideoProgress()
    {
        if (Yii::$app->request->isAjax) {
            $session = Yii::$app->session;
            if (!empty($session['file_info'])) {
                return $session['file_info'];
            }
            return 0;
        }
    }
}
