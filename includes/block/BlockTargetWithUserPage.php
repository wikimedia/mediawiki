<?php

namespace MediaWiki\Block;

use MediaWiki\Page\PageReference;

/**
 * Shared interface for user and single IP targets, that is, for targets with a
 * meaningful user page link. Single IP addresses are traditionally treated as
 * the names of anonymous users.
 *
 * @since 1.44
 */
interface BlockTargetWithUserPage extends BlockTargetWithUserIdentity {
	/**
	 * Get the target's user page. The page has an associated talk page which
	 * can be used to talk to the target.
	 *
	 * @return PageReference
	 */
	public function getUserPage(): PageReference;
}
