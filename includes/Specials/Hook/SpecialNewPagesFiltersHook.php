<?php

namespace MediaWiki\Hook;

use MediaWiki\Specials\SpecialNewPages;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialNewPagesFilters" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialNewPagesFiltersHook {
	/**
	 * This hook is called after building form options at NewPages.
	 *
	 * @since 1.35
	 *
	 * @param SpecialNewPages $special The special page object
	 * @param array &$filters Associative array of filter definitions. The keys are the HTML
	 *   name/URL parameters. Each key maps to an associative array with a 'msg'
	 *   (message key) and a 'default' value.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialNewPagesFilters( $special, &$filters );
}
