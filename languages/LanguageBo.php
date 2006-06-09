<?php
/** Tibetan (བོད་ཡིག)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

class LanguageBo extends LanguageUtf8 {

	function getAllMessages() {
		return null;
	}

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
