<?php

namespace MediaWiki\Block\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetAllBlockActions" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetAllBlockActionsHook {
	/**
	 * Use this hook to add an action or actions that may be blocked by a partial block.
	 *
	 * Add an item to the $actions array with:
	 * - key: unique action string (as it appears in the code)
	 * - value: unique integer ID
	 *
	 * The ID must be 100 or greater (IDs below 100 are reserved for core actions),
	 * must not conflict with other extension IDs, and must be documented at:
	 * https://www.mediawiki.org/wiki/Manual:Hooks/GetAllBlockActions
	 *
	 * @since 1.37
	 *
	 * @param int[] &$actions Array of actions, which may be added to
	 * @return bool|void True or no return value to continue; callers of this hook should not abort it
	 */
	public function onGetAllBlockActions( &$actions );
}
