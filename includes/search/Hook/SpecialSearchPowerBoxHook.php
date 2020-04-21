<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchPowerBoxHook {
	/**
	 * The equivalent of SpecialSearchProfileForm for
	 * the advanced form, a.k.a. power search box.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$showSections an array to add values with more options to
	 * @param ?mixed $term the search term (not a title object)
	 * @param ?mixed &$opts an array of hidden options (containing 'redirs' and 'profile')
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchPowerBox( &$showSections, $term, &$opts );
}
