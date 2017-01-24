<?php


if($_POST["channel_name"] == "twitter" && $_POST["user_name"] != "slackbot"){ //Twitterチャンネルでかつbot以外
$oauth = twitter_oauth();

    if(strpos($_POST["text"],"uploaded a file") !==false){

        //①２度めの縦線を見つける
        //②その後の文字列を取得する
        //③URLを取得する
        $tweet = (string)$_POST["text"]; 
        
        try{
        $pattern_url = '/(http)(.*)(?=[|])/';
        $pattern_name = '/(jpg|png|gif)/';

        preg_match($pattern_url,$tweet,$url_matches);
        file_put_contents("debug.txt","url_matchesなう。".$url_matches[0],FILE_APPEND);//チェック
        preg_match($pattern_name,$url_matches[0],$name_matches);
        file_put_contents("debug.txt",$name_matches[0],FILE_APPEND);//チェック
        
        $picture = file_get_contents($url_matches[0]);
        file_put_contents("download".$name_matches[0],$picture);

        header('Content-Type: application/'.$name_matches[0]);
        header('Content-Disposition: attachment; filename="'.basename($url_matches[0]).'"');
        header('Content-Length: ' . filesize($url_matches[0]));
        readfile($url_matches[0]);
        file_put_contents("debug.txt",strval($http_response_header),FILE_APPEND);//チェック

        }catch(exception $e){
            file_put_contents("debug.txt",strval($e),FILE_APPEND);//チェック
        }

        try{
            $params = array(
            'status' => basename($url_matches[0]),
            '@media[]' => $name_matches[0]
             );

           //Twitterへの送信
              $twitter->postMultipart('statues/update_with_media', $params);

        }catch(exception $e){
            file_put_contents("debug.txt",strval($e),FILE_APPEND);//チェック
        }
        

    }else {
    $tweet = $_POST["text"]." sent by ". $_POST["user_name"];
    file_put_contents("system.log",$tweet);
    $posting = twitter_post($oauth,$tweet);

    }
}
    


function twitter_oauth() {
  include("TwistOAuth.phar");

//twitterAPI情報の記入
  $api_key = "ZuXuTlNeS2NK9kK5UIrcQYMEb";
  $api_secret = "LwNsxwp6imqpbSUb1ITfkwXkHnCzbQGpDKxFPGRJTrtaIiqHP6";
  $access_taken = "711855333790973953-R5XANI08WbF5orzrsBbz8PLvhZ6c6fg";
  $access_secret = "IfCC4QCmIDTdp3P4gMBIGaGSovHgXjZoipX9rd6uM56Bt";

  try{
      $twitter = new TwistOAuth($api_key,$api_secret,$access_taken,$access_secret);

    }catch(TwistExtention $e){  

        info_to_slack(strval($e));
         
    }

     return $twitter;

}

function twitter_post($oauth,$message){
  try {
        $status = $oauth->post('statuses/update', array("status"=> $message));

        info_to_slack("送信しました。");

    }catch(TwistException $e){
        info_to_slack(strval($e));
        file_put_contents("system.log",$e);
    }

}

function info_to_slack($message){
    $array_message = array("text" => $message);
    echo json_encode($array_message);
}

function get_picture_uri($path,$filetype){

 
}
?>