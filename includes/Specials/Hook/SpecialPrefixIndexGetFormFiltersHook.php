<?php

namespace MediaWiki\Hook;

use MediaWiki\Context\IContextSource;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialPrefixIndexGetFormFilters" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialPrefixIndexGetFormFiltersHook {
	/**
	 * This hook is called with a list of filters to render on Special:PrefixIndex.
	 *
	 * @since 1.42
	 *
	 * @param IContextSource $contextSource
	 * @param array &$filters List of filter object definitions (compatible with OOUI form)
	 * @return void Un-abortable hook
	 */
	public function onSpecialPrefixIndexGetFormFilters( IContextSource $contextSource, array &$filters );
}
