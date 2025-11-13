<?php

namespace MediaWiki\Specials\Hook;

use MediaWiki\HTMLForm\HTMLForm;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EmailUserForm" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EmailUserFormHook {
	/**
	 * This hook is called after building the email user form object.
	 *
	 * @since 1.35
	 *
	 * @param HTMLForm &$form HTMLForm object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUserForm( &$form );
}

/** @deprecated class alias since 1.46 */
class_alias( EmailUserFormHook::class, 'MediaWiki\\Hook\\EmailUserFormHook' );
