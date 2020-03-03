<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContributionsToolLinksHook {
	/**
	 * Change tool links above Special:Contributions
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $id User identifier
	 * @param ?mixed $title User page title
	 * @param ?mixed &$tools Array of tool links
	 * @param ?mixed $specialPage SpecialPage instance for context and services. Can be either
	 *   SpecialContributions or DeletedContributionsPage. Extensions should type
	 *   hint against a generic SpecialPage though.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContributionsToolLinks( $id, $title, &$tools, $specialPage );
}
