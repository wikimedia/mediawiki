<?php

namespace MediaWiki\Storage;

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
 * RevisionNotFoundException is raised when trying to access a revision that was not found
 * in the storage backend.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class RevisionNotFoundException extends RevisionContentException {

	/**
	 * @param Title $title
	 * @param int $revisionId
	 * @param string $slot
	 */
	public function __construct( Title $title, $revisionId, $slot ) {
		parent::__construct( "Revision not found", $title, $revisionId, $slot );
	}

}
