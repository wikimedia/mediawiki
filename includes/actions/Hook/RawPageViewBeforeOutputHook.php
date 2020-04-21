<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RawPageViewBeforeOutputHook {
	/**
	 * Right before the text is blown out in action=raw.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $obj RawAction object
	 * @param ?mixed &$text The text that's going to be the output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRawPageViewBeforeOutput( $obj, &$text );
}
