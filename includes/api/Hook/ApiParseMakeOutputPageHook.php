<?php

namespace MediaWiki\Api\Hook;

use ApiBase;
use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiParseMakeOutputPageHook {
	/**
	 * This hook is called when preparing the OutputPage object for
	 * ApiParse. This is mainly intended for calling OutputPage::addContentOverride()
	 * or OutputPage::addContentOverrideCallback().
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module ApiBase (which is also a ContextSource)
	 * @param OutputPage $output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiParseMakeOutputPage( $module, $output );
}
