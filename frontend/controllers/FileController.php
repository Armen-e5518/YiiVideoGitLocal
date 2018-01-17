<?php
namespace frontend\controllers;

ini_set('upload_max_filesize', 1);
ini_set('post_max_size', 2000000000);
ini_set('max_execution_time', -1);
set_time_limit(-1);
ini_set('memory_limit', 2000000000);

use common\models\Actions;
use common\models\Events;
use common\models\Matchs;
use frontend\components\ApiData;
use frontend\components\Crop;
use frontend\components\Helper;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class FileController extends Controller
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
        Yii::$app->params['domain'] = Yii::$app->urlManager->getHostInfo();
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        if (Yii::$app->request->post()) {
            $file_phat = Yii::getAlias("@app") . "/web/videos/";
            $post = Yii::$app->request->post();
            $target_url = Yii::$app->params['MyCoach']['MyCoachVideoServer'];
            $file_name_with_full_path = $file_phat . $post['file'];
            if (function_exists('curl_file_create')) {
                $cFile = curl_file_create($file_name_with_full_path);
            } else {
                $cFile = '@' . realpath($file_name_with_full_path);
            }
            $post = array('videos' => $cFile);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $target_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 240000);
            curl_exec($ch);
            curl_close($ch);
        }

    }

    public function actionFileUpload()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        if (!empty($_FILES['fileToUpload']) && $_FILES['fileToUpload']['size'] > 0) {
            $match_id = !empty(Yii::$app->request->post('match')) ? Yii::$app->request->post('match') : 1;
            $cfile = $this->getCurlValue($_FILES['fileToUpload']['tmp_name'], 'video/mp4', $_FILES['fileToUpload']['name']);
            $data = array('videos' => $cfile);
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => Yii::$app->params['MyCoach']['MyCoachVideoServer'],
                CURLOPT_RETURNTRANSFER => true,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_HEADER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
            );
//            curl_setopt_array($ch, $options);
//            $result = curl_exec($ch);
//            $header_info = curl_getinfo($ch, CURLINFO_HEADER_OUT);
//            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
//            $header = substr($result, 0, $header_size);
//            $body = substr($result, $header_size);
//            curl_close($ch);
//            var_dump($result);
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            curl_close($ch);
            $video = explode('Location:', $result);
            if (!empty($video[1]) && !empty($match_id)) {
                if (Matchs::SetVideoSrcByMatchId($match_id, $video[1])) {
                    Yii::$app->session->setFlash('success', 'You have successfully.');
                    return $this->redirect("/site/match?id=" . $match_id);
                }
            }
        }
        Yii::$app->session->setFlash('error', 'Error...');
        return $this->redirect("/site");
    }

    public function actionUpload()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
//        $upload_max_size = ini_get('upload_max_filesize');
//        $max_input_time = ini_get('max_input_time');
//        $post_max_size = ini_get('post_max_size');
//        $memory_limit = ini_get('memory_limit');
//        print_r("upload_max_filesize-" . $upload_max_size);
//        echo '<br>';
//        print_r("max_input_time-" . $max_input_time);
//        echo '<br>';
//        print_r("post_max_size-" . $post_max_size);
//        echo '<br>';
//        print_r("memory_limit-" . $memory_limit);

        $match_id = !empty(Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : false;
        ApiData::SetDataByMatchId($match_id);
        $match = Matchs::getMatchsByMatchId($match_id);
        if (empty($match)) {
            Yii::$app->session->setFlash('error', 'Error...');
            return $this->redirect("/site");
        }
        return $this->render('upload', [
            'match_id' => $match_id,
            'match' => $match,
        ]);
    }

    public function getCurlValue($filename, $contentType, $postname)
    {
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $contentType, $postname);
        }
        $value = "@{$filename};filename=" . $postname;
        if ($contentType) {
            $value .= ';type=' . $contentType;
        }
        return $value;
    }

    public function actionCrop()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        $match_id = !empty(Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : false;
        if (!empty($match_id)) {
            $events = Events::GetEventsByMatchId($match_id);
            $video_src = Matchs::GetVideoSrcByMatchId($match_id);
            if (!empty($video_src['video_src'])) {
                foreach ($events as $event) {
                    if ($event->video)
                        if ($event->time_to > 0) {
                            $to = ($event->time_to - $event->time_from > 0) ? ($event->time_to - $event->time_from) : 0;
                            Helper::out($to);
                            if ($to > 0) {
                                $crop = new Crop($video_src['video_src'], $event->time_from, $to);
                                $v_src = $crop->VideoCrop();
                                if ($v_src && Events::SaveVideoSrcByEventId($event->id, $v_src)) {

                                }
                            }
                        }
                }
            } else {
                echo "no Video";
            }
        }
        echo "No";
        return false;
    }

    public function actionCutVideos()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        $match_id = !empty(\Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : false;
        $match = Matchs::getMatchsByMatchId($match_id);
        if (!$match_id || empty($match)) {
            return $this->redirect("/site");
        }
        $events = Events::GetEventsByMatchByVideo($match_id);
        return $this->render('cut-videos', [
            'events' => $events,
            'events_count' => count($events),
            'events_done' => Events::GetCountEventsByMatchAndVideo($match_id)['count'],
            'match' => $match,
            'actions' => Actions::getActionsType(),
        ]);
    }

    public function actionUploadVideos()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        $match_id = !empty(\Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : false;
        $match = Matchs::getMatchsByMatchId($match_id);
        if (!$match_id || empty($match)) {
            return $this->redirect("/site");
        }
        $events = Events::GetEventsByMatchByVideo($match_id);
        return $this->render('upload-videos', [
            'events' => $events,
            'match' => $match,
            'events_count' => count($events),
            'events_done' => Events::GetCountEventsByMatchAndVideoServer($match_id)['count'],
            'actions' => Actions::getActionsType(),

        ]);
    }

    public function actionDownload()
    {
        $post['match_id'] = 8452;
        if (!empty($post['match_id'])) {
            $path = \Yii::$app->basePath . "/web/videos/src/";
            Helper::DownloadVideoByMatchId($post['match_id'], $path);

        }
    }

    public function actionTest()
    {
        session_start();
        var_dump( $_SESSION['file_info1']);
    }

    public function progress($resource, $download_size, $downloaded, $upload_size, $uploaded)
    {
        file_put_contents("/home/www/dev-mycoach/www/frontend/web/src.txt", "Hello World. Testing!" . $downloaded);
        if ($download_size > 0)
            echo $downloaded / $download_size * 100;
        ob_flush();
        flush();
        sleep(1); // just to see effect
    }
}
