<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "DifferenceEngineShowEmptyOldContent" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineShowEmptyOldContentHook {
	/**
	 * Use this hook to change the diff table body (without header) in cases when there is
	 * no old revision or the old and new revisions are identical.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineShowEmptyOldContent( $differenceEngine );
}
