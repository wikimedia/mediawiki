<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialPageAfterExecuteHook {
	/**
	 * Called after SpecialPage::execute.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $special the SpecialPage object
	 * @param ?mixed $subPage the subpage string or null if no subpage was specified
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPageAfterExecute( $special, $subPage );
}
