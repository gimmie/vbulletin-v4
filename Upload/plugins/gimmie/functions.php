<?php
$gimmie = Array(
  "gimmie_key" => $vbulletin->options['gimmie_key'],
  "gimmie_secret" => $vbulletin->options['gimmie_secret'],
  "gimmie_trigger_login" => "vbulletin_login",
  "gimmie_trigger_perpollpost" => "vbulletin_create_poll",
  "gimmie_trigger_perpost" => "vbulletin__create_post",
  "gimmie_trigger_perpostown" => "vbulletin_create_post",
  "gimmie_trigger_perthreadrating" => "vbulletin_rate_thread",
  "gimmie_trigger_perthreadratingreceived" => "vbulletin_rate_thread_received",
  "gimmie_trigger_perreferral" => "vbulletin_referral",
  "gimmie_trigger_perthread" => "vbulletin_create_thread",
  "gimmie_trigger_perpollvote" => "vbulletin_vote_poll"
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
                                "mostpoints"            : {GIMMIE_LEADERBOARD_MOSTPOINTS},
                                "mostrewards"           : {GIMMIE_LEADERBOARD_MOSTREWARDS},
                                "mostvalues"            : {GIMMIE_LEADERBOARD_MOSTVALUES}
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
