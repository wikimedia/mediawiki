<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;
use Revision;

/**
 * @deprecated since 1.35, use DifferenceEngineViewHeader
 * @ingroup Hooks
 */
interface DiffViewHeaderHook {
	/**
	 * This hook is called before diff display.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $diff
	 * @param Revision|null $oldRev Old revision (may be null/invalid)
	 * @param Revision $newRev New revision
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDiffViewHeader( $diff, $oldRev, $newRev );
}
