<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'TimeMap',
	'author' => 'Harihar Shankar, Herbert Van de Sompel, Robert Sanderson',
	'url' => 'http://www.mediawiki.org/wiki/Extension:Memento',
	'description' => 'Memento TimeMap',
	'descriptionmsg' => 'timemap-desc',
	'version' => '1.0',
);

$dir = dirname(__FILE__) . '/';

$wgAutoloadClasses['TimeMap'] = $dir . 'timemap_body.php'; 
$wgExtensionMessagesFiles['TimeMap'] = $dir . 'timemap.i18n.php';
$wgExtensionAliasesFiles['TimeMap'] = $dir . 'timemap.alias.php';
$wgSpecialPages['TimeMap'] = 'TimeMap'; 
