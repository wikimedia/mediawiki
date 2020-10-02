<?php

namespace MediaWiki\Hook;

use OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BeforePageRedirect" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforePageRedirectHook {
	/**
	 * This hook is called prior to sending an HTTP redirect. Gives a chance to
	 * override how the redirect is output by modifying, or by returning false and
	 * taking over the output.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out
	 * @param string &$redirect URL, modifiable
	 * @param int &$code HTTP code (eg '301' or '302'), modifiable
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforePageRedirect( $out, &$redirect, &$code );
}
