<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIGetDescriptionMessagesHook {
	/**
	 * Use this hook to modify a module's help message.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiBase Module object
	 * @param ?mixed &$msg Array of Message objects
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIGetDescriptionMessages( $module, &$msg );
}
