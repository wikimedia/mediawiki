<?php

wfLoadExtension( 'Arrays' );
wfLoadExtension( 'CategoryTree' );
wfLoadExtension( 'DynamicPageList' );
require_once __DIR__ . "/../extensions/HitCounters/HitCounters.php";
require_once __DIR__ . "/../extensions/ImageMapEdit/ImageMapEdit.php";
wfLoadExtension( 'RSS' );
wfLoadExtension( 'Echo');
wfLoadExtension( 'TitleKey');
require_once __DIR__ . "/../extensions/EmbedVideo/EmbedVideo.php";
wfLoadExtension( 'FilterSpecialPages' );
wfLoadExtension( 'UserMerge' );
$wgUserMergeProtectedGroups = [];
$wgUserMergeUnmergeable = [];
wfLoadExtension( 'Variables' );
wfLoadExtension( 'BlueSpiceEchoConnector' );
wfLoadExtension( 'BlueSpiceDistributionConnector' );
require_once __DIR__ . "/../extensions/UserFunctions/UserFunctions.php";
$GLOBALS['wgUFAllowedNamespaces'] = array_fill( 0, 5000, true );
require_once __DIR__ . "/../extensions/UrlGetParameters/UrlGetParameters.php";
wfLoadExtension( 'FlexiSkin' );
wfLoadExtension( 'Loops' );
