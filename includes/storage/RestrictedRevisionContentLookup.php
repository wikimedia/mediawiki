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
 * Abstract base implementation of RevisionContentLookup that enforces access control.
 */
abstract class RestrictedRevisionContentLookup implements RevisionContentLookup {

	/**
	 * @var RevisionContentLookup
	 */
	private $lookup;

	/**
	 * @param RevisionContentLookup $lookup
	 */
	public function __construct( RevisionContentLookup $lookup ) {
		$this->lookup = $lookup;
	}

	/**
	 * @param RevisionSlot $slotRecord
	 *
	 * @todo: provide more details
	 * @return bool
	 */
	protected abstract function canAccess( RevisionSlot $slotRecord );

	/**
	 * @see RevisionContentLookup::getRevisionSlot
	 *
	 * @param Title $title
	 * @param int $revisionId The revision ID (not 0)
	 * @param string $slot The slot name.
	 *
	 * @throws RevisionContentException
	 * @return RevisionSlot
	 */
	function getRevisionSlot( Title $title, $revisionId, $slotName = 'main' ) {
		$slot = $this->lookup->getRevisionSlot( $title, $revisionId, $slotName );

		if ( !$this->canAccess( $slot ) ) {
			throw new SlotAccessDeniedException( $title, $revisionId, $slotName );
		}

		return $slot;
	}

	/**
	 * @see RevisionContentLookup::listPrimarySlots
	 *
	 * @param Title $title
	 * @param int $revisionId
	 *
	 * @throws RevisionContentException
	 * @return string[]
	 */
	function listPrimarySlots( Title $title, $revisionId ) {
		return $this->lookup->listPrimarySlots( $title, $revisionId );
	}

}
