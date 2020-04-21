<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIGetAllowedParamsHook {
	/**
	 * Use this hook to modify a module's parameters.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiBase Module object
	 * @param ?mixed &$params Array of parameters
	 * @param ?mixed $flags int zero or OR-ed flags like ApiBase::GET_VALUES_FOR_HELP
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIGetAllowedParams( $module, &$params, $flags );
}
