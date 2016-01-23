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

    //trigger if no trigger and edited match if old text didn't match, and new text matches
    $trigger_words = $vbulletin->options['gimmie_trigger_word'];
    $new_match = !gimmie_match($postinfo['pagetext'], $trigger_words) && gimmie_match($edit['message'], $trigger_words);
    if ( $new_match )
    {

      //pick event_name
      $event_name = "";
      if($vbulletin->options['gimmie_trigger_perpostedit'] == 1 && $thread['postusername'] != $vbulletin->userinfo['username'])
      {
        $event_name = $gimmie['edit_post_event'];
      } elseif($vbulletin->options['gimmie_trigger_perposteditown'] == 1 && $thread['postusername'] == $vbulletin->userinfo['username']) {
        $event_name = $gimmie['edit_own_post_event'];
      }

      $endpoint           = "https://api.gimmieworld.com/1/trigger.json?async=webnotify&source_uid=".$vbulletin->options['bburl']."&event_name=" . $event_name;
      $acc_req          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $endpoint, $params);
      $acc_req->sign_request($sig_method, $consumer, $token);

      fetch_web_data($acc_req->to_url());
      return;

    } elseif ( gimmie_match($postinfo['pagetext'], $trigger_words) && !gimmie_match($edit['message'], $trigger_words) ) { //old text matched, but new text doesn't

      //pick event_name
      $event_name = $gimmie['remove_matching_post_event'];
      $endpoint           = "https://api.gimmieworld.com/1/trigger.json?async=webnotify&source_uid=".$vbulletin->options['bburl']."&event_name=" . $event_name;
      $acc_req          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $endpoint, $params);
      $acc_req->sign_request($sig_method, $consumer, $token);

      fetch_web_data($acc_req->to_url());
      return;
    } else {
      //either new and old both match, or both doesn't match
      //do nothing
    }
  }
}

?>
