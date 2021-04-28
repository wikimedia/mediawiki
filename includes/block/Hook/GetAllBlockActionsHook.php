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
	 * Use this hook to add an action to block on
	 *
	 * @since 1.37
	 *
	 * @param int[] &$actions Array of actions, which may be added to
	 * @return bool|void True or no return value to continue; callers of this hook should not abort it
	 */
	public function onGetAllBlockActions( &$actions );
}
