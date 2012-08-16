<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'TimeGate',
	'author' => 'Harihar Shankar, Herbert Van de Sompel, Robert Sanderson',
	'url' => 'http://www.mediawiki.org/wiki/Extension:Memento',
	'description' => 'Memento TimeGate',
	'descriptionmsg' => 'timegate-desc',
	'version' => '1.0'
);

$dir = dirname( __FILE__ ) . '/';

$wgAutoloadClasses['TimeGate'] = $dir . 'timegate_body.php'; 
$wgExtensionMessagesFiles['TimeGate'] = $dir . 'timegate.i18n.php';
$wgExtensionAliasesFiles['TimeGate'] = $dir . 'timegate.alias.php';
$wgSpecialPages['TimeGate'] = 'TimeGate'; 
