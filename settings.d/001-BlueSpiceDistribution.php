<?php

require_once __DIR__ . "/../extensions/Arrays/Arrays.php";
require_once __DIR__ . "/../extensions/CategoryTree/CategoryTree.php";
wfLoadExtension( 'DynamicPageList' );
require_once __DIR__ . "/../extensions/HitCounters/HitCounters.php";
require_once __DIR__ . "/../extensions/ImageMapEdit/ImageMapEdit.php";
require_once __DIR__ . "/../extensions/Quiz/Quiz.php";
wfLoadExtension( 'RSS' );
wfLoadExtension( 'Echo');
require_once __DIR__ . "/../extensions/TitleKey/TitleKey.php";
require_once __DIR__ . "/../extensions/EmbedVideo/EmbedVideo.php";
wfLoadExtension( 'FilterSpecialPages' );
wfLoadExtension( 'UserMerge' );
$wgUserMergeProtectedGroups = array();
$wgUserMergeUnmergeable = array();
require_once __DIR__ . "/../extensions/Variables/Variables.php";
require_once __DIR__ . "/../extensions/EditNotify/EditNotify.php";
wfLoadExtension( 'BlueSpiceEchoConnector' );
require_once __DIR__ . "/../extensions/BlueSpiceDistributionConnector/BlueSpiceDistributionConnector.php";
require_once __DIR__ . "/../extensions/BlueSpiceUserMergeConnector/BlueSpiceUserMergeConnector.php";
wfLoadExtension( 'BlueSpiceEditNotifyConnector' );
require_once __DIR__ . "/../extensions/UserFunctions/UserFunctions.php";
