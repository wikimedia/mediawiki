<?php

namespace MediaWiki\Diff\Hook;

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface DiffToolsHook {
	/**
	 * Use this hook to override or extend the revision tools available from the
	 * diff view, i.e. undo, etc.
	 *
	 * @since 1.35
	 *
	 * @param RevisionRecord $newRevRecord New revision
	 * @param string[] &$links Array of HTML links
	 * @param RevisionRecord|null $oldRevRecord Old revision (may be null)
	 * @param UserIdentity $userIdentity Current user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDiffTools( $newRevRecord, &$links, $oldRevRecord, $userIdentity );
}
