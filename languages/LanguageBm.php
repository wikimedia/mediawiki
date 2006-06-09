<?php
/** Bambara (Bamanankan)
  *
  * @package MediaWiki
  * @subpackage Language
  */

# Stub for Bambara; import French (official language of Mali)

require_once( 'LanguageFr.php' );

class LanguageBm extends LanguageFr {

	function getFallbackLanguage() {
		return 'fr';
	}

	function getAllMessages() {
		return null;
	}

}

?>
