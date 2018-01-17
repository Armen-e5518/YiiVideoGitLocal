<?php

namespace frontend\components;


class DownloadFile
{

    private $glob_file;
    private $local_file;

    public $file_size;

    public function __construct($local_file = null, $glob_file = null)
    {
        $this->glob_file = $glob_file;
        $this->local_file = $local_file;
    }

    public function Download()
    {
        if (!empty($this->glob_file) && !empty($this->local_file)) {
            return file_put_contents($this->local_file, fopen($this->glob_file, 'r'));
        }
        return false;
    }

    public function DownloadCurl()
    {

        if (!empty($this->glob_file) && !empty($this->local_file)) {
            ob_start();
            ob_flush();
            flush();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->glob_file);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, [$this, 'progress']);
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);

//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, -1);
//            curl_setopt($ch, CURLOPT_TIMEOUT, -1);
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//            curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
//            curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
//            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
//            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function ($resource, $download_size, $downloaded, $upload_size, $uploaded) {
//                $_SESSION['file_info'] = [
//                    'download_size' => $download_size,
//                    'downloaded' => $downloaded,
//                ];
//                file_put_contents('/home/www/dev-mycoach/www/frontend/web/src.txt',$downloaded);
//            });
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            $data = curl_exec($ch);
            curl_close($ch);
            $file = fopen($this->local_file, "w+");
            fputs($file, $data);
            ob_flush();
            flush();
            if (fclose($file)) {
                return $this->local_file;
            }
        }
        return false;
    }

    public function progress($resource, $download_size, $downloaded, $upload_size, $uploaded)
    {
        $session = \Yii::$app->session;
        file_put_contents('/home/www/dev-mycoach/www/frontend/web/src.txt', $downloaded);
        $session['file_info'] = [
            'download_size' => $download_size,
            'downloaded' => $downloaded,
        ];
        ob_flush();
        flush();
    }
}


