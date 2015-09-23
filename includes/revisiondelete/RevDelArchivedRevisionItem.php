<?php
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
 * @file
 * @ingroup RevisionDelete
 */

/**
 * Item class for a archive table row by ar_rev_id -- actually
 * used via RevDelRevisionList.
 */
class RevDelArchivedRevisionItem extends RevDelArchiveItem {
	public function __construct( $list, $row ) {
		RevDelItem::__construct( $list, $row );

		$this->revision = Revision::newFromArchiveRow( $row,
			array( 'page' => $this->list->title->getArticleID() ) );
	}

	public function getIdField() {
		return 'ar_rev_id';
	}

	public function getId() {
		return $this->revision->getId();
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'archive',
			array( 'ar_deleted' => $bits ),
			array( 'ar_rev_id' => $this->row->ar_rev_id,
				'ar_deleted' => $this->getBits()
			),
			__METHOD__ );

		return (bool)$dbw->affectedRows();
	}
}
