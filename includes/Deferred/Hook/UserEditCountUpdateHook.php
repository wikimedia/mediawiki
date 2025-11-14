<?php

namespace MediaWiki\Deferred\Hook;

use MediaWiki\Deferred\UserEditCountInfo;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserEditCountUpdate" to register handlers implementing this interface.
 *
 * @stable to implement
 * @since 1.38
 * @ingroup Hooks
 */
interface UserEditCountUpdateHook {
	/**
	 * This is called from a deferred update on edit or move and provides
	 * collected user edit count information.
	 *
	 * @param UserEditCountInfo[] $infos
	 * @return void
	 */
	public function onUserEditCountUpdate( $infos ): void;
}

/** @deprecated class alias since 1.46 */
class_alias( UserEditCountUpdateHook::class, 'MediaWiki\\Hook\\UserEditCountUpdateHook' );
