<?php
/**
 * See deferred.doc
 *
 * @package MediaWiki
 */

/**
 *
 * @package MediaWiki
 */
class UserUpdate {
	/**
	 *
	 */
	function UserUpdate() { }

	/**
	 *
	 */
	function doUpdate() {
		global $wgUser;
		$wgUser->saveSettings();
	}
}

?>
