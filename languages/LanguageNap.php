<?php
/** Neapolitan (Nnapulitano)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageIt.php' );

class LanguageNap extends LanguageIt {

	function getFallbackLanguage() {
		return 'it';
	}

	function getAllMessages() {
		return null;
	}

}

?>
