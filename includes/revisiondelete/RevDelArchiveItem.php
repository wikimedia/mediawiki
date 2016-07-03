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
 * Item class for a archive table row
 */
class RevDelArchiveItem extends RevDelRevisionItem {
	public function __construct( $list, $row ) {
		RevDelItem::__construct( $list, $row );
		$this->revision = Revision::newFromArchiveRow( $row,
			[ 'page' => $this->list->title->getArticleID() ] );
	}

	public function getIdField() {
		return 'ar_timestamp';
	}

	public function getTimestampField() {
		return 'ar_timestamp';
	}

	public function getAuthorIdField() {
		return 'ar_user';
	}

	public function getAuthorNameField() {
		return 'ar_user_text';
	}

	public function getId() {
		# Convert DB timestamp to MW timestamp
		return $this->revision->getTimestamp();
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'archive',
			[ 'ar_deleted' => $bits ],
			[
				'ar_namespace' => $this->list->title->getNamespace(),
				'ar_title' => $this->list->title->getDBkey(),
				// use timestamp for index
				'ar_timestamp' => $this->row->ar_timestamp,
				'ar_rev_id' => $this->row->ar_rev_id,
				'ar_deleted' => $this->getBits()
			],
			__METHOD__ );

		return (bool)$dbw->affectedRows();
	}

	protected function getRevisionLink() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->revision->getTimestamp(), $this->list->getUser() ) );

		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}

		return Linker::link(
			SpecialPage::getTitleFor( 'Undelete' ),
			$date,
			[],
			[
				'target' => $this->list->title->getPrefixedText(),
				'timestamp' => $this->revision->getTimestamp()
			]
		);
	}

	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $this->list->msg( 'diff' )->escaped();
		}

		return Linker::link(
			SpecialPage::getTitleFor( 'Undelete' ),
			$this->list->msg( 'diff' )->escaped(),
			[],
			[
				'target' => $this->list->title->getPrefixedText(),
				'diff' => 'prev',
				'timestamp' => $this->revision->getTimestamp()
			]
		);
	}
}
