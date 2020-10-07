<?php

namespace MediaWiki\Api\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use ApiMain;
use Throwable;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiMain__onExceptionHook {
	/**
	 * This hook is called by ApiMain::executeActionWithErrorHandling() when
	 * an exception is thrown during API action execution.
	 *
	 * @since 1.35
	 *
	 * @param ApiMain $apiMain Calling ApiMain instance
	 * @param Throwable $e
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiMain__onException( $apiMain, $e );
}
