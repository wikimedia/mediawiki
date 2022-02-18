<?php

namespace MediaWiki\Hook;

use MediaWiki\Page\PageReference;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialExportGetExtraPages" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialExportGetExtraPagesHook {
	/**
	 * Add extra pages to the list of pages to export.
	 *
	 * @since 1.38
	 *
	 * @param string[] $inputPages List of page titles to export
	 * @param PageReference[] &$extraPages List of extra page titles
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialExportGetExtraPages( array $inputPages, array &$extraPages );
}
