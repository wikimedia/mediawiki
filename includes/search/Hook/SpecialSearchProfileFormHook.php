<?php

namespace MediaWiki\Search\Hook;

use MediaWiki\SpecialPage\SpecialPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialSearchProfileForm" to register handlers implementing this interface.
 *
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
