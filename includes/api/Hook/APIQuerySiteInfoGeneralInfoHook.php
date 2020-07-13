<?php

namespace MediaWiki\Api\Hook;

use ApiQuerySiteinfo;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface APIQuerySiteInfoGeneralInfoHook {
	/**
	 * Use this hook to add extra information to the site's general information.
	 *
	 * @since 1.35
	 *
	 * @param ApiQuerySiteinfo $module Current ApiQuerySiteinfo module
	 * @param array &$results Array of results, add things here
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQuerySiteInfoGeneralInfo( $module, &$results );
}
