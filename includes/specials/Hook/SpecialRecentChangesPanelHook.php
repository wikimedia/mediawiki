<?php

namespace MediaWiki\Hook;

use MediaWiki\Html\FormOptions;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialRecentChangesPanel" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialRecentChangesPanelHook {
	/**
	 * This hook is called when building form options in SpecialRecentChanges.
	 *
	 * @since 1.35
	 *
	 * @param array &$extraOpts Array of added items, to which can be added
	 * @param FormOptions $opts FormOptions for this request
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialRecentChangesPanel( &$extraOpts, $opts );
}
