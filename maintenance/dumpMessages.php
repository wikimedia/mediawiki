<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( "commandLine.inc" );
$wgMessageCache->disableTransform();
$messages = array();
$wgEnglishLang = Language::factory( 'en' );
foreach ( $wgEnglishLang->getAllMessages() as $key => $englishValue )
{
	$messages[$key] = wfMsg( $key );
}
print "MediaWiki $wgVersion language file\n";
print serialize( $messages );

?>
