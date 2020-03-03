<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineShowDiffPageHook {
	/**
	 * Add additional output via the available
	 * OutputPage object into the diff view
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out OutputPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineShowDiffPage( $out );
}
