<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface NewDifferenceEngineHook {
	/**
	 * Called when a new DifferenceEngine object is made
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title the diff page title (nullable)
	 * @param ?mixed &$oldId the actual old Id to use in the diff
	 * @param ?mixed &$newId the actual new Id to use in the diff (0 means current)
	 * @param ?mixed $old the ?old= param value from the url
	 * @param ?mixed $new the ?new= param value from the url
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onNewDifferenceEngine( $title, &$oldId, &$newId, $old, $new );
}
