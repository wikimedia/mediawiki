<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialRecentChangesPanelHook {
	/**
	 * Called when building form options in
	 * SpecialRecentChanges.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$extraOpts array of added items, to which can be added
	 * @param ?mixed $opts FormOptions for this request
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialRecentChangesPanel( &$extraOpts, $opts );
}
