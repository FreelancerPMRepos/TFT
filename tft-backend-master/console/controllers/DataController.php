<?php
namespace console\controllers;
use yii\console\Controller;
use Yii;
use yii\imagine\Image;
use Imagine\Image\ImageInterface;
use Imagick;
class DataController extends Controller
{
    public function behaviors()
    {
        return [
            'cronLogger' => [
                'class' => 'yii2mod\cron\behaviors\CronLoggerBehavior',
                'actions' => ['*']
            ],
            // Example of usage the `MutexConsoleCommandBehavior`
            'mutexBehavior' => [
               'class' => 'yii2mod\cron\behaviors\MutexConsoleCommandBehavior',
               'mutexActions' => ['*']
            ]
        ];
    } 
    
    private function unlinkFile($file){
        if(file_exists($file)){
            return unlink($file);
        }
        return false;
    }
    public function getFileFromUrl($url,$file_name){
        $path               = Yii::$app->basePath.'/dummy_data/';
        $newfname           = $path . $file_name;   
        $output_video_file  = $newfname;
        $file = fopen ($url, "rb");
        if ($file) {
            $newf = fopen ($newfname, "wb");        
            if ($newf)
            while(!feof($file)) {
                fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
            }
        }        
        if ($file) {
            fclose($file);
        }        
        if ($newf) {
            fclose($newf);
        }
        return $newfname;
    }
    public function getArray(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.tiktok.com/share/item/list?secUid=&id=771611&type=3&count=100&minCursor=-1&maxCursor=0&_signature=s-QQZBAT7oWACUY8nT01MLPkEH&shareUid=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Postman-Token: 4615fdf7-0020-1208-1917-d6db03f871dd",
            "accept: application/json, text/plain, */*",
            "accept-encoding: gzip, deflate, br",
            "accept-language: en,de-CH;q=0.9,de;q=0.8,en-US;q=0.7,en-GB;q=0.6",
            "authority: www.tiktok.com",
            "cookie: _ga=GA1.2.1067524687.1568177617; _fbp=fb.1.1568177616905.385710199; _gid=GA1.2.690619095.1569846111; _gat_gtag_UA_144727112_1=1; ak_bmsc=675465AAD175B75A2858820B578484DB312CC20F3B2200007511935D209DA73D~pl6FOV1q6Ivjn/8zQPYuT07oTRMa8BhNMvQ4fZZBOv77VSVLbuAnMHMl6tcklAhfHudEL872U2bKMfbO1HenO/SoAa0aUgmzmW1nMXHxDV2VaosH7G5/L2UduQ8REeiXj4inKYH+e3U9ySVhCa3Du7RD/6MpQEsb7HDc6uxPQPF8xjDKn/jv675oVfnM6L5bv2Hk8P4edeQ5JmGhLMN9D4bhXO0timxcDv3JksaNCvjicRJsmRc5hZ08DemWTRU9Xv; tt_webid=6742752287858755073; bm_sv=DCA5DB302B132651F19287361D4AFF38~B83l3XLfseRiVW3/6xND2JY9uL6zLLb4SA+s2yGj/Hp81ZS8TPfqsaprvp5Vr2pq2MMiyR7W6u1YMrq+CcpSsyjI2fIBwdB8A+cJ1NZiDwdPuKjyuIMU3PooLsm3q+HTTXSPhfC5b4cdsw2qmDf4v5tAF6bMLOKNI+kLHxV5x7A=",
            "referer: https://www.tiktok.com/tag/nigeria?langCountry=en",
            "sec-fetch-mode: cors",
            "sec-fetch-site: same-origin",
            "user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36"
            ),
          ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          return json_decode($response,true);
        }
    }
    //########## command : php yii data/get-videos
    public function actionGetVideos()
    {
     
        $data = $this->getArray();
        $i = 0;
        $user_id  = 44;
        foreach($data['body']['itemListData'] as $k => $ele){
            $video      =   $ele['itemInfos'];
            $video['text'] = substr($video['text'], 90);
            $slotname           = time();  
            
            $baseName               =  date('Y').'_'.date('m').'_'.date('d').'_'.rand(time(),time()+86400);
            $video_name             =  $baseName.'.mp4';   
            $image_name             =  $baseName.'.jpg';   

            $video_dir              =  Yii::$app->basePath.'/../img_assets/videos/';

            $output_video_file      =  $video_dir.$video_name;
            $input_video_file       =  $this->getFileFromUrl($video['video']['urls'][0],$video_name);                   
            
            $ffmpeg         = \FFMpeg\FFMpeg::create();
            $videoObj       = $ffmpeg->open($input_video_file);
        
            $watermark      = Yii::$app->basePath.'/../img_assets/videos/../watermark.png';
            $thumbnail      = Yii::$app->basePath.'/../img_assets/videos/'.$image_name;
    
            $clip           = $videoObj->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds(1), \FFMpeg\Coordinate\TimeCode::fromSeconds(60));
            
            /* convert video to 360p and Fast start */
            shell_exec("ffmpeg -i $input_video_file -movflags +faststart -vf scale=-2:360 $output_video_file 2>&1");

            /* lets grab the thumb image */
            $frame          =   $videoObj->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
            $frame->save($thumbnail);

            /* Quality reduce for the video poster */
            $imagine = Image::getImagine();
            $imageImagine = $imagine->open($thumbnail);
            $iWidth = $imageImagine->getSize()->getWidth();
            $iHeight = $imageImagine->getSize()->getHeight();
            Image::$thumbnailBackgroundColor = '#000';
            Image::thumbnail($thumbnail, 400, 710, ImageInterface::THUMBNAIL_INSET)->save(Yii::getAlias($thumbnail), ['quality' => 30]);

            $imagick = new Imagick($thumbnail);
            $imagick->gaussianBlurImage(5, 8, Imagick::CHANNEL_DEFAULT);
            $imagick->writeImage($thumbnail);

            $storage            =  Yii::$app->get('storage');
            $storage->upload('videos/'.$video_name,$output_video_file);
            $storage->upload('videos/'.$image_name,$thumbnail);    
            
            
            $model              =  new \common\models\Video;
            $model->video_image =   'http://winmtc-11d1c.kxcdn.com/videos/'.$image_name;
            $model->video_url   =   'http://winmtc-11d1c.kxcdn.com/videos/'.$video_name;
            $model->video_name  =   $video_name;

            $model->description       =  '';
            $model->disclaimer        =  '';
            $model->category_id       =  2;
            // $model->category_id       =  rand(1,5);
            $model->disclaimer        =  '';
            $model->country_id        =  \Yii::$app->params['default_country_id'];
            $model->user_id           =  $user_id;
            $model->created_by        =  $user_id;
            $model->updated_by        =  $user_id;
            $model->video_status      =  1;
            if($model->save(false)){
                $this->unlinkFile($output_video_file); 
                $this->unlinkFile($thumbnail); 
                $this->unlinkFile($input_video_file); 
            }else{
                $model->validate();
                // throw new \yii\web\HttpException(404, json_encode($model->errors));
            }
            $i++;
        }
        
        
    }

}
