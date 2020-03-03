<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforePageRedirectHook {
	/**
	 * Prior to sending an HTTP redirect. Gives a chance to
	 * override how the redirect is output by modifying, or by returning false and
	 * taking over the output.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out OutputPage object
	 * @param ?mixed &$redirect URL, modifiable
	 * @param ?mixed &$code HTTP code (eg '301' or '302'), modifiable
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforePageRedirect( $out, &$redirect, &$code );
}
