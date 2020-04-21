<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RequestHasSameOriginSecurityHook {
	/**
	 * Called to determine if the request is somehow
	 * flagged to lack same-origin security. Return false to indicate the lack. Note
	 * if the "somehow" involves HTTP headers, you'll probably need to make sure
	 * the header is varied on.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $request The WebRequest object.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRequestHasSameOriginSecurity( $request );
}
