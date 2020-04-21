<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIQuerySiteInfoGeneralInfoHook {
	/**
	 * Use this hook to add extra information to the
	 * sites general information.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module the current ApiQuerySiteinfo module
	 * @param ?mixed &$results array of results, add things here
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQuerySiteInfoGeneralInfo( $module, &$results );
}
