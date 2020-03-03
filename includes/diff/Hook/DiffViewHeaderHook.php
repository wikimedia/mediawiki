<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DiffViewHeaderHook {
	/**
	 * Called before diff display
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $diff DifferenceEngine object that's calling
	 * @param ?mixed $oldRev Revision object of the "old" revision (may be null/invalid)
	 * @param ?mixed $newRev Revision object of the "new" revision
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDiffViewHeader( $diff, $oldRev, $newRev );
}
