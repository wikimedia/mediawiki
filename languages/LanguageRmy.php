<?php
/** Vlax Romany (Romani)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @bug 5422
  *
  * @author Niklas Laxström
  *
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

/**
 * Use Romanian as default instead of English
 */
require_once 'LanguageRo.php';


if (!$wgCachedMessageArrays) {
	require_once('MessagesRmy.php');
}

class LanguageRmy extends LanguageRo {
	private $mMessagesRmy = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesRmy;
		$this->mMessagesRmy =& $wgAllMessagesRmy;

	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesRmy[$key] ) ) {
			return $this->mMessagesRmy[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesRmy;
	}

	function getFallbackLanguage() {
		return 'ro';
	}

}
?>