<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetInternalURLHook {
	/**
	 * Modify fully-qualified URLs used for squid cache purging.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object of page
	 * @param ?mixed &$url string value as output (out parameter, can modify)
	 * @param ?mixed $query query options as string passed to Title::getInternalURL()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetInternalURL( $title, &$url, $query );
}
