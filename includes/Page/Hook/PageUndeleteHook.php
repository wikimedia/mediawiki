<?php

namespace MediaWiki\Page\Hook;

use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use StatusValue;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PageUndelete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PageUndeleteHook {
	/**
	 * This hook is called before (part of) a page is undeleted.
	 *
	 * @since 1.37
	 *
	 * @param ProperPageIdentity $page Page being undeleted.
	 * @param Authority $performer Who is undeleting the page
	 * @param string $reason Reason the page is being undeleted
	 * @param bool $unsuppress Whether content is being unsuppressed or not
	 * @param string[] $timestamps Timestamps of revisions that we're going to undelete. If empty, means all revisions.
	 * @param int[] $fileVersions Versions of a file that we're going to undelete. If empty, means all versions.
	 * @param StatusValue $status Add any error here.
	 * @return bool|void True or no return value to continue; false to abort, which also requires adding
	 * a fatal error to $status.
	 */
	public function onPageUndelete(
		ProperPageIdentity $page,
		Authority $performer,
		string $reason,
		bool $unsuppress,
		array $timestamps,
		array $fileVersions,
		StatusValue $status
	);
}
