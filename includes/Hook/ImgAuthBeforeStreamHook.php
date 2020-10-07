<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImgAuthBeforeStreamHook {
	/**
	 * This hook is called before a file is streamed to a user, but only when using img_auth.php.
	 *
	 * @since 1.35
	 *
	 * @param Title &$title Title object of the file as it would appear for the upload page
	 * @param string &$path Original file and path name when img_auth was invoked by the web server
	 * @param string &$name Name only component of the file
	 * @param array &$result Location to pass back results of the hook routine (only used if
	 *   failed)
	 *   - $result[0]=The index of the header message
	 *   - $result[1]=The index of the body text message
	 *   - $result[2 through n]=Parameters passed to body text message. Please note the
	 *   header message cannot receive/use parameters.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImgAuthBeforeStream( &$title, &$path, &$name, &$result );
}
