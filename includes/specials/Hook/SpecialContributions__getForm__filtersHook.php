<?php

namespace MediaWiki\Hook;

use SpecialContributions;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialContributions__getForm__filtersHook {
	/**
	 * This hook is called with a list of filters to render on Special:Contributions.
	 *
	 * @since 1.35
	 *
	 * @param SpecialContributions $sp SpecialContributions object, for context
	 * @param array &$filters List of filter object definitions (compatible with OOUI form)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialContributions__getForm__filters( $sp, &$filters );
}
