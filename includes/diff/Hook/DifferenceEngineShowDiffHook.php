<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineShowDiffHook {
	/**
	 * Use this hook to affect the diff text which eventually gets sent to the OutputPage object.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineShowDiff( $differenceEngine );
}
