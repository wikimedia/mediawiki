<?php

namespace MediaWiki\Api\Hook;

use WebRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface RequestHasSameOriginSecurityHook {
	/**
	 * Use this hook to determine if the request is somehow flagged to lack same-origin security.
	 * Note that if the "somehow" involves HTTP headers, you'll probably need to make sure
	 * the header is varied on.
	 *
	 * @since 1.35
	 *
	 * @param WebRequest $request
	 * @return bool|void True or no return value to continue, or false to indicate a lack of
	 *   same-origin security
	 */
	public function onRequestHasSameOriginSecurity( $request );
}
