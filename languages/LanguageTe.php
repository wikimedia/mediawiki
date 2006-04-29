<?php
/** Telugu (Telugu)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

class LanguageTe extends LanguageUtf8 {

	function digitTransformTable() {
		return array(
			'0' => '౦',
			'1' => '౧',
			'2' => '౨',
			'3' => '౩',
			'4' => '౪',
			'5' => '౫',
			'6' => '౬',
			'7' => '౭',
			'8' => '౮',
			'9' => '౯'
		);
	}

}
?>
