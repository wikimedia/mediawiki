<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserClonedHook {
	/**
	 * Called when the parser is cloned.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Newly-cloned Parser object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserCloned( $parser );
}
