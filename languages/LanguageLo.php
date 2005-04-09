<?php
/** Lao (ພາສາລາວ)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

class LanguageLo extends LanguageUtf8 {
	var $digitTransTable = array(
		'0' => '໐',
		'1' => '໑',
		'2' => '໒',
		'3' => '໓',
		'4' => '໔',
		'5' => '໕',
		'6' => '໖',
		'7' => '໗',
		'8' => '໘',
		'9' => '໙'
	);

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		if( $wgTranslateNumerals ) {
			return strtr( $number, $this->digitTransTable );
		} else {
			return $number;
		}
	}
}

?>
