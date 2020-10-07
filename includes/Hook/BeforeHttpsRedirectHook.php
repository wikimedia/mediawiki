<?php

namespace MediaWiki\Hook;

use IContextSource;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface BeforeHttpsRedirectHook {
	/**
	 * This hook is called prior to forcing HTTP->HTTPS redirect. Use this hook
	 * to override how the redirect is output.
	 * ATTENTION: This hook is likely to be removed soon due to overall design of the
	 * system.
	 *
	 * @since 1.35
	 *
	 * @param IContextSource $context
	 * @param string &$redirect string URL, modifiable
	 * @return bool|void True or no return value to continue, or false to let standard HTTP rendering
	 *   take place
	 */
	public function onBeforeHttpsRedirect( $context, &$redirect );
}
