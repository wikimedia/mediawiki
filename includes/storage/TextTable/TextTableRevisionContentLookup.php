<?php

namespace MediaWiki\Storage\TextTable;

use Content;
use MediaWiki\Storage\RevisionContentException;
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
 * Implementation of RevisionContentLookup that using the traditional storage schema
 * based on the text table. This supports only the "main" slot.
 */
class TextTableRevisionContentLookup implements RevisionContentLookup {

	/**
	 * Returns the Content of the given revision slot
	 *
	 * @param Title $title
	 * @param int $revisionId The revision ID (not 0)
	 * @param string $slot The slot name.
	 *
	 * @throws RevisionContentException
	 * @return Content
	 */
	function getRevisionContent( Title $title, $revisionId, $slot = 'main' ) {
		if ( $slot !== 'main' ) {
			throw new NoSuchSlotException( $title, $revisionId, $slot );
		}
	}

	/**
	 * @param Title $title
	 * @param int $revisionId The revision ID (not 0)
	 * @param string $slot The slot name.
	 *
	 * @throws RevisionContentException
	 * @return RevisionSlot
	 */
	function getRevisionSlot( Title $title, $revisionId, $slot = 'main' ) {
		if ( $slot !== 'main' ) {
			throw new NoSuchSlotException( $title, $revisionId, $slot );
		}

		return new SimpleRevisionSlot(
			$slot,
			$title->getContentModel(), // NOTE: we have to be careful not to get a circular dependency here.
			$title->getTouched()
		);
	}

	/**
	 * Lists the primary content slots associated with the given revision. Primary slots contain
	 * original, user supplied content. They are persistent and immutable.
	 *
	 * @param Title $title
	 * @param int $revisionId
	 *
	 * @throws RevisionContentException
	 * @return string[]
	 */
	function listPrimarySlots( Title $title, $revisionId ) {
		return array( 'main' );
	}

}
