<?php

namespace MediaWiki\MessagePoster;

use User;

/**
 * This interface allows creating a talk page topic.  It is implemented by multiple
 * discussion systems with different storage models.  All permission checks are performed
 * by the IMessagePoster.
 */
interface IMessagePoster {
	public function __construct( $title );

	/**
	 * Post a topic (with subject and body) to a talk page.  The IMessagePoster
	 * does all permission checks.
	 *
	 * @param User $user User making the post/edit.  This should normally be either a
	 *   system user, or the current user.
	 * @param string $subject Subject/topic title.  The amount of wikitext supported is
	 *   implementation-specific. It is recommended to only use basic wikilink syntax for
	 *   maximum compatibility.
	 * @param string $body Body, as wikitext.  Signature code will automatically be added
	 *   by IMessagePoster implementations that require one, unless the message already
	 *   contains the string ~~~.
	 * @param boolean $bot Whether to mark the post/edit as a bot action.  This is only
	 *   supported if the IMessagePoster supports the bot marker, and the user has the
	 *   right to use it.  Otherwise, it will be ignored (optional, defaults false)
	 *
	 * @throws MWException Throws if the post fails for any reason, including user
	 *   permissions.
	 */
	public function post( User $user, $subject, $body, $bot = false );
}
