<?php
require_once(DIR . '/plugins/gimmie/functions.php');
require_once(DIR . '/plugins/gimmie/OAuth.php');

$my_player_uid        = $vbulletin->userinfo['email'];

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

$threadsql  = $vbulletin->db->query_read("SELECT * FROM " . TABLE_PREFIX . "thread WHERE `threadid` = '" . $_POST['threadid'] . "'");
$thread   = $vbulletin->db->fetch_array($threadsql);


if ($vbulletin->options['gimmie_enable_global'] == 1 && $vbulletin->options['gimmie_trigger_perpost'] == 1 && $thread['postusername'] != $vbulletin->userinfo['username'] )
{
if ($vbulletin->options['gimmie_trigger_word'] != "")
{
$wordarray = explode(",", $vbulletin->options['gimmie_trigger_word']);
$wordarray = array_map('trim',$wordarray);

$wordsearch1 = implode("\W|\W",$wordarray);
$pattern1 = "\W".$wordsearch1."\W";

$wordsearch2 = implode("\W|^",$wordarray);
$pattern2 = "^".$wordsearch2."\W";

$wordsearch3 = implode("$|\W",$wordarray);
$pattern3 = "\W".$wordsearch3."$";

$pattern = "/(".$pattern1."|".$pattern2."|".$pattern3.")/i";

if (preg_match($pattern, $newpost['message']))
{
  $endpoint           = "https://api.gimmieworld.com/1/trigger.json?async=webnotify&source_uid=".$vbulletin->options['bburl']."&event_name=" . $gimmie['gimmie_trigger_perpost'];
  $acc_req          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $endpoint, $params);
  $acc_req->sign_request($sig_method, $consumer, $token);

  file_get_contents($acc_req->to_url());
  return;
}
}
else
{
$endpoint           = "https://api.gimmieworld.com/1/trigger.json?async=webnotify&source_uid=".$vbulletin->options['bburl']."&event_name=" . $gimmie['gimmie_trigger_perpost'];
$acc_req          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $endpoint, $params);
$acc_req->sign_request($sig_method, $consumer, $token);

file_get_contents($acc_req->to_url());
return;

}

}

elseif($vbulletin->options['gimmie_enable_global'] == 1 && $vbulletin->options['gimmie_trigger_perpostown'] == 1 && $thread['postusername'] == $vbulletin->userinfo['username'])
{

if ($vbulletin->options['gimmie_trigger_word'] != "")
{
$wordarray = explode(",", $vbulletin->options['gimmie_trigger_word']);
$wordarray = array_map('trim',$wordarray);

$wordsearch1 = implode("\W|\W",$wordarray);
$pattern1 = "\W".$wordsearch1."\W";

$wordsearch2 = implode("\W|^",$wordarray);
$pattern2 = "^".$wordsearch2."\W";

$wordsearch3 = implode("$|\W",$wordarray);
$pattern3 = "\W".$wordsearch3."$";

$pattern = "/(".$pattern1."|".$pattern2."|".$pattern3.")/i";

if (preg_match($pattern, $newpost['message']))
{
  $endpoint           = "https://api.gimmieworld.com/1/trigger.json?async=webnotify&source_uid=".$vbulletin->options['bburl']."&event_name=" . $gimmie['gimmie_trigger_perpostown'];
  $acc_req          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $endpoint, $params);
  $acc_req->sign_request($sig_method, $consumer, $token);

  file_get_contents($acc_req->to_url());
  return;
       
}
}
else
{
$endpoint           = "https://api.gimmieworld.com/1/trigger.json?async=webnotify&source_uid=".$vbulletin->options['bburl']."&event_name=" . $gimmie['gimmie_trigger_perpostown'];
$acc_req          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $endpoint, $params);
$acc_req->sign_request($sig_method, $consumer, $token);

file_get_contents($acc_req->to_url());
return;

}
}    
}

?>