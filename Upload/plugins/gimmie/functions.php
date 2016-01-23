<?php
function fetch_web_data($url, $post_data = '', $keep_alive = false, $redirection_level = 0)
{
  static $keep_alive_dom = null, $keep_alive_fp = null;

  preg_match('~^(http)(s)?://([^/:]+)(:(\d+))?(.+)$~', $url, $match);

  if (empty($match[1]))
    return false;

  elseif (isset($match[1]) && $match[1] == 'http')
  {
    if ($keep_alive && $match[3] == $keep_alive_dom)
      $fp = $keep_alive_fp;
    if (empty($fp))
    {
      $fp = @fsockopen(($match[2] ? 'ssl://' : '') . $match[3], empty($match[5]) ? ($match[2] ? 443 : 80) : $match[5], $err, $err, 5);
      if (!$fp)
        return false;
    }

    if ($keep_alive)
    {
      $keep_alive_dom = $match[3];
      $keep_alive_fp = $fp;
    }

    if (empty($post_data))
    {
      fwrite($fp, 'GET ' . $match[6] . ' HTTP/1.0' . "\r\n");
      fwrite($fp, 'Host: ' . $match[3] . (empty($match[5]) ? ($match[2] ? ':443' : '') : ':' . $match[5]) . "\r\n");
      fwrite($fp, 'User-Agent: PHP/SMF' . "\r\n");
      if ($keep_alive)
        fwrite($fp, 'Connection: Keep-Alive' . "\r\n\r\n");
      else
        fwrite($fp, 'Connection: close' . "\r\n\r\n");
    }
    else
    {
      fwrite($fp, 'POST ' . $match[6] . ' HTTP/1.0' . "\r\n");
      fwrite($fp, 'Host: ' . $match[3] . (empty($match[5]) ? ($match[2] ? ':443' : '') : ':' . $match[5]) . "\r\n");
      fwrite($fp, 'User-Agent: PHP/SMF' . "\r\n");
      if ($keep_alive)
        fwrite($fp, 'Connection: Keep-Alive' . "\r\n");
      else
        fwrite($fp, 'Connection: close' . "\r\n");
      fwrite($fp, 'Content-Type: application/x-www-form-urlencoded' . "\r\n");
      fwrite($fp, 'Content-Length: ' . strlen($post_data) . "\r\n\r\n");
      fwrite($fp, $post_data);
    }

    $response = fgets($fp, 768);

    if ($redirection_level < 3 && preg_match('~^HTTP/\S+\s+30[127]~i', $response) === 1)
    {
      $header = '';
      $location = '';
      while (!feof($fp) && trim($header = fgets($fp, 4096)) != '')
        if (strpos($header, 'Location:') !== false)
          $location = trim(substr($header, strpos($header, ':') + 1));

      if (empty($location))
        return false;
      else
      {
        if (!$keep_alive)
          fclose($fp);
        return fetch_web_data($location, $post_data, $keep_alive, $redirection_level + 1);
      }
    }

    elseif (preg_match('~^HTTP/\S+\s+20[01]~i', $response) === 0)
      return false;

    while (!feof($fp) && trim($header = fgets($fp, 4096)) != '')
    {
      if (preg_match('~content-length:\s*(\d+)~i', $header, $match) != 0)
        $content_length = $match[1];
      elseif (preg_match('~connection:\s*close~i', $header) != 0)
      {
        $keep_alive_dom = null;
        $keep_alive = false;
      }

      continue;
    }

    $data = '';
    if (isset($content_length))
    {
      while (!feof($fp) && strlen($data) < $content_length)
        $data .= fread($fp, $content_length - strlen($data));
    }
    else
    {
      while (!feof($fp))
        $data .= fread($fp, 4096);
    }

    if (!$keep_alive)
      fclose($fp);
  }
  else
  {
    $data = false;
  }

  return $data;
}


function gimmie_match($message)
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
  return preg_match($pattern, $message);
}

$gimmie = Array(
  "gimmie_key" => $vbulletin->options['gimmie_key'],
  "gimmie_secret" => $vbulletin->options['gimmie_secret'],
  "gimmie_trigger_login" => "login_site",
  "gimmie_trigger_perpollpost" => "create_poll",
  "gimmie_trigger_perpost" => "create_post",
  "gimmie_trigger_perpostown" => "create_post",
  "edit_post_event" => "edit_post_to_match",
  "edit_own_post_event" => "edit_post_to_match",
  "delete_post_event" => "delete_matching_post",
  "gimmie_trigger_perthreadrating" => "rate_thread",
  "gimmie_trigger_perthreadratingreceived" => "received_thread_rating",
  "gimmie_trigger_perreferral" => "refer_a_friend",
  "gimmie_trigger_perthread" => "create_thread",
  "gimmie_trigger_perpollvote" => "vote_poll"
);


$gimmie_widget_setting = '<div id="gimmie-root"></div>
<script type="text/javascript">
var _gimmie = {
                          {GIMMIE_USER}
                          "endpoint"                    : "{GIMMIE_FORUMPATH}/gimmie-connect.php?gimmieapi=",
                          "gimmie_endpoint"             : "http://api.gimmieworld.com",
                          "key"                         : "{GIMMIE_KEY}",
                          "country"                     : "{GIMMIE_COUNTRY}",
                          "locale"                      : "{GIMMIE_LOCALE}",
                          "options"                     : {
                            "push_notification"         : false,
                            "animate"                   : true,
                            "auto_show_notification"    : true,
                            "notification_timeout"      : {GIMMIE_NOTIFICATION_TIMEOUT},
                            "responsive"                : {GIMMIE_RESPONSIVE},
                            "show_anonymous_rewards"    : true,
                            "shuffle_reward"            : true,
                            "default_level_icon"        : "",
                            "pages"                     : {
                              "catalog"                 : {
                                "hide"                  : {GIMMIE_CATALOG},
                                "hide_sponsor_here"     : {GIMMIE_HIDE_SPONSOR}
                              },
                              "profile"                 : {
                                "hide"                  : {GIMMIE_PROFILE},
                                "redemptions"           : {GIMMIE_PROFILE_REDEMPTIONS},
                                "mayorships"            : false,
                                "badges"                : false,
                                "activities"            : {GIMMIE_PROFILE_ACTIVITIES}
                              },
                              "leaderboard"             : {
                                "table"                 : "alltime", //alltime,thisweek,last7days,pastweek,today,last30days,pastmonth,thismonth
                                "hide"                  : {GIMMIE_LEADERBOARD},
                                "most_points"           : {GIMMIE_LEADERBOARD_MOSTPOINTS},
                                "most_rewards"          : {GIMMIE_LEADERBOARD_MOSTREWARDS},
                                "most_reward_value"     : {GIMMIE_LEADERBOARD_MOSTVALUES}
                              }
                            }
                          },
                          "events"                      : {
                            "widgetLoad"                : function () {
                              //console.log ("Loaded");
                            },
                            "login"                     : function () {
                              //console.log ("Login");
                            },
                            "loadLeaderboard"           : function (data, cb) {
                              //data
                              cb(data);
                            }
                          },
                          "text"                        : {
                            "help"                      : "{GIMMIE_HELP}",
                            "help_url"                  : "{GIMMIE_HELP_URL}",
                            {GIMMIE_LOCALIZE}
                          },
                          "templates"                   : {
                          },
                        };
                        (function(d){
                          var js, id = "gimmie-widget", ref = d.getElementsByTagName("script")[0];
                          if (d.getElementById(id)) {return;}
                          js = d.createElement("script"); js.id = id; js.async = true;
                          js.src = "//api.gimmieworld.com/cdn/gimmie-widget2.all.js";
                          ref.parentNode.insertBefore(js, ref);
                        }(document));

</script>

<style>
{GIMMIE_CUSTOMCSS}
</style>
';
?>
