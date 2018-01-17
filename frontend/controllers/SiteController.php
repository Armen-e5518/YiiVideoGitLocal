<?php
namespace frontend\controllers;

use common\models\PlayerEvents;
use common\models\PlayerPositions;
use common\models\Substitutions;
use frontend\components\ApiData;
use frontend\components\DownloadFile;
use frontend\components\Helper;
use frontend\components\UploadVideo;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\components\Api;
use common\models\Actions;
use common\models\Events;
use common\models\MatchPlayersNumber;
use common\models\Matchs;
use common\models\Players;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
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

        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        $matchs = Helper::GetMatchArrayById(Matchs::getMatchs());
        $matchs_api = json_decode((new Api('/analytics/official-game-list'))->GetMatch());
        return $this->render('list-api', [
            'matchs_api' => $matchs_api,
            'matchs' => $matchs
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionMatch()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        $players_number_by_match_new = [];
        $match_id = !empty(Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : false;
        if (!$match_id) {
            return $this->redirect("/site");
        }
        if (!ApiData::SetDataByMatchId($match_id)) {
            return $this->redirect("/site");
        };
        $match = Matchs::getMatchsByMatchId($match_id);
        if (empty($match)) {
            Yii::$app->session->setFlash('error', 'Error...');
            return $this->redirect("/site");
        }
        $match_ids = Helper::GetMatchDataDI($match);
//        if ($match['status_end'] == 1) {
//            return $this->redirect("/site/match-event?id=" . $match['id']);
//        }
        $players_t1 = Players::GetPlayersBayTeamIdNew($match['team1_id'], $match_id);
        $players_t2 = Players::GetPlayersBayTeamIdNew($match['team2_id'], $match_id);

        $players_number_by_match = MatchPlayersNumber::GetPlayersByMatch($match_id);
        foreach ($players_number_by_match as $player) {
            $players_number_by_match_new[$player['player_id']] = $player;
        }
        $players_t1 = Helper::MarcgePlayersData($players_t1, $players_number_by_match_new);
        $players_t2 = Helper::MarcgePlayersData($players_t2, $players_number_by_match_new);
        $sort_data = Helper::SortingPlayers($players_t1, $players_t2);
        return $this->render('match', [
            'match' => $match,
            'match_name' => Helper::GetMatchData($match),
            'actions_main' => Actions::getActionsMain(),
            'actions' => Actions::getActionsType(),
            'players_pos' => $sort_data['player_list_by_pos'],
            'players_free_t1' => $sort_data['team_1'],
            'players_free_t2' => $sort_data['team_2'],
            'events' => Helper::NormalizationEventDataNew(Events::GetEventsByMatch($match_id, 'DESC', null, $match), $match_ids),
            'p_events' => PlayerEvents::GetPlayerEventsByMatchId($match_id),
            'line_up' => PlayerPositions::CheckLineUpByMatchId($match_id),
            'check_substitutions' => Substitutions::CheckSubstitutionsByMatchId($match_id),
        ]);
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionMatchEvent()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        $match_id = !empty(Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : false;
        $event_id = !empty(Yii::$app->request->get('event')) ? Yii::$app->request->get('event') : false;
        $post = !empty(Yii::$app->request->post()) ? Yii::$app->request->post() : ['player' => null, 'action' => null];
        if (!$match_id) {
            return $this->redirect("/site");
        }
        $match = Matchs::getMatchsByMatchId($match_id);
        if (empty($match)) {
            return $this->redirect("/site");
        }
        if (!ApiData::SetDataByMatchId($match_id)) {
            return $this->redirect("/site");
        };
        if ($match['status_end'] == 0) {
            return $this->redirect("/site");
        }
        $match_ids = Helper::GetMatchDataDI($match);
        $players_t1 = Players::GetPlayersBayTeamIdNew($match['team1_id'], $match_id);
        $players_t2 = Players::GetPlayersBayTeamIdNew($match['team2_id'], $match_id);
        $players_number_by_match = MatchPlayersNumber::GetPlayersByMatch($match_id);
        $players_number_by_match_new = [];
        foreach ($players_number_by_match as $player) {
            $players_number_by_match_new[$player['player_id']] = $player;
        }
        $players_t1 = Helper::MarcgePlayersData($players_t1, $players_number_by_match_new);
        $players_t2 = Helper::MarcgePlayersData($players_t2, $players_number_by_match_new);
        return $this->render('match-event', [
            'match' => $match,
            'match_name' => Helper::GetMatchData($match),
            'players_t1' => Helper::PlayersOnlyNumber($players_t1),
            'players_t2' => Helper::PlayersOnlyNumber($players_t2),
            'actions_main' => Actions::getActionsMain(),
            'actions_goalkeeper' => Actions::getActionsGoalkeeper(),
            'actions_general' => Actions::getActionsGeneral(),
            'events' => Helper::NormalizationEventDataNew(Events::GetEventsByMatch($match_id, "ASC", $post), $match_ids),
            'event_data' => Events::GetEventById($event_id),
            'post_data' => $post,
            'actions' => Actions::getActionsType(),
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (!empty($_POST['LoginForm'])) {
            $api = new Api(Yii::$app->params['MyCoach']['MyCoachApi']);
            $response = $api->Login($_POST['LoginForm']);
            if (!empty($response->getHeaders()['location'])) {
                $_POST['LoginForm'] = Helper::GetUserData();
            }
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete()
    {
        $match_id = !empty(Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : false;
        if (!empty($match_id)) {
            $events = Events::GetEventsByMatchId($match_id);
            foreach ($events as $event) {
                if (!empty($event['video_src'])) {
                    $path = \Yii::$app->basePath . "/web/videos/data/" . $event['video_src'];
                    UploadVideo::DeleteLocalVideo($path);
                    if (Events::SetNullVideoSrcByEventId($event['id'])) {
                        echo $event["id"] . '<br> ';
                    }
                } else {
                    echo $event["id"] . '-video_src - Null <br>';
                }
            }
        }
    }

    public function actionInfo()
    {
        echo 'V-1';
        phpinfo();
    }

    public function actionFmpeg()
    {
        $command = "ffmpeg";
        var_dump(exec($command, $output, $res));
        var_dump($output);
        var_dump($res);

    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

}
