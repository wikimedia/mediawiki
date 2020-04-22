<?php

namespace MediaWiki\Hook;

use SpecialNewPages;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialNewPagesFiltersHook {
	/**
	 * This hook is called after building form options at NewPages.
	 *
	 * @since 1.35
	 *
	 * @param SpecialNewPages $special the special page object
	 * @param array &$filters associative array of filter definitions. The keys are the HTML
	 *   name/URL parameters. Each key maps to an associative array with a 'msg'
	 *   (message key) and a 'default' value.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialNewPagesFilters( $special, &$filters );
}
