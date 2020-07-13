<?php

namespace MediaWiki\SpecialPage\Hook;

use MediaWiki\Auth\AuthenticationRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface AuthChangeFormFieldsHook {
	/**
	 * This hook is called after converting a field information array obtained
	 * from a set of AuthenticationRequest classes into a form descriptor; hooks
	 * can tweak the array to change how login etc. forms should look.
	 *
	 * @since 1.35
	 *
	 * @param AuthenticationRequest[] $requests Array of AuthenticationRequests the fields
	 *   are created from
	 * @param array $fieldInfo Field information array (union of all
	 *   AuthenticationRequest::getFieldInfo() responses)
	 * @param array &$formDescriptor HTMLForm descriptor. The special key 'weight' can be set
	 *   to change the order of the fields.
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAuthChangeFormFields( $requests, $fieldInfo,
		&$formDescriptor, $action
	);
}
