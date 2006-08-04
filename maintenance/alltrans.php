<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * Get all the translations messages, as defined in the English language file.
 */

require_once( 'commandLine.inc' );

$wgEnglishLang = Language::factory( 'en' );
foreach( array_keys( $wgEnglishLang->getAllMessages() ) as $key ) {
	echo "$key\n";
}

?>
