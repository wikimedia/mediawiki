<?php

namespace MediaWiki\SpecialPage\Hook;

use SpecialPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialPageAfterExecuteHook {
	/**
	 * This hook is called after SpecialPage::execute.
	 *
	 * @since 1.35
	 *
	 * @param SpecialPage $special
	 * @param string|null $subPage Subpage string, or null if no subpage was specified
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPageAfterExecute( $special, $subPage );
}
