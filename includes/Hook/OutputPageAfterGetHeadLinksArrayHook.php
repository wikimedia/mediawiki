<?php

namespace MediaWiki\Hook;

use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputPageAfterGetHeadLinksArrayHook {
	/**
	 * This hook is called in OutputPage#getHeadLinksArray right
	 * before returning the result.
	 *
	 * @since 1.35
	 *
	 * @param array &$tags Array containing all <head> links generated so far. The array format is
	 *   "link name or number => 'link HTML'".
	 * @param OutputPage $out
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOutputPageAfterGetHeadLinksArray( &$tags, $out );
}
