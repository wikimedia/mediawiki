<?php

require_once __DIR__ . "/../extensions/CategoryTree/CategoryTree.php";
require_once __DIR__ . "/../extensions/DynamicPageList/DynamicPageList.php";
require_once __DIR__ . "/../extensions/HitCounters/HitCounters.php";
require_once __DIR__ . "/../extensions/ImageMapEdit/ImageMapEdit.php";
require_once __DIR__ . "/../extensions/Lockdown/Lockdown.php";
require_once __DIR__ . "/../extensions/Quiz/Quiz.php";
wfLoadExtension("Rss");
require_once __DIR__ . "/../extensions/Echo/Echo.php";
require_once __DIR__ . "/../extensions/TitleKey/TitleKey.php";
require_once __DIR__ . "/../extensions/EmbedVideo/EmbedVideo.php";
wfLoadExtension("UserMerge");
$wgUserMergeProtectedGroups = array();
$wgUserMergeUnmergeable = array();
require_once __DIR__ . "/../extensions/EditNotify/EditNotify.php";
require_once __DIR__ . "/../extensions/MobileFrontend/MobileFrontend.php";
$wgMFAutodetectMobileView = true;
require_once __DIR__ . "/../extensions/BlueSpiceEchoConnector/BlueSpiceEchoConnector.php";
require_once __DIR__ . "/../extensions/BlueSpiceDistributionConnector/BlueSpiceDistributionConnector.php";
require_once __DIR__ . "/../extensions/BlueSpiceUserMergeConnector/BlueSpiceUserMergeConnector.php";
wfLoadExtension( "BlueSpiceEditNotifyConnector" );
$pathUserFunctions = __DIR__ . "/../extensions/UserFunctions/UserFunctions.php";
if ( file_exists( $pathUserFunctions ) ) {
  require_once $pathUserFunctions;
}
