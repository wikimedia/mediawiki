<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiQueryBaseProcessRowHook {
	/**
	 * Called for (some) API query modules as each row of
	 * the database result is processed. Return false to stop processing the result
	 * set. An API query module wanting to use this hook should see the
	 * ApiQueryBase::select() and ApiQueryBase::processRow() documentation.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiQueryBase module in question
	 * @param ?mixed $row stdClass Database result row
	 * @param ?mixed &$data array to be included in the ApiResult.
	 * @param ?mixed &$hookData array that was be passed to the 'ApiQueryBaseBeforeQuery' and
	 *   'ApiQueryBaseAfterQuery' hooks, intended for inter-hook communication.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryBaseProcessRow( $module, $row, &$data, &$hookData );
}
