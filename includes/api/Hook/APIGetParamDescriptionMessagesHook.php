<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIGetParamDescriptionMessagesHook {
	/**
	 * Use this hook to modify a module's parameter
	 * descriptions.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiBase Module object
	 * @param ?mixed &$msg Array of arrays of Message objects
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIGetParamDescriptionMessages( $module, &$msg );
}
