<?php

namespace MediaWiki\Storage;

use Content;
use Title;

/**
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
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.27
 *
 * @file
 * @ingroup Storage
 *
 * @author Daniel Kinzler
 */

/**
 * RevisionContentLookup defines a service for accessing content associated with the individual
 * slots of a revision.
 *
 * @note Implementations may or may not enforce access restrictions.
 *
 * @todo replace Title with PageRecord or RevisionRecord.
 */
interface RevisionContentLookup {

	/**
	 * @param Title $title
	 * @param int $revisionId The revision ID (not 0)
	 * @param string $slot The slot name.
	 *
	 * @throws RevisionContentException
	 * @return RevisionSlot
	 */
	function getRevisionSlot( Title $title, $revisionId, $slot = 'main' );

	/**
	 * Lists the primary content slots associated with the given revision. Primary slots contain
	 * original, user supplied content. They are persistent and immutable.
	 *
	 * @note A RevisionContentLookup used by application logic should always at least include
	 * the "main" slot here. Implementations of RevisionContentLookup for internal use are however
	 * free to return an empty list, or a list not including "main".
	 *
	 * @param Title $title
	 * @param int $revisionId
	 *
	 * @throws RevisionContentException
	 * @return string[] The ids of all primary slots for the given revision.
	 */
	function listPrimarySlots( Title $title, $revisionId );

}
