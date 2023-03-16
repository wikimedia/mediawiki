<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "DifferenceEngineBeforeDiffTable" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineBeforeDiffTableHook {
	/**
	 * Use this hook to change the HTML that is rendered above the diff table.
	 *
	 * @since 1.41
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @param mixed[] &$parts HTML strings to add to a container above the diff table.
	 * Will be sorted by key before being output.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineBeforeDiffTable( DifferenceEngine $differenceEngine, array &$parts );
}
