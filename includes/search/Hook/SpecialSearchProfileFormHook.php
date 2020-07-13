<?php

namespace MediaWiki\Search\Hook;

use SpecialPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialSearchProfileFormHook {
	/**
	 * Use this hook to modify search profile forms.
	 *
	 * @since 1.35
	 *
	 * @param SpecialPage $search
	 * @param string &$form Form HTML
	 * @param string $profile Current search profile
	 * @param string $term Search term
	 * @param string[] $opts Key => value of hidden options for inclusion in custom forms
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchProfileForm( $search, &$form, $profile, $term,
		$opts
	);
}
