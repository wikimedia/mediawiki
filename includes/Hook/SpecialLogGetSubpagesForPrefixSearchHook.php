<?php

namespace MediaWiki\Hook;

use MediaWiki\Context\IContextSource;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialLogGetSubpagesForPrefixSearch" to register handlers
 * implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialLogGetSubpagesForPrefixSearchHook {
	/**
	 * Hook for intercepting and changing the list of suggested log types when
	 * typing "Special:Log" in the search box.
	 *
	 * The common use case for this hook is to include aliases for the log type
	 * in the suggestions.
	 *
	 * @param IContextSource $context Request context
	 * @param string[] &$subpages Log subpages currently known
	 *
	 * @return void
	 * @since 1.46
	 */
	public function onSpecialLogGetSubpagesForPrefixSearch(
		IContextSource $context,
		array &$subpages
	): void;
}
