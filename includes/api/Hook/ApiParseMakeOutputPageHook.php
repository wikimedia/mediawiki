<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiParseMakeOutputPageHook {
	/**
	 * Called when preparing the OutputPage object for
	 * ApiParse. This is mainly intended for calling OutputPage::addContentOverride()
	 * or OutputPage::addContentOverrideCallback().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiBase (which is also a ContextSource)
	 * @param ?mixed $output OutputPage
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiParseMakeOutputPage( $module, $output );
}
