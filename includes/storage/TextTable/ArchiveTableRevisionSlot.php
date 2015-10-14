<?php

namespace MediaWiki\Storage\TextTable;

use Content;
use ContentHandler;
use ExternalStore;
use IDBAccessObject;
use MediaWiki\Storage\RevisionContentException;
use MediaWiki\Storage\RevisionSlot;
use Revision;
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
 * A RevisionSlot implementation based on the `archive` tables.
 */
class ArchiveTableRevisionSlot extends TextTableRevisionSlot {

	/**
	 * @param Title $title
	 * @param object|array $archiveRow
	 * @param int $queryFlags
	 */
	public function __construct( Title $title, $archiveRow, $queryFlags = 0 ) {
		parent::__construct( $title, $archiveRow, $archiveRow, $queryFlags );

		$this->revisionFieldPrefix = 'ar_';
		$this->textFieldPrefix = 'ar_';
	}

}
