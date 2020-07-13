<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserClonedHook {
	/**
	 * This hook is called when the parser is cloned.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Newly-cloned Parser object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserCloned( $parser );
}
