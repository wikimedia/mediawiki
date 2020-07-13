<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineOldHeaderHook {
	/**
	 * Use this hook to change the $oldHeader variable, which contains information about
	 * the old revision, such as the revision's author, whether the revision was marked
	 * as a minor edit or not, etc.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @param string &$oldHeader String containing the various #mw-diff-otitle[1-5] divs, which
	 *   include things like revision author info, revision comment, RevisionDelete link and more
	 * @param string $prevlink String containing the link to the previous revision (if any); also
	 *   included in $oldHeader
	 * @param string $oldminor String indicating if the old revision was marked as a minor edit
	 * @param bool $diffOnly Boolean parameter passed to DifferenceEngine#showDiffPage, indicating
	 *   whether we should show just the diff; passed in as a query string parameter to
	 *   the various URLs constructed here (i.e. $prevlink)
	 * @param string $ldel RevisionDelete link for the old revision, if the current user is allowed
	 *   to use the RevisionDelete feature
	 * @param bool $unhide Boolean parameter indicating whether to show RevisionDeleted revisions
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineOldHeader( $differenceEngine, &$oldHeader,
		$prevlink, $oldminor, $diffOnly, $ldel, $unhide
	);
}
