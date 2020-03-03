<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AfterFinalPageOutputHook {
	/**
	 * Nearly at the end of OutputPage::output() but
	 * before OutputPage::sendCacheControl() and final ob_end_flush() which
	 * will send the buffered output to the client. This allows for last-minute
	 * modification of the output within the buffer by using ob_get_clean().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $output The OutputPage object where output() was called
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onAfterFinalPageOutput( $output );
}
