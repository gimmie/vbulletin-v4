<?php
require_once(DIR . '/plugins/gimmie/functions.php');
require_once(DIR . '/plugins/gimmie/OAuth.php');

$my_player_uid        = $vbulletin->userinfo['userid'];
$key            = $gimmie['gimmie_key'];
$secret           = $gimmie['gimmie_secret'];
$access_token         = $my_player_uid;
$access_token_secret    = $secret;
$params           = array();
$sig_method         = new OAuthSignatureMethod_HMAC_SHA1();
$consumer         = new OAuthConsumer($key, $secret, NULL);
$token            = new OAuthConsumer($access_token, $access_token_secret);

if ($vbulletin->options['gimmie_use_email'] == 1 )
{
  $my_player_uid        = $vbulletin->userinfo['email'];
}

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

  if ($vbulletin->options['gimmie_enable_global'] == 1)
  {
    //pick event_name
    $event_name = "";
    if($vbulletin->options['gimmie_trigger_perpostedit'] == 1 && $thread['postusername'] != $vbulletin->userinfo['username'])
    {
      $event_name = $gimmie['edit_post_event'];
    } elseif($vbulletin->options['gimmie_trigger_perposteditown'] == 1 && $thread['postusername'] == $vbulletin->userinfo['username']) {
      $event_name = $gimmie['edit_own_post_event'];
    }

    //trigger if no trigger word list, or if word list matched
    if ($vbulletin->options['gimmie_trigger_word'] == "" || gimmie_match($edit['message']) )
    {
      // file_put_contents(DIR . '/plugins/gimmie/logs', "Trigger word matched!!\n", FILE_APPEND);
      // file_put_contents(DIR . '/plugins/gimmie/logs', $edit['message']."\n", FILE_APPEND);
      $endpoint           = "https://api.gimmieworld.com/1/trigger.json?async=webnotify&source_uid=".$vbulletin->options['bburl']."&event_name=" . $event_name;
      $acc_req          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $endpoint, $params);
      $acc_req->sign_request($sig_method, $consumer, $token);

      fetch_web_data($acc_req->to_url());
      return;

    }
  }
}

?>
