<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use MediaWiki\RecentChanges\RecentChange;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RecentChange_save" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface RecentChange_saveHook {
	/**
	 * This hook is called at the end of RecentChange::save().
	 *
	 * @since 1.35
	 *
	 * @param RecentChange $recentChange
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRecentChange_save( $recentChange );
}
