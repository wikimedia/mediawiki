<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiQueryBaseAfterQueryHook {
	/**
	 * Called for (some) API query modules after the
	 * database query has returned. An API query module wanting to use this hook
	 * should see the ApiQueryBase::select() and ApiQueryBase::processRow()
	 * documentation.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiQueryBase module in question
	 * @param ?mixed $result ResultWrapper|bool returned from the IDatabase::select()
	 * @param ?mixed &$hookData array that was passed to the 'ApiQueryBaseBeforeQuery' hook and
	 *   will be passed to the 'ApiQueryBaseProcessRow' hook, intended for inter-hook
	 *   communication.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryBaseAfterQuery( $module, $result, &$hookData );
}
