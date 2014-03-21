<?php
require_once(DIR . '/plugins/gimmie/functions.php');

function getRealIpAddr()
{
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
  //check ip from share internet
  {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  //to check ip is pass from proxy
  {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

if ($vbulletin->options['gimmie_enable_global'] == 1)
{

    $gimmiewidget = str_replace('{GIMMIE_FORUMPATH}',$vbulletin->options['bburl'],$gimmie_widget_setting);    
    
    if ($vbulletin->userinfo['userid'])
    {
      
      $user_info = '"user"                        : {
        "name"                      : "'.$vbulletin->userinfo['username'].'",
        "realname"                  : "'.$vbulletin->userinfo['username'].'",    
        "email"                     : "'.$vbulletin->userinfo['email'].'",
        "avatar"                    : ""
      },';
            
      $gimmiewidget = str_replace('{GIMMIE_USER}',$user_info,$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_USER}','',$gimmiewidget);
    }

    $country = $vbulletin->options['gimmie_country'];

    if ($country == "")
    {
      $country = file_get_contents("http://api.wipmania.com/".getRealIpAddr()."?http://".$_SERVER['HTTP_HOST']);
    }
    
    $help = $vbulletin->options['gimmie_help'];
    $order   = array("\r\n", "\n", "\r");
    $replace = '<br />';
    $help = str_replace($order, $replace, $help);

    if ($vbulletin->options['gimmie_web_responsive'] == 1)
    {
      $gimmiewidget = str_replace('{GIMMIE_RESPONSIVE}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_RESPONSIVE}','false',$gimmiewidget);
    }
    
    $gimmiewidget = str_replace('{GIMMIE_COUNTRY}',$country,$gimmiewidget);
    $gimmiewidget = str_replace('{GIMMIE_LOCALIZE}',$vbulletin->options['gimmie_localize'],$gimmiewidget);
    $gimmiewidget = str_replace('{GIMMIE_HELP_URL}',$vbulletin->options['gimmie_help_url'],$gimmiewidget);
    $gimmiewidget = str_replace('{GIMMIE_KEY}',$gimmie['gimmie_key'],$gimmiewidget);

    if ($vbulletin->options['gimmie_catalog'] == 0)
    {
      $gimmiewidget = str_replace('{GIMMIE_CATALOG}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_CATALOG}','false',$gimmiewidget);
    }
    
    if ($vbulletin->options['gimmie_sponsor_link'] == 0)
    {
      $gimmiewidget = str_replace('{GIMMIE_HIDE_SPONSOR}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_HIDE_SPONSOR}','false',$gimmiewidget);
    }    
    
    if ($vbulletin->options['gimmie_profile'] == 0)
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE}','false',$gimmiewidget);
    }    

    if ($vbulletin->options['gimmie_profile_redemptions'] == 1)
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE_REDEMPTIONS}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE_REDEMPTIONS}','false',$gimmiewidget);
    }
    
    if ($vbulletin->options['gimmie_profile_mayorships'] == 1)
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE_MAYORSHIPS}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE_MAYORSHIPS}','false',$gimmiewidget);
    }    
    
    if ($vbulletin->options['gimmie_profile_badges'] == 1)
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE_BADGES}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE_BADGES}','false',$gimmiewidget);
    }    
      
    if ($vbulletin->options['gimmie_profile_activities'] == 1)
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE_ACTIVITIES}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_PROFILE_ACTIVITIES}','false',$gimmiewidget);
    }

    if ($vbulletin->options['gimmie_leaderboard'] == 0)
    {
      $gimmiewidget = str_replace('{GIMMIE_LEADERBOARD}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_LEADERBOARD}','false',$gimmiewidget);
    }

    if ($vbulletin->options['gimmie_leaderboard_mostpoints'] == 1)
    {
      $gimmiewidget = str_replace('{GIMMIE_LEADERBOARD_MOSTPOINTS}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_LEADERBOARD_MOSTPOINTS}','false',$gimmiewidget);
    }

    if ($vbulletin->options['gimmie_leaderboard_mostrewards'] == 1)
    {
      $gimmiewidget = str_replace('{GIMMIE_LEADERBOARD_MOSTREWARDS}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_LEADERBOARD_MOSTREWARDS}','false',$gimmiewidget);
    }
 
    if ($vbulletin->options['gimmie_leaderboard_mostvalues'] == 1)
    {
      $gimmiewidget = str_replace('{GIMMIE_LEADERBOARD_MOSTVALUES}','true',$gimmiewidget);
    }
    else
    {
      $gimmiewidget = str_replace('{GIMMIE_LEADERBOARD_MOSTVALUES}','false',$gimmiewidget);
    }
    
    $gimmiewidget = str_replace('{GIMMIE_NOTIFICATION_TIMEOUT}',$vbulletin->options['gimmie_notification_timeout'],$gimmiewidget);
    $gimmiewidget = str_replace('{GIMMIE_HELP}',$help,$gimmiewidget);
    

    $gimmiewidget = str_replace('{GIMMIE_CUSTOMCSS}',$vbulletin->options['gimmie_custom_css'],$gimmiewidget);
    
    $widget_find = '</body>';
    $widget_add_before = $gimmiewidget. PHP_EOL;
    $output = str_replace($widget_find,$widget_add_before.$widget_find, $output);

}
?>