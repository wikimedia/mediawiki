<?php

$wgLocalTZoffset = date("Z") / 60;
$wgLocaltimezone = 'Europe/Berlin';
$wgUrlProtocols[] = 'file://';
$wgNamespacesWithSubpages[NS_MAIN] = true;
$wgExternalLinkTarget = '_blank';
$wgBlockDisablesLogin = true;
$wgEnableUploads = true;

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
$GLOBALS['wgParserCacheType'] = CACHE_NONE;

/**
 * Allow authentication extensions like "Auth_remoteuser", "SimpleSAMLphp" or
 * "LDAPAuthentication2" to create users.
 */
$GLOBALS['wgExtensionFunctions'][] = function() {
	$GLOBALS['wgGroupPermissions']['*']['autocreateaccount'] = true;
};

/**
 * ERM20479: Prevent unregulated access to logs
 */
$GLOBALS['wgExtensionFunctions'][] = function() {
	$logKeys = $GLOBALS['wgLogTypes'];
	foreach ( $logKeys as $logKey ) {
		if ( !isset( $GLOBALS['wgLogRestrictions'][$logKey] ) ) {
			$GLOBALS['wgLogRestrictions'][$logKey] = 'wikiadmin';
		}
	}
};