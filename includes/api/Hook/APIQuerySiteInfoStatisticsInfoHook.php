<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface APIQuerySiteInfoStatisticsInfoHook {
	/**
	 * Use this hook to add extra information to the site's statistics information.
	 *
	 * @since 1.35
	 *
	 * @param array &$results Array of results, add things here
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQuerySiteInfoStatisticsInfo( &$results );
}
