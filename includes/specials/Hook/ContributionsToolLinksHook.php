<?php

namespace MediaWiki\Hook;

use SpecialPage;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ContributionsToolLinksHook {
	/**
	 * Use this hook to change the tool links above Special:Contributions.
	 *
	 * @since 1.35
	 *
	 * @param int $id User identifier
	 * @param Title $title User page title
	 * @param string[] &$tools Array of tool links
	 * @param SpecialPage $specialPage SpecialPage instance for context and services. Can be either
	 *   SpecialContributions or DeletedContributionsPage. Extensions should type
	 *   hint against a generic SpecialPage though.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContributionsToolLinks( $id, $title, &$tools, $specialPage );
}
