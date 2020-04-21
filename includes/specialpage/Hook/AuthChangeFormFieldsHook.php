<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AuthChangeFormFieldsHook {
	/**
	 * After converting a field information array obtained
	 * from a set of AuthenticationRequest classes into a form descriptor; hooks
	 * can tweak the array to change how login etc. forms should look.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $requests array of AuthenticationRequests the fields are created from
	 * @param ?mixed $fieldInfo field information array (union of all
	 *   AuthenticationRequest::getFieldInfo() responses).
	 * @param ?mixed &$formDescriptor HTMLForm descriptor. The special key 'weight' can be set
	 *   to change the order of the fields.
	 * @param ?mixed $action one of the AuthManager::ACTION_* constants.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAuthChangeFormFields( $requests, $fieldInfo,
		&$formDescriptor, $action
	);
}
