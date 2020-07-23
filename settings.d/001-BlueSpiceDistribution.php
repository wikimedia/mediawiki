<?php

wfLoadExtension( 'Arrays' );
wfLoadExtension( 'CategoryTree' );
wfLoadExtension( 'DynamicPageList' );
require_once __DIR__ . "/../extensions/HitCounters/HitCounters.php";
require_once __DIR__ . "/../extensions/ImageMapEdit/ImageMapEdit.php";
require_once __DIR__ . "/../extensions/Quiz/Quiz.php";
wfLoadExtension( 'RSS' );
wfLoadExtension( 'Echo');
wfLoadExtension( 'TitleKey');
require_once __DIR__ . "/../extensions/EmbedVideo/EmbedVideo.php";
wfLoadExtension( 'FilterSpecialPages' );
wfLoadExtension( 'UserMerge' );
$wgUserMergeProtectedGroups = array();
$wgUserMergeUnmergeable = array();
wfLoadExtension( 'Variables' );
wfLoadExtension( 'EditNotify' );
wfLoadExtension( 'BlueSpiceEchoConnector' );
wfLoadExtension( 'BlueSpiceDistributionConnector' );
require_once __DIR__ . "/../extensions/BlueSpiceUserMergeConnector/BlueSpiceUserMergeConnector.php";
wfLoadExtension( 'BlueSpiceEditNotifyConnector' );
require_once __DIR__ . "/../extensions/UserFunctions/UserFunctions.php";
require_once __DIR__ . "/../extensions/UrlGetParameters/UrlGetParameters.php";
