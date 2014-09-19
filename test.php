<?php
include('functions.php');
$url = 'http://forum.huyetlong.us/css.php?styleid=1&langid=2&d=1400318003&td=ltr&sheet=bbcode.css,editor.css,popupmenu.css,reset-fonts.css,vbulletin.css,forumhome_sub_forum_manager.css,vbulletin-chrome.css,vbulletin-formcontrols.css,,vietvbb_topstats.css';

$a = cleanUrl($url,true);
var_dump($a);
