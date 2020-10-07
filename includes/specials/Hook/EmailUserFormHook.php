<?php

namespace MediaWiki\Hook;

use HTMLForm;

/**
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
