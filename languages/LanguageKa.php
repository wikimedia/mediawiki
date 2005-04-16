<?php
/** Kartuli (Georgian) stub
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

$wgNamespaceNamesKa = array(
	NS_CATEGORY => "კატეგორია",
) + $wgNamespaceNamesEn;

class LanguageKa extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesKa;
		return $wgNamespaceNamesKa;
	}
	
	function getNsText( $index ) {
		global $wgNamespaceNamesKa;
		return $wgNamespaceNamesKa[$index];
	}
	
	function getNsIndex( $text ) {
		global $wgNamespaceNamesKa;
		
		foreach ( $wgNamespaceNamesKa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) {
				return $i;
			}
		}
		return false;
	}
}

?>
