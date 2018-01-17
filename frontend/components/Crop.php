<?php

namespace frontend\components;


use common\models\Events;
use common\models\Matchs;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WebM;
use yii\base\Controller;

class Crop
{

    public $video_src;
    public $video_from;
    public $video_to;

    public function __construct($video_src, $video_from, $video_to)
    {
        $this->video_src = $video_src;
        $this->video_from = $video_from;
        $this->video_to = $video_to;
    }

    public function VideoCrop()
    {
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout' => 3600 * 3600,
        ]);
        $video = $ffmpeg->open($this->video_src);
        $video->filters()->clip(TimeCode::fromSeconds($this->video_from), TimeCode::fromSeconds($this->video_to));
        $video_name = md5(microtime(true));
        $video_save_phat = \Yii::$app->basePath . "/web/videos/data/";
        if ($video->save(new WMV(), $video_save_phat . $video_name . '.wmv')) {
            return $video_name . '.wmv';
        }
        return null;
    }

    public function VideoCropByExec()
    {
        $time_from = gmdate("H:i:s", $this->video_from);
        $video_name = md5(microtime(true));
        $video_name .= '.mp4';
        $video_save_phat = \Yii::$app->basePath . "/web/videos/data/";
        $command = "ffmpeg -ss " . $time_from . " -i " . $this->video_src . " -t " . $this->video_to . " -c copy " . $video_save_phat . $video_name;
        exec($command, $output, $res);
        return [
            'res' => $res,
            'video' => $video_name,
            'command' => $command
        ];
    }

    public function VideoCropByExecTwice()
    {
        $time_from = ($this->video_from - 3 <= 0) ? 0 : $this->video_from - 3;
        $time_from2 = ($time_from == 0) ? "00:00:00" : "00:00:03";
        $time_from = gmdate("H:i:s", $time_from);
        $duration = gmdate("H:i:s", $this->video_to + 3);
        $video_name = md5(microtime(true));
        $video_name .= '.mp4';
        $video_save_phat = \Yii::$app->basePath . "/web/videos/data/";
        $command = "ffmpeg -ss " . $time_from . " -i " . $this->video_src . " -t " . $duration . "  -c copy " . $video_save_phat . $video_name;
        exec($command, $output, $res);
        if ($res == 0) {
            $video_name2 = md5(microtime(true));
            $video_name2 .= '.mp4';
            $command2 = "ffmpeg -ss ".$time_from2." -i " . $video_save_phat . $video_name . " -t " . $duration . " -avoid_negative_ts 1  -c copy " . $video_save_phat . $video_name2;
            exec($command2, $output, $res);
            UploadVideo::DeleteLocalVideo($video_save_phat . $video_name);
            return [
                'res' => $res,
                'video' => $video_name2,
                'command2' => $command2,
                'command' => $command,
            ];
        }
        return false;

    }

    public static function CutVideosByMatchId($match_id)
    {
        if (!empty($match_id)) {
            $events = Events::GetEventsByMatchId($match_id);
            $video_src = Matchs::GetVideoSrcByMatchId($match_id);
            if (!empty($video_src['video_src'])) {
                foreach ($events as $event) {
                    if (empty($event->video_src)) {
                        if ($event->time_to > 0) {
                            $to = ($event->time_to - $event->time_from > 0) ? ($event->time_to - $event->time_from) : 0;
                            if ($to > 0) {
                                $crop = new Crop($video_src['video_src'], $event->time_from, $to);
                                $v_src = $crop->VideoCrop();
                                if ($v_src && Events::SaveVideoSrcByEventId($event->id, $v_src)) {
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function CutVideosByEvent($post = null)
    {
        $video_src = $post['m_video'];
        $event = $post;
        if (!empty($video_src)) {
            if (empty($event['video_src'])) {
                if ($event['time_to'] > 0) {
                    $to = ($event['time_to'] - $event['time_from'] > 0) ? ($event['time_to'] - $event['time_from']) : 0;
                    if ($to > 0) {
                        $crop = new Crop($video_src, $event['time_from'], $to);
                        $v_src = $crop->VideoCrop();
                        if ($v_src && Events::SaveVideoSrcByEventId($event['id'], $v_src)) {
                            return $v_src;
                        }
                    }
                }
            }
        }
        return false;
    }

    public static function CutVideosByEventExec($event = null)
    {
        if (!empty($event['m_video'])) {
            if (empty($event['video_src'])) {
                if ($event['time_to'] > 0) {
                    $to = ($event['time_to'] - $event['time_from'] > 0) ? ($event['time_to'] - $event['time_from']) : 0;
                    if ($to > 0) {
                        $crop = new Crop($event['m_video'], $event['time_from'], $to);
                        $res = $crop->VideoCropByExecTwice();
                        if ($res['res'] == 0 && Events::SaveVideoSrcByEventId($event['id'], $res['video'])) {
                            return $res;
                        }
                    }
                }
            }
        }
        return false;
    }
}