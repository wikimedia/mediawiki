<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditFilterMergedContentHook {
	/**
	 * Post-section-merge edit filter.
	 * This may be triggered by the EditPage or any other facility that modifies page
	 * content. Use the $status object to indicate whether the edit should be allowed,
	 * and to provide a reason for disallowing it. Return false to abort the edit, and
	 * true to continue. Returning true if $status->isOK() returns false means "don't
	 * save but continue user interaction", e.g. show the edit form.
	 * $status->apiHookResult can be set to an array to be returned by api.php
	 *   action=edit. This is used to deliver captchas.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context object implementing the IContextSource interface.
	 * @param ?mixed $content content of the edit box, as a Content object.
	 * @param ?mixed $status Status object to represent errors, etc.
	 * @param ?mixed $summary Edit summary for page
	 * @param ?mixed $user the User object representing the user whois performing the edit.
	 * @param ?mixed $minoredit whether the edit was marked as minor by the user.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditFilterMergedContent( $context, $content, $status,
		$summary, $user, $minoredit
	);
}
