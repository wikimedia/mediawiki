<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OutputPageAfterGetHeadLinksArrayHook {
	/**
	 * Called in OutputPage#getHeadLinksArray right
	 * before returning the result.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tags array containing all <head> links generated so far. The array format is
	 *   "link name or number => 'link HTML'".
	 * @param ?mixed $out the OutputPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOutputPageAfterGetHeadLinksArray( &$tags, $out );
}
