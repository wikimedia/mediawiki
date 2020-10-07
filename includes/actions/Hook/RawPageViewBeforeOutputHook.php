<?php

namespace MediaWiki\Hook;

use RawAction;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface RawPageViewBeforeOutputHook {
	/**
	 * This hook is called right before the text is blown out in action=raw.
	 *
	 * @since 1.35
	 *
	 * @param RawAction $obj
	 * @param string &$text The text that's going to be the output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRawPageViewBeforeOutput( $obj, &$text );
}
