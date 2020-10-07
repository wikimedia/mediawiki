<?php

namespace MediaWiki\Hook;

use Content;
use IContextSource;
use Status;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditFilterMergedContentHook {
	/**
	 * Use this hook for a post-section-merge edit filter. This may be triggered by
	 * the EditPage or any other facility that modifies page content. Use the $status
	 * object to indicate whether the edit should be allowed and to provide a reason
	 * for disallowing it. $status->apiHookResult can be set to an array to be returned
	 * by api.php action=edit. This is used to deliver captchas.
	 *
	 * @since 1.35
	 *
	 * @param IContextSource $context
	 * @param Content $content Content of the edit box
	 * @param Status $status Status object to represent errors, etc.
	 * @param string $summary Edit summary for page
	 * @param User $user User whois performing the edit
	 * @param bool $minoredit Whether the edit was marked as minor by the user.
	 * @return bool|void True or no return value to continue or false to abort the edit.
	 *   Returning true if $status->isOK() returns false means "don't save but continue user
	 *   interaction", e.g. show the edit form.
	 */
	public function onEditFilterMergedContent( $context, $content, $status,
		$summary, $user, $minoredit
	);
}
