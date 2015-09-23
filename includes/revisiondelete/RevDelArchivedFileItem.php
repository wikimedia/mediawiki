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
 * Item class for a filearchive table row
 */
class RevDelArchivedFileItem extends RevDelFileItem {
	public function __construct( $list, $row ) {
		RevDelItem::__construct( $list, $row );
		$this->file = ArchivedFile::newFromRow( $row );
	}

	public function getIdField() {
		return 'fa_id';
	}

	public function getTimestampField() {
		return 'fa_timestamp';
	}

	public function getAuthorIdField() {
		return 'fa_user';
	}

	public function getAuthorNameField() {
		return 'fa_user_text';
	}

	public function getId() {
		return $this->row->fa_id;
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'filearchive',
			array( 'fa_deleted' => $bits ),
			array(
				'fa_id' => $this->row->fa_id,
				'fa_deleted' => $this->getBits(),
			),
			__METHOD__
		);

		return (bool)$dbw->affectedRows();
	}

	protected function getLink() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->file->getTimestamp(), $this->list->getUser() ) );

		# Hidden files...
		if ( !$this->canViewContent() ) {
			$link = $date;
		} else {
			$undelete = SpecialPage::getTitleFor( 'Undelete' );
			$key = $this->file->getKey();
			$link = Linker::link( $undelete, $date, array(),
				array(
					'target' => $this->list->title->getPrefixedText(),
					'file' => $key,
					'token' => $this->list->getUser()->getEditToken( $key )
				)
			);
		}
		if ( $this->isDeleted() ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	public function getApiData( ApiResult $result ) {
		$file = $this->file;
		$user = $this->list->getUser();
		$ret = array(
			'title' => $this->list->title->getPrefixedText(),
			'timestamp' => wfTimestamp( TS_ISO_8601, $file->getTimestamp() ),
			'width' => $file->getWidth(),
			'height' => $file->getHeight(),
			'size' => $file->getSize(),
		);
		$ret += $file->isDeleted( Revision::DELETED_USER ) ? array( 'userhidden' => '' ) : array();
		$ret += $file->isDeleted( Revision::DELETED_COMMENT ) ? array( 'commenthidden' => '' ) : array();
		$ret += $this->isDeleted() ? array( 'contenthidden' => '' ) : array();
		if ( $this->canViewContent() ) {
			$ret += array(
				'url' => SpecialPage::getTitleFor( 'Revisiondelete' )->getLinkURL(
					array(
						'target' => $this->list->title->getPrefixedText(),
						'file' => $file->getKey(),
						'token' => $user->getEditToken( $file->getKey() )
					),
					false, PROTO_RELATIVE
				),
			);
		}
		if ( $file->userCan( Revision::DELETED_USER, $user ) ) {
			$ret += array(
				'userid' => $file->getUser( 'id' ),
				'user' => $file->getUser( 'text' ),
			);
		}
		if ( $file->userCan( Revision::DELETED_COMMENT, $user ) ) {
			$ret += array(
				'comment' => $file->getRawDescription(),
			);
		}

		return $ret;
	}
}
