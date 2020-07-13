<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineNewHeaderHook {
	/**
	 * Use this hook to change the $newHeader variable, which contains information about
	 * the new revision, such as the revision's author, whether the revision was marked
	 * as a minor edit or not, etc.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @param string &$newHeader String containing the various #mw-diff-otitle[1-5] divs, which
	 *   include things like revision author info, revision comment, RevisionDelete link and more
	 * @param array $formattedRevisionTools Array containing revision tools, some of which may have
	 *   been injected with the DiffRevisionTools hook
	 * @param string $nextlink String containing the link to the next revision (if any); also
	 *   included in $newHeader
	 * @param string $rollback Rollback link to roll this revision back to the previous one, if any
	 * @param string $newminor String indicating if the new revision was marked as a minor edit
	 * @param bool $diffOnly Boolean parameter passed to DifferenceEngine#showDiffPage, indicating
	 *   whether we should show just the diff; passed in as a query string parameter to
	 *   the various URLs constructed here (i.e. $nextlink)
	 * @param string $rdel RevisionDelete link for the new revision, if the current user is allowed
	 *   to use the RevisionDelete feature
	 * @param bool $unhide Boolean parameter indicating whether to show RevisionDeleted revisions
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineNewHeader( $differenceEngine, &$newHeader,
		$formattedRevisionTools, $nextlink, $rollback, $newminor, $diffOnly, $rdel,
		$unhide
	);
}
