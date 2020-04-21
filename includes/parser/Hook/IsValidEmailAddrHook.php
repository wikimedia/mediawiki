<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface IsValidEmailAddrHook {
	/**
	 * Override the result of Sanitizer::validateEmail(), for
	 * instance to return false if the domain name doesn't match your organization.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $addr The e-mail address entered by the user
	 * @param ?mixed &$result Set this and return false to override the internal checks
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIsValidEmailAddr( $addr, &$result );
}
