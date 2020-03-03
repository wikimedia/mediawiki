<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforeHttpsRedirectHook {
	/**
	 * Prior to forcing HTTP->HTTPS redirect. Gives a chance to
	 * override how the redirect is output by modifying, or by returning false, and
	 * letting standard HTTP rendering take place.
	 * ATTENTION: This hook is likely to be removed soon due to overall design of the
	 * system.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context IContextSource object
	 * @param ?mixed &$redirect string URL, modifiable
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeHttpsRedirect( $context, &$redirect );
}
