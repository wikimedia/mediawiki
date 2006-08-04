<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * Get all the translations messages, as defined in the English language file.
 */

require_once( 'commandLine.inc' );

$wgEnglishMessages = array_keys( Language::getMessagesFor( 'en' ) );
foreach( $wgEnglishMessages as $key ) {
	echo "$key\n";
}

?>
