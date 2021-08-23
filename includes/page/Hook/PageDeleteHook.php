<?php

namespace MediaWiki\Page\Hook;

use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use StatusValue;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PageDelete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PageDeleteHook {
	/**
	 * This hook is called before a page is deleted.
	 *
	 * @since 1.37
	 *
	 * @param ProperPageIdentity $page Page being deleted.
	 * @param Authority $deleter Who is deleting the page
	 * @param string $reason Reason the page is being deleted
	 * @param StatusValue $status Add any error here
	 * @param bool $suppress Whether this is a suppression deletion or not
	 * @return bool|void True or no return value to continue; false to abort, which also requires adding
	 * a fatal error to $status.
	 */
	public function onPageDelete(
		ProperPageIdentity $page,
		Authority $deleter,
		string $reason,
		StatusValue $status,
		bool $suppress
	);
}
