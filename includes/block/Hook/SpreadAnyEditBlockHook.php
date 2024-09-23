<?php

namespace MediaWiki\Block\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpreadAnyEditBlock" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpreadAnyEditBlockHook {
	/**
	 * Use this hook to spread any blocks that are not provided by core when User::spreadAnyEditBlock
	 * is called.
	 *
	 * @since 1.43
	 *
	 * @param User $user The user to check for blocks that should be spread
	 * @param bool &$blockWasSpread Whether any block was spread
	 * @return bool|void True or no return value to continue, or false to cancel
	 */
	public function onSpreadAnyEditBlock( $user, bool &$blockWasSpread );
}
