<?php
/** Samogitian (Žemaitėška)
 *
 * Inherits Lithuanian
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Rob Church <robchur@gmail.com>
 */

require_once( 'LanguageLt.php' );

class LanguageBat_smg extends LanguageLt {

	function getFallbackLanguage() {
		return 'lt';
	}

	function getAllMessages() {
		return null;
	}

}

?>