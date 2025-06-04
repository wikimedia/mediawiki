<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetSecurityLogContext" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetSecurityLogContextHook {

	/**
	 * This hook is called from WebRequest::getSecurityLogContext() to collect information about
	 * the request that's worth logging for log events which are relevant for security or
	 * anti-abuse purposes (login, credentials changes etc).
	 *
	 * @param array $info Information array with the following fields:
	 *   - request: the WebRequest object
	 *   - user: a UserIdentity object, or null. This is the user the log event is associated with
	 *     (not necessarily the session user; not necessarily a locally existing user). The username
	 *     is never an IP address.
	 * @param array &$context The PSR-3 log context.
	 * @return void This hook must not abort, it must return no value
	 *
	 * @since 1.45
	 * @see WebRequest::getSecurityLogContext()
	 */
	public function onGetSecurityLogContext( array $info, array &$context ): void;

}
