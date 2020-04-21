<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WebRequestPathInfoRouterHook {
	/**
	 * While building the PathRouter to parse the
	 * REQUEST_URI.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $router The PathRouter instance
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWebRequestPathInfoRouter( $router );
}
