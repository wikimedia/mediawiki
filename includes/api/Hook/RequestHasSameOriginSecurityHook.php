<?php

namespace MediaWiki\Api\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RequestHasSameOriginSecurity" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface RequestHasSameOriginSecurityHook {
	/**
	 * Use this hook to determine if the request is somehow flagged to lack same-origin security.
	 * Note that if the "somehow" involves HTTP headers, you'll probably need to make sure
	 * the header is varied on.
	 *
	 * @param \MediaWiki\Request\WebRequest $request
	 * @return bool|void True or no return value to continue, or false to indicate a lack of
	 *   same-origin security
	 * @since 1.35
	 *
	 */
	public function onRequestHasSameOriginSecurity( $request );
}
