<?php

namespace MediaWiki\Content\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetContentModelsHook {
	/**
	 * Use this hook to add content models to the list of available models.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$models Array containing current model list as strings. Extensions should add to this list.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetContentModels( &$models );
}
