<?php
/** Assamese (অসমীয়া)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

class LanguageAs extends LanguageUtf8 {
	function digitTransformTable() {
		return array(
			'0' => '০',
			'1' => '১',
			'2' => '২',
			'3' => '৩',
			'4' => '৪',
			'5' => '৫',
			'6' => '৬',
			'7' => '৭',
			'8' => '৮',
			'9' => '৯'
		);
	}

}
?>
