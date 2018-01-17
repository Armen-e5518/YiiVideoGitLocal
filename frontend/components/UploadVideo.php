<?php

namespace frontend\components;

use common\models\Matchs;
use common\models\Events;


class UploadVideo
{

    public static function UploadVideoByMatch($post)
    {
        $event_id = $post['id'];
        if (!empty($event_id)) {
            $file_name_with_full_path = \Yii::$app->basePath . "/web/videos/data/" . $post['video_src'];
            if (function_exists('curl_file_create')) {
                $cfile = curl_file_create($file_name_with_full_path);
            } else {
                $cfile = '@' . realpath($file_name_with_full_path);
            }
            $data = array('videos' => $cfile);
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => \Yii::$app->params['MyCoach']['MyCoachVideoServer'],
                CURLOPT_RETURNTRANSFER => true,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_HEADER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
            );
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            curl_close($ch);
            $video = explode('Location:', $result);
            if (!empty($video[1]) && !empty($event_id)) {
                if (Events::SetVideoSrcByEventId($event_id, $video[1])) {
                    self::DeleteLocalVideo($file_name_with_full_path);
                    return $video[1];
                }
            }
            return false;
        }
        return false;
    }

    public static function DeleteVideoInServer($file_full_path = null)
    {
        if (!empty($file_full_path)) {
            exec('curl -X "DELETE" ' . $file_full_path, $res);
            return $res;
        }
    }

    public static function DeleteLocalVideo($file_full_path)
    {
        if (!empty($file_full_path)) {
            if (file_exists($file_full_path)) {
                if (is_file($file_full_path)) {
                    if (unlink($file_full_path)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
//"/home/www/dev-mycoach/www/frontend/web/videos/data/00d8539d560ebcfa44f840424970f4e6.mp4"
//"/home/www/dev-mycoach/www/frontend/web/videos/data/b14536e9b30897fe168c741890dfef04.mp4"