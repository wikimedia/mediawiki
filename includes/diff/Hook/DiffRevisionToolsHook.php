<?php

namespace MediaWiki\Diff\Hook;

use Revision;
use User;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface DiffRevisionToolsHook {
	/**
	 * Use this hook to override or extend the revision tools available from the
	 * diff view, i.e. undo, etc.
	 *
	 * @since 1.35
	 *
	 * @param Revision $newRev New revision
	 * @param string[] &$links Array of HTML links
	 * @param Revision|null $oldRev Old revision (may be null)
	 * @param User $user Current user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDiffRevisionTools( $newRev, &$links, $oldRev, $user );
}
