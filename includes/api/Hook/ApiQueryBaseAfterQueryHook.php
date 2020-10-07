<?php

namespace MediaWiki\Api\Hook;

use ApiQueryBase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiQueryBaseAfterQueryHook {
	/**
	 * This hook is called for (some) API query modules after the
	 * database query has returned. An API query module wanting to use this hook
	 * should see the ApiQueryBase::select() and ApiQueryBase::processRow()
	 * documentation.
	 *
	 * @since 1.35
	 *
	 * @param ApiQueryBase $module Module in question
	 * @param IResultWrapper|bool $result ResultWrapper or bool returned from the IDatabase::select()
	 * @param array &$hookData Array that was passed to the ApiQueryBaseBeforeQuery hook and
	 *   will be passed to the ApiQueryBaseProcessRow hook, intended for inter-hook
	 *   communication
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryBaseAfterQuery( $module, $result, &$hookData );
}
