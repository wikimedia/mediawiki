<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiQueryBase;
use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiQueryBaseProcessRow" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiQueryBaseProcessRowHook {
	/**
	 * This hook is called for (some) API query modules as each row of the database result is
	 * processed. An API query module wanting to use this hook should see the
	 * ApiQueryBase::select() and ApiQueryBase::processRow() documentation.
	 *
	 * @since 1.35
	 *
	 * @param ApiQueryBase $module Module in question
	 * @param stdClass $row Database result row
	 * @param array &$data Array to be included in the ApiResult
	 * @param array &$hookData Array that was be passed to the ApiQueryBaseBeforeQuery and
	 *   ApiQueryBaseAfterQuery hooks, intended for inter-hook communication
	 * @return bool|void True or no return value to continue, or false to stop processing the
	 *   result set
	 */
	public function onApiQueryBaseProcessRow( $module, $row, &$data, &$hookData );
}
