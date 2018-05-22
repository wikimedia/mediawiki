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

use MediaWiki\Linker\LinkTarget;

/**
 * FIXME
 *
 * @since 1.32
 */
class SlotRoleRegistry {

	public function getRoleHandler( SlotRecord $slotRecord ) {
		// FIXME
	}

	public function getContentModel( $role, LinkTarget $title ) {
		// FIXME
	}

	public function getAllowedRoles( LinkTarget $title ) {
		// XXX per page type
		// FIXME
	}

	public function getRequiredRoles( LinkTarget $title ) {
		// XXX per page type
		// FIXME
	}

	public function getDefinedRoles() {
		// FIXME
	}

	public function getKnownRoles() {
		// FIXME
	}

}
