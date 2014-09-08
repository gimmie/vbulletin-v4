<?php
require_once(DIR . '/plugins/gimmie/functions.php');
require_once(DIR . '/plugins/gimmie/OAuth.php');

$my_player_uid        = $vbulletin->userinfo['userid'];

if ($vbulletin->options['gimmie_use_email'] == 1 )
{
  $my_player_uid        = $vbulletin->userinfo['email'];
}

$key            = $gimmie['gimmie_key']; 
$secret           = $gimmie['gimmie_secret']; 
$access_token         = $my_player_uid;
$access_token_secret    = $secret;
$params           = array();
$sig_method         = new OAuthSignatureMethod_HMAC_SHA1();
$consumer         = new OAuthConsumer($key, $secret, NULL);
$token            = new OAuthConsumer($access_token, $access_token_secret);
$forumarray = array();

if ($vbulletin->options['gimmie_trigger_specificforum'] != "")
{
$forumarray = explode(",", $vbulletin->options['gimmie_trigger_specificforum']);
$forumarray = array_map('trim',$forumarray);
}

if (in_array($threadinfo[forumid], $forumarray) || $vbulletin->options['gimmie_trigger_specificforum'] == "")
{   

if ($vbulletin->options['gimmie_enable_global'] == 1 && $vbulletin->options['gimmie_trigger_perpollpost'] == 1)
{

$endpoint           = "https://api.gimmieworld.com/1/trigger.json?async=webnotify&source_uid=".$vbulletin->options['bburl']."&event_name=" . $gimmie['gimmie_trigger_perpollpost'];
$acc_req          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $endpoint, $params);
$acc_req->sign_request($sig_method, $consumer, $token);

fetch_web_data($acc_req->to_url());
return;

}
}

?>