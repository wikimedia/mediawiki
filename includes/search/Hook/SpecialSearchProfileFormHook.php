<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchProfileFormHook {
	/**
	 * Allows modification of search profile forms.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $search special page object
	 * @param ?mixed &$form String: form html
	 * @param ?mixed $profile String: current search profile
	 * @param ?mixed $term String: search term
	 * @param ?mixed $opts Array: key => value of hidden options for inclusion in custom forms
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchProfileForm( $search, &$form, $profile, $term,
		$opts
	);
}
