<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface IsUploadAllowedFromUrlHook {
	/**
	 * Override the result of UploadFromUrl::isAllowedUrl()
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $url URL used to upload from
	 * @param ?mixed &$allowed Boolean indicating if uploading is allowed for given URL
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIsUploadAllowedFromUrl( $url, &$allowed );
}
