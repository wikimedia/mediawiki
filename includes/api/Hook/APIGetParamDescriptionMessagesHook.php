<?php

namespace MediaWiki\Api\Hook;

use ApiBase;
use Message;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface APIGetParamDescriptionMessagesHook {
	/**
	 * Use this hook to modify a module's parameter descriptions.
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module Module object
	 * @param Message[][] &$msg Array of arrays of Message objects
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIGetParamDescriptionMessages( $module, &$msg );
}
