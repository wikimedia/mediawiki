<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetCanonicalURLHook {
	/**
	 * Modify fully-qualified URLs used for IRC and e-mail
	 * notifications.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object of page
	 * @param ?mixed &$url string value as output (out parameter, can modify)
	 * @param ?mixed $query query options as string passed to Title::getCanonicalURL()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetCanonicalURL( $title, &$url, $query );
}
