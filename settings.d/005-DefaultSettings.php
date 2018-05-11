<?php

$wgLocalTZoffset = date("Z") / 60;
$wgLocaltimezone = 'Europe/Berlin';
$wgDefaultUserOptions['timecorrection'] = 'ZoneInfo|' . (date("I") ? 120 : 60) . '|Europe/Berlin';
$wgUrlProtocols[] = 'file://';
$wgNamespacesWithSubpages[NS_MAIN] = true;
$wgExternalLinkTarget = '_blank';
$wgBlockDisablesLogin = true;

//Default MediaWiki settings needed for BlueSpice
$GLOBALS['wgNamespacesWithSubpages'][NS_MAIN] = true;
$GLOBALS['wgApiFrameOptions'] = 'SAMEORIGIN';
$GLOBALS['wgRSSUrlWhitelist'] = array(
	"http://blog.bluespice.com/feed/",
	"http://blog.hallowelt.com/feed/",
	"https://blog.bluespice.com/feed/",
	"https://blog.hallowelt.com/feed/",
);
$GLOBALS['wgExternalLinkTarget'] = '_blank';
$GLOBALS['wgCapitalLinkOverrides'][ NS_FILE ] = false;
$GLOBALS['wgRestrictDisplayTitle'] = false; //Otherwise only titles that normalize to the same DB key are allowed
$GLOBALS['wgUrlProtocols'][] = "file://";
$GLOBALS['wgVerifyMimeType'] = false;
$GLOBALS['wgAllowJavaUploads'] = true;

//Skin specific
$GLOBALS['wgDefaultSkin'] = 'bluespiceskin';
