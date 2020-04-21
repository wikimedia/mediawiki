<?php

namespace MediaWiki\Content\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetContentModelsHook {
	/**
	 * Add content models to the list of available models.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$models array containing current model list, as strings. Extensions should add to this list.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetContentModels( &$models );
}
