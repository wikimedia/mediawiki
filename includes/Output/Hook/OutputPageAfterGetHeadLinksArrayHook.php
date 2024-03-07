<?php

namespace MediaWiki\Output\Hook;

use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "OutputPageAfterGetHeadLinksArray" to register handlers implementing this interface.
 *
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

/** @deprecated class alias since 1.41 */
class_alias( OutputPageAfterGetHeadLinksArrayHook::class, 'MediaWiki\Hook\OutputPageAfterGetHeadLinksArrayHook' );
