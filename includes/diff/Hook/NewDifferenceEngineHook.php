<?php

namespace MediaWiki\Diff\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface NewDifferenceEngineHook {
	/**
	 * This hook is called when a new DifferenceEngine object is made.
	 *
	 * @since 1.35
	 *
	 * @param Title|null $title Diff page title
	 * @param int|false|null &$oldId Actual old ID to use in the diff
	 * @param int|false|null &$newId Actual new Id to use in the diff (0 means current)
	 * @param int $old ?old= param value from the URL
	 * @param int $new ?new= param value from the URL
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onNewDifferenceEngine( $title, &$oldId, &$newId, $old, $new );
}
