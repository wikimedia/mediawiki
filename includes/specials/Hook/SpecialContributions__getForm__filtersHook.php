<?php

namespace MediaWiki\Hook;

use MediaWiki\SpecialPage\ContributionsSpecialPage;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialContributions::getForm::filters" to register handlers implementing this interface.
 *
 * This hook is run for any ContributionsSpecialPage, not just SpecialContributions.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialContributions__getForm__filtersHook {
	/**
	 * This hook is called with a list of filters to render on Special:Contributions.
	 *
	 * @since 1.35
	 *
	 * @param ContributionsSpecialPage $sp Special page object, for context
	 * @param array &$filters List of filter object definitions (compatible with OOUI form)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialContributions__getForm__filters( $sp, &$filters );
}
