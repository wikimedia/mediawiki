<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EmailUserFormHook {
	/**
	 * After building the email user form object.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$form HTMLForm object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUserForm( &$form );
}
