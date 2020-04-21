<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetLocalURL__InternalHook {
	/**
	 * Modify local URLs to internal pages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object of page
	 * @param ?mixed &$url string value as output (out parameter, can modify)
	 * @param ?mixed $query query options as string passed to Title::getLocalURL()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLocalURL__Internal( $title, &$url, $query );
}
