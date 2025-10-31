<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiQueryBase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiQueryBaseAfterQuery" to register handlers implementing this interface.
 *
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
	 * @param IResultWrapper $result ResultWrapper
	 * @param array &$hookData Array that was passed to the ApiQueryBaseBeforeQuery hook and
	 *   will be passed to the ApiQueryBaseProcessRow hook, intended for inter-hook
	 *   communication
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryBaseAfterQuery( $module, $result, &$hookData );
}
