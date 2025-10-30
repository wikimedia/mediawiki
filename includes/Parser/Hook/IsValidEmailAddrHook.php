<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "isValidEmailAddr" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface IsValidEmailAddrHook {
	/**
	 * Use this hook to override the result of Sanitizer::validateEmail(), for
	 * instance to return false if the domain name doesn't match your organization.
	 *
	 * @since 1.35
	 *
	 * @param string $addr Email address entered by the user
	 * @param bool|null &$result Set this and return false to override the internal checks
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIsValidEmailAddr( $addr, &$result );
}
