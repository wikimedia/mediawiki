<?php
/**
 * See deferred.doc
 *
 */

/**
 *
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
