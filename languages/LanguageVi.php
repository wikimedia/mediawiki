<?php
/**
 * Vietnamese (Tiếng Việt)
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

class LanguageVi extends LanguageUtf8 {
	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}
}
?>
