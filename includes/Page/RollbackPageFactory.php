<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page;

use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;

/**
 * Service for page rollback actions.
 *
 * Default implementation is MediaWiki\Page\PageCommandFactory.
 *
 * @since 1.37
 */
interface RollbackPageFactory {

	/**
	 * Create a new command instance for page rollback.
	 *
	 * @param PageIdentity $page
	 * @param Authority $performer
	 * @param UserIdentity $byUser
	 * @return RollbackPage
	 */
	public function newRollbackPage(
		PageIdentity $page,
		Authority $performer,
		UserIdentity $byUser
	): RollbackPage;
}
