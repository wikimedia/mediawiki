<?php
/** Urdu (اردو)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( "LanguageUtf8.php" );

class LanguageUr extends LanguageUtf8 {
	private $mMessagesUr = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesUr;
		$this->mMessagesUr =& $wgAllMessagesUr;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesUr[$key] ) ) {
			return $this->mMessagesUr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesUr;
	}

	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();
		$opt["quickbar"] = 2; # Right-to-left
		$opt["underline"] = 0; # Underline is hard to read in Arabic script
		return $opt;
	}

	# For right-to-left language support
	function isRTL() {
		return true;
	}
}

?>
