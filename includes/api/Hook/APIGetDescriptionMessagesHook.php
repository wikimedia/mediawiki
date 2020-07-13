<?php

namespace MediaWiki\Api\Hook;

use ApiBase;
use Message;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface APIGetDescriptionMessagesHook {
	/**
	 * Use this hook to modify a module's help message.
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module Module object
	 * @param Message[] &$msg Array of Message objects
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIGetDescriptionMessages( $module, &$msg );
}
