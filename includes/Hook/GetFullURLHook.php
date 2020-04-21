<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetFullURLHook {
	/**
	 * Modify fully-qualified URLs used in redirects/export/offsite data.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object of page
	 * @param ?mixed &$url string value as output (out parameter, can modify)
	 * @param ?mixed $query query options as string passed to Title::getFullURL()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetFullURL( $title, &$url, $query );
}
