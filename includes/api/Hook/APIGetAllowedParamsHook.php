<?php

namespace MediaWiki\Api\Hook;

use ApiBase;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface APIGetAllowedParamsHook {
	/**
	 * Use this hook to modify a module's parameters.
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module Module object
	 * @param array &$params Array of parameters
	 * @param int $flags Zero or OR-ed flags like ApiBase::GET_VALUES_FOR_HELP
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIGetAllowedParams( $module, &$params, $flags );
}
