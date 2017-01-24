<?php

require "./vendor/autoload.php";
require "./config.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token, $access_token_secret);

$content = $connection->get("account/verify_credentials");

$statuses = $connection->get("statuses/home_timeline", ["count" => 25, "exclude_replies" => true]);

echo $statuses;

?>