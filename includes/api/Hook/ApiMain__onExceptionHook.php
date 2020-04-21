<?php

namespace MediaWiki\Api\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiMain__onExceptionHook {
	/**
	 * Called by ApiMain::executeActionWithErrorHandling() when
	 * an exception is thrown during API action execution.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $apiMain Calling ApiMain instance.
	 * @param ?mixed $e Exception object.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiMain__onException( $apiMain, $e );
}
