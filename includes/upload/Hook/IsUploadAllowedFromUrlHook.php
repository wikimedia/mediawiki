<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface IsUploadAllowedFromUrlHook {
	/**
	 * Use this hook to override the result of UploadFromUrl::isAllowedUrl().
	 *
	 * @since 1.35
	 *
	 * @param string $url URL used to upload from
	 * @param bool &$allowed Whether uploading is allowed for given URL
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIsUploadAllowedFromUrl( $url, &$allowed );
}
