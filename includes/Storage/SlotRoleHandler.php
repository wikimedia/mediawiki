<?php
/**
 * This file is part of MediaWiki.
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
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Storage;

use ParserOutput;

/**
 * FIXME
 *
 * @since 1.32
 */
class SlotRoleHandler {

	private $role;

	/**
	 * @param $role
	 */
	public function __construct( $role ) {
		$this->role = $role;
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

	// XXX: no getContentModel(), that's in SlotRoleRegistry!
	// This way, we only need a single handler for the main slot, not one per content model.

	public function getFieldsForSearchIndex() {
		// FIXME: need to know content model! Or take ContentHandler as param.
	}

	public function getDataForSearchIndex( SlotRecord $slot ) {
		// FIXME: delegate to ContentHandler
	}

	public function getSecondaryDataUpdates( SlotRecord $slot ) {
		// FIXME: delegate to new method in ContentHandler
	}

	public function getOutputHTML( ParserOutput $output ) {
		// FIXME: delegate to ContentHandler
	}

	/**
	 * @return array an associative array of hints
	 */
	public function getOutputPlacementHints() {
	}

}
