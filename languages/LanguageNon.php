<?php
/** Old Norse (Norrǿna)
  *
  * Defaults to Icelandic instead of English.
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageIs.php' );

class LanguageNon extends LanguageIs {

	function getFallbackLanguage() {
		return 'is';
	}

	function getAllMessages() {
		return null;
	}

}

?>
