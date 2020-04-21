<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DiffRevisionToolsHook {
	/**
	 * Override or extend the revision tools available from the
	 * diff view, i.e. undo, etc.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $newRev Revision object of the "new" revision
	 * @param ?mixed &$links Array of HTML links
	 * @param ?mixed $oldRev Revision object of the "old" revision (may be null)
	 * @param ?mixed $user Current user object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDiffRevisionTools( $newRev, &$links, $oldRev, $user );
}
