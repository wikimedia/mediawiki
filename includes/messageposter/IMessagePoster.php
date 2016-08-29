<?php

/**
 * IMessagePoster
 *
 * @copyright GPL http://www.gnu.org/copyleft/gpl.html
 * @author Matthew Flaschen
 * @ingroup MessagePoster
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace MediaWiki\MessagePoster;

use Title;
use User;

/**
 * This interface allows creating a talk page topic.  It is implemented by multiple
 * discussion systems with different storage models.  All permission checks are performed
 * by the IMessagePoster.
 */
interface IMessagePoster {
	/**
	 * @var int Whether to mark the post/edit as a bot action.  This is only
	 *   supported if the IMessagePoster supports the bot marker, and the user has the
	 *   right to use it.  Otherwise, it will be ignored.
	 */
	const BOT_EDIT = 1;

	/**
	 * Post a topic (with subject and body) to a talk page.  The IMessagePoster
	 * does all permission checks.
	 *
	 * @param Title $title Tile of talk page
	 * @param User $user User making the post/edit.  This should normally be either a
	 *   system user, or the current user.
	 * @param string $subject Subject/topic title.  The amount of wikitext supported is
	 *   implementation-specific. It is recommended to only use basic wikilink syntax for
	 *   maximum compatibility.
	 * @param string $body Body, as wikitext.  Signature code will automatically be added
	 *   by IMessagePoster implementations that require one, unless the message already
	 *   contains the string ~~~.
	 * @param int $flags Currently, the only option is BOT_EDIT.  Defaults to no flags
	 *   (0).
	 *
	 * @throws MWException Throws if the post fails for any reason, including user
	 *   permissions.
	 */
	public function postTopic( Title $title, User $user, $subject, $body, $flags = 0 );
}
