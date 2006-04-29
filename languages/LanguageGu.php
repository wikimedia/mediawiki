<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

class LanguageGu extends LanguageUtf8 {
	function digitTransformTable() {
		return array(
			'0' => '૦',
			'1' => '૧',
			'2' => '૨',
			'3' => '૩',
			'4' => '૪',
			'5' => '૫',
			'6' => '૬',
			'7' => '૭',
			'8' => '૮',
			'9' => '૯'
		);
	}

}

?>
