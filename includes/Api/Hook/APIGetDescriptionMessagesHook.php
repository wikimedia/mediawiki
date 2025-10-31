<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiBase;
use MediaWiki\Message\Message;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "APIGetDescriptionMessages" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface APIGetDescriptionMessagesHook {
	/**
	 * Use this hook to modify a module's help message.
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module
	 * @param Message[] &$msg
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIGetDescriptionMessages( $module, &$msg );
}
