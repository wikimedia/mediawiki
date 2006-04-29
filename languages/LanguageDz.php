<?php
/** Dzongkha (རྫོང་ཁ)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

class LanguageDz extends LanguageUtf8 {
	function digitTransformTable() {
		return array(
			'0' => '༠',
			'1' => '༡',
			'2' => '༢',
			'3' => '༣',
			'4' => '༤',
			'5' => '༥',
			'6' => '༦',
			'7' => '༧',
			'8' => '༨',
			'9' => '༩'
		);
	}

}

?>
