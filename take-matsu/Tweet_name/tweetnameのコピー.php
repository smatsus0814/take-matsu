<?php


if($_POST["channel_name"] == "twitter"){
    echo json_encode(array("text" => "HTTP Post リクエストを受信しました"));
    $tweet = $_POST['text']+"sent by "+ $_POST['user_name'];

    $oauth = twitter_oauth();
    $posting = twitter_post($oauth,$tweet);

    info_to_slack('送信完了');
    
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
        $status = $oauth->post('status/update', array("status"=> $message));

        info_to_slack("送信しました。");

    }catch(TwistException $e){
        info_to_slack(strval($e));
    }

}

function info_to_slack($message){
    $array_message = array("text" => $message);
    echo json_encode($array_message);
}

?>