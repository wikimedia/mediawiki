<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIQuerySiteInfoStatisticsInfoHook {
	/**
	 * Use this hook to add extra information to the
	 * sites statistics information.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$results array of results, add things here
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQuerySiteInfoStatisticsInfo( &$results );
}
