<?php
/** Khmer (ភាសាខ្មែរ)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

class LanguageKm extends LanguageUtf8 {
	function digitTransformTable() {
		return array(
			'0' => '០',
			'1' => '១',
			'2' => '២',
			'3' => '៣',
			'4' => '៤',
			'5' => '៥',
			'6' => '៦',
			'7' => '៧',
			'8' => '៨',
			'9' => '៩'
		);
	}

}

?>
