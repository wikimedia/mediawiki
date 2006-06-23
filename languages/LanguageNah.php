<?php
/** Nahuatl
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Rob Church <robchur@gmail.com>
  *
  * @copyright Copyright © 2006, Rob Church
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

require_once( 'LanguageEs.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesNah.php');
}

# Per conversation with a user in IRC, we inherit from Spanish and work from there
# Nahuatl was the language of the Aztecs, and a modern speaker is most likely to
# understand Spanish if a Nah translation is not available

class LanguageNah extends LanguageEs {
	private $mMessagesNah = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesNah;
		$this->mMessagesNah =& $wgAllMessagesNah;

	}

	function getFallbackLanguage() {
		return 'es';
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesNah[$key] ) ) {
			return $this->mMessagesNah[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesNah;
	}

}

?>