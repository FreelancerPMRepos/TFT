<?PHP
ini_set('display_errors', 'on');
ini_set('memory_limit', '4G');
ini_set('max_execution_time', 0);
error_reporting(E_ALL);
ob_implicit_flush(true);
ignore_user_abort(true);
ob_end_flush();


$dir = "/var/www/html/temp/";

/*** cycle through all files in the directory ***/
foreach (glob($dir."*") as $file) {
    /*** if file is 24 hours (86400 seconds) old then delete it ***/
    if(time() - filectime($file) > (86400 / 6)){
        unlink($file);
    }
}

if(!isset($_GET['url'])) {
    echo 'Please specify url';
    exit;
} else {
    $url = $_GET['url'];
}


/* Creating status file first */
$response = serialize(['status' => 'processing', 'updated' => date('Y-m-d H:i:s')]);
$statusFile = '/var/www/html/status/'.md5($url).'.json';
writeToFile($statusFile, $response);


/* log array will keep everything in it :D */
$logArray = [];


$logArray['main']['start'] = date('Y-m-d H:i:s');

$ffmpeg_path = 'ffmpeg'; //or: /usr/bin/ffmpeg , or /usr/local/bin/ffmpeg - depends on your installation (type which ffmpeg into a console to find the install path)
// $vid = '/var/www/html/temp/2e89f26832cd308fea5323afdb9af119.mp4';
// $video_attributes = _get_video_attributes($vid, $ffmpeg_path);
// print_r($video_attributes);
// exit;
//$url = 'https://www.webllywood.com/assets/upload/all_post/5a8e31db276ce1519268315.mp4';

$uniqueId = md5($url);
$newfilename = $uniqueId .'.mp4';
$logArray['download']['start'] = date('Y-m-d H:i:s');
$file = get_file($url, '/var/www/html/temp/', $newfilename);
$logArray['download']['end'] = date('Y-m-d H:i:s');
// $vid = '/var/www/html/video/2.mp4'; //Replace here!
$vid = '/var/www/html/temp/'. $newfilename;
$vidWithoutExt = '/var/www/html/temp/'. $uniqueId;
$logArray['video']['path'] = $vid;
// $videoSizes = [
//     1080 => 'time ffmpeg -i __FILE_NAME__ -vcodec libvpx -qmin 0 -qmax 50 -pix_fmt yuv420p -b:v 4500k -minrate 4500k -maxrate 9000k -bufsize 9000k -vf scale=-1:1080 -acodec libvorbis -b:a 128k __FILE_NAME___1080.webm', 
//     720 => 'time ffmpeg -i __FILE_NAME__ -vcodec libvpx -qmin 0 -qmax 50 -pix_fmt yuv420p -b:v 2500k -minrate 1500k -maxrate 4000k -bufsize 5000k -vf scale=-1:720 -acodec libvorbis -b:a 128k __FILE_NAME___720.webm', 
//     480 => 'time ffmpeg -i __FILE_NAME__ -vcodec libvpx -qmin 0 -qmax 50 -pix_fmt yuv420p -b:v 1000k -minrate 500k -maxrate 2000k -bufsize 2000k -vf scale=-1:480 -acodec libvorbis -b:a 128k __FILE_NAME___480.webm', 
//     320 => 'time ffmpeg -i __FILE_NAME__ -vcodec libvpx -qmin 0 -qmax 50 -pix_fmt yuv420p -b:v 750k -minrate 400k -maxrate 1000k -bufsize 1500k -vf scale=-1:360 -acodec libvorbis -b:a 128k __FILE_NAME___360.webm'
// ];
// $videoSizes = [
//     1080 => 'time ffmpeg -i __FILE_NAME__ -vcodec libx264 -qmin 0 -qmax 50 -pix_fmt yuv420p -b:v 4500k -minrate 4500k -maxrate 9000k -bufsize 9000k -vf scale=-1:1080 -acodec aac -b:a 128k -strict -2 __FILE_NAME___1080.mp4', 
//     720 => 'time ffmpeg -i __FILE_NAME__ -vcodec libx264 -qmin 0 -qmax 50 -pix_fmt yuv420p -b:v 2500k -minrate 1500k -maxrate 4000k -bufsize 5000k -vf scale=-1:720 -acodec aac -b:a 128k -strict -2 __FILE_NAME___720.mp4', 
//     // 480 => 'time ffmpeg -i __FILE_NAME__ -vcodec libx264 -qmin 0 -qmax 50 -pix_fmt yuv420p -b:v 1000k -minrate 500k -maxrate 2000k -bufsize 2000k -vf scale=-1:480 -acodec aac -b:a 128k __FILE_NAME___480.mp4', 
//     320 => 'time ffmpeg -i __FILE_NAME__ -vcodec libx264 -qmin 0 -qmax 50 -pix_fmt yuv420p -b:v 750k -minrate 400k -maxrate 1000k -bufsize 1500k -vf scale=-1:360 -acodec aac -b:a 128k -strict -2 __FILE_NAME___360.mp4'
// ];

if(!isset($_REQUEST['single'])) {
    $videoSizes = [
        1080 => 'time ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -ac 2 -ab 128k -vcodec libx264 -f mp4 -filter:v scale="trunc(oh*a/2)*2:1080" __FILE_NAME___1080.mp4', 
        720 => 'time ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -vcodec libx264 -vf scale="trunc(oh*a/2)*2:720" __FILE_NAME___720.mp4', 
        480 => 'time ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -vcodec libx264 -vf scale="trunc(oh*a/2)*2:480" __FILE_NAME___480.mp4',
        // 360 => 'time ffmpeg -i __FILE_NAME__ -vcodec libx264 -ac 2 -ab 128k scale=-1:1080 -acodec aac -strict -2 -crf 22 -f mp4 __FILE_NAME___360.mp4'
        320 => 'ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -ac 2 -ab 128k -vcodec libx264 -f mp4 -filter:v scale="trunc(oh*a/2)*2:320" __FILE_NAME___320.mp4',
        180 => 'ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -ac 2 -ab 128k -vcodec libx264 -f mp4 -filter:v scale="trunc(oh*a/2)*2:180" __FILE_NAME___180.mp4',
    ];
} else {
    $videoSizes = [
        // 1080 => 'time ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -ac 2 -ab 128k -vcodec libx264 -f mp4 -filter:v scale="trunc(oh*a/2)*2:1080" __FILE_NAME___1080.mp4', 
        // 720 => 'time ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -vcodec libx264 -vf scale="trunc(oh*a/2)*2:720" __FILE_NAME___720.mp4', 
        // 480 => 'time ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -vcodec libx264 -vf scale="trunc(oh*a/2)*2:480" __FILE_NAME___480.mp4',
        // 360 => 'time ffmpeg -i __FILE_NAME__ -vcodec libx264 -ac 2 -ab 128k scale=-1:1080 -acodec aac -strict -2 -crf 22 -f mp4 __FILE_NAME___360.mp4'
        320 => 'ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -ac 2 -ab 128k -vcodec libx264 -f mp4 -filter:v scale="trunc(oh*a/2)*2:320" __FILE_NAME___320.mp4',
        // 180 => 'ffmpeg -i __FILE_NAME__.mp4 -acodec aac -strict -2 -ac 2 -ab 128k -vcodec libx264 -f mp4 -filter:v scale="trunc(oh*a/2)*2:180" __FILE_NAME___180.mp4',
    ];
}

$logArray['video']['sizes'] = $videoSizes;

 if (file_exists($vid)) {
    $logArray['video']['exists'] = 'Yes';
    $videoSize = 10000;
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $vid); // check mime type
    finfo_close($finfo);

    $logArray['video']['finfo'] = $finfo;
    $logArray['video']['mime'] = $mime_type;

    if (preg_match('/video\/*/', $mime_type)) {

        $logArray['video']['mime_isvideo'] = 'Yes';
        $video_attributes = _get_video_attributes($vid, $ffmpeg_path);
        $logArray['video']['attributes'] = $video_attributes;
        //print_r('Codec: ' . $video_attributes['codec'] . '<br/>');
        $videoSize = (int)$video_attributes['height'];
        // echo 'Video Size: '.$videoSize.'<br>';

        foreach($videoSizes as $k=>$vsize) {
            // echo $videoSize.' > '.$vsize. ' = '.($videoSize > $vsize ? 1: 0).'<br>';
            if($videoSize < $k) {
                unset($videoSizes[$k]);
            }
        }
        
        $logArray['video']['process_sizes'] = $videoSizes;
        //$videoSizes = array_values($videoSizes);
        
        $filesDone = array();
        foreach($videoSizes as $k=>$toSize) {

            /* Writing update :)*/
            $response = serialize(['status' => 'processing', 'updated' => date('Y-m-d H:i:s'), 'json_data' => $logArray]);
            var_dump($response);
            writeToFile($statusFile, $response);
        

            $command = str_replace('__FILE_NAME__', $vidWithoutExt, $toSize);

            $logArray['video']['process'][$toSize]['cmd'] = $command;
            $logArray['video']['process'][$toSize]['start'] = date('Y-m-d H:i:s');

            $result = liveExecuteCommand($command);

            $logArray['video']['process'][$toSize]['end'] = date('Y-m-d H:i:s');
            
            $logArray['video']['process'][$toSize]['result'] = $result;

            $doneUrl = 'http://159.203.84.90/temp/'. $uniqueId . '_'.$k.'.mp4';

            echo 'File checking........';
            var_dump(file_exists('/var/www/html/temp/'. $uniqueId . '_'.$k.'.mp4')); echo '<br>';
            var_dump(is_readable('/var/www/html/temp/'. $uniqueId . '_'.$k.'.mp4')); echo '<br>';
            var_dump(filesize('/var/www/html/temp/'. $uniqueId . '_'.$k.'.mp4')); echo '<br>';
            if(filesize('/var/www/html/temp/'. $uniqueId . '_'.$k.'.mp4') > 0) {
                $filesDone[$k] = $doneUrl;
            }
            $logArray['video']['process'][$toSize]['file'] = $doneUrl;

            /* Writing update :)*/
            $response = serialize(['status' => 'processing', 'updated' => date('Y-m-d H:i:s'), 'json_data' => $logArray]);
            
            writeToFile($statusFile, $response);
        }

        $logArray['main']['end'] = date('Y-m-d H:i:s');


        /* Writing success :)*/
        echo 'Before Response <br>';
        $response = serialize(['status' => 'completed', 'videos' => $filesDone, 'json_data' => $logArray]);
        var_dump($response);
        writeToFile($statusFile, $response);
        echo 'Complted';
        exit;

        // $filesDone = array(
        //     '1080' => 'http://159.203.84.90/temp/'. $newfilename . '_1080.mp4',
        //     '720' => 'http://159.203.84.90/temp/'. $newfilename . '_720.mp4',
        //     '320' => 'http://159.203.84.90/temp/'. $newfilename . '_320.mp4',
        // );

        // print_r('Duration: ' . $video_attributes['hours'] . ':' . $video_attributes['mins'] . ':'
        //         . $video_attributes['secs'] . '.' . $video_attributes['ms'] . '<br/>');

        // print_r('Size:  ' . _human_filesize(filesize($vid)));

    } else {
        $logArray['error'] = 'Mime couldnt found video';
        /* Writing failure :)*/
        $response = serialize(['status' => 'failed', 'json_data' => $logArray]);
        writeToFile($statusFile, $response);
        exit;
    }
} else {   
    $logArray['error'] = 'File not found after downloading';

    /* Writing failure :)*/
    $response = serialize(['status' => 'failed', 'json_data' => $logArray]);
    writeToFile($statusFile, $response);
    exit;
}

function _get_video_attributes($video, $ffmpeg) {
    
    $command = $ffmpeg . ' -i ' . $video . ' -vstats 2>&1';
    $output = shell_exec($command);

    $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/";
    $regex_sizes1 = "/Video: ([^\r\n]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/";
    // preg_match($regex_sizes, $output, $regs);
    // preg_match($regex_sizes1, $output, $regs1);

    // var_dump($output);
    // var_dump($regs);
    // var_dump($regs1);
    // exit;
    if (preg_match($regex_sizes, $output, $regs)) {
        $codec = $regs [1] ? $regs [1] : null;
        $width = $regs [3] ? $regs [3] : null;
        $height = $regs [4] ? $regs [4] : null;
    }

    if(!isset($width) || $width == null) {
        if (preg_match($regex_sizes1, $output, $regs)) {
            $codec = $regs [1] ? $regs [1] : null;
            $width = $regs [3] ? $regs [3] : null;
            $height = $regs [4] ? $regs [4] : null;
        }
    
    }

    $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
    if (preg_match($regex_duration, $output, $regs)) {
        $hours = $regs [1] ? $regs [1] : null;
        $mins = $regs [2] ? $regs [2] : null;
        $secs = $regs [3] ? $regs [3] : null;
        $ms = $regs [4] ? $regs [4] : null;
    }

    return array(
        'codec' => $codec,
        'width' => $width,
        'height' => $height,
        'hours' => $hours,
        'mins' => $mins,
        'secs' => $secs,
        'ms' => $ms
    );
}

function _human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}


/* Save file to local */

function get_file($file, $local_path, $newfilename) 
{ 

    $ch = curl_init();
    $fp = fopen ($local_path.$newfilename, 'w+');
    $ch = curl_init($file);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    return true;
}


function liveExecuteCommand($cmd)
{

    while (@ ob_end_flush()); // end all output buffers if any

    $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

    $live_output     = "";
    $complete_output = "";

    while (!feof($proc))
    {
        $live_output     = fread($proc, 4096);
        $complete_output = $complete_output . $live_output;
       // echo date('Y-m-d H:i:s')." $live_output <br>";
      //  @ flush();
    }

    pclose($proc);

    // get exit status
    preg_match('/[0-9]+$/', $complete_output, $matches);

    // return exit status and intended output
    return array (
                    'exit_status'  => intval($matches[0]),
                    'output'       => str_replace("Exit status : " . $matches[0], '', $complete_output)
                 );
}


function writeToFile($file, $log){
    echo '<br><br> ********************* Writing to log *********************** <br>';
    
    echo ' file name: '. $file. '<br>';
    $method = (file_exists($file)) ? 'wa+' : 'w';
    echo ' Method : '.$method.'<br>';
    echo ' Log : '.$log.'<br>';
    $fh = fopen($file, $method);
    echo $file;
    if(!file_exists($file)) {
        echo 'Log file is not exists.!!!!!!!'; exit;
    } else if(!is_readable($file)) {
        echo 'Log file is not readable.!!!!!!!'; exit;
    } else if(!is_writable($file)) {
        echo 'Log file is not writable.!!!!!!!'; exit;
    } else {
        echo ' File is readable';
    }

    fwrite($fh, $log);
    fclose($fh);
    echo '*********************************** Write done ******************************<br>';
}
