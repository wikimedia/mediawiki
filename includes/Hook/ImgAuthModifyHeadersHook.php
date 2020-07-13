<?php

namespace MediaWiki\Hook;

use MediaWiki\Linker\LinkTarget;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImgAuthModifyHeadersHook {
	/**
	 * This hook is called just before a file is streamed to a user via
	 * img_auth.php, allowing headers to be modified beforehand.
	 *
	 * @since 1.35
	 *
	 * @param LinkTarget $title
	 * @param string[] &$headers HTTP headers ( name => value, names are case insensitive ).
	 *   Two headers get special handling: If-Modified-Since (value must be
	 *   a valid HTTP date) and Range (must be of the form "bytes=(\d*-\d*)")
	 *   will be honored when streaming the file.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImgAuthModifyHeaders( $title, &$headers );
}
