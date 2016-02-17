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
 * Item class for an oldimage table row
 */
class RevDelFileItem extends RevDelItem {
	/** @var File */
	public $file;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->file = RepoGroup::singleton()->getLocalRepo()->newFileFromRow( $row );
	}

	public function getIdField() {
		return 'oi_archive_name';
	}

	public function getTimestampField() {
		return 'oi_timestamp';
	}

	public function getAuthorIdField() {
		return 'oi_user';
	}

	public function getAuthorNameField() {
		return 'oi_user_text';
	}

	public function getId() {
		$parts = explode( '!', $this->row->oi_archive_name );

		return $parts[0];
	}

	public function canView() {
		return $this->file->userCan( File::DELETED_RESTRICTED, $this->list->getUser() );
	}

	public function canViewContent() {
		return $this->file->userCan( File::DELETED_FILE, $this->list->getUser() );
	}

	public function getBits() {
		return $this->file->getVisibility();
	}

	public function setBits( $bits ) {
		# Queue the file op
		# @todo FIXME: Move to LocalFile.php
		if ( $this->isDeleted() ) {
			if ( $bits & File::DELETED_FILE ) {
				# Still deleted
			} else {
				# Newly undeleted
				$key = $this->file->getStorageKey();
				$srcRel = $this->file->repo->getDeletedHashPath( $key ) . $key;
				$this->list->storeBatch[] = [
					$this->file->repo->getVirtualUrl( 'deleted' ) . '/' . $srcRel,
					'public',
					$this->file->getRel()
				];
				$this->list->cleanupBatch[] = $key;
			}
		} elseif ( $bits & File::DELETED_FILE ) {
			# Newly deleted
			$key = $this->file->getStorageKey();
			$dstRel = $this->file->repo->getDeletedHashPath( $key ) . $key;
			$this->list->deleteBatch[] = [ $this->file->getRel(), $dstRel ];
		}

		# Do the database operations
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'oldimage',
			[ 'oi_deleted' => $bits ],
			[
				'oi_name' => $this->row->oi_name,
				'oi_timestamp' => $this->row->oi_timestamp,
				'oi_deleted' => $this->getBits()
			],
			__METHOD__
		);

		return (bool)$dbw->affectedRows();
	}

	public function isDeleted() {
		return $this->file->isDeleted( File::DELETED_FILE );
	}

	/**
	 * Get the link to the file.
	 * Overridden by RevDelArchivedFileItem.
	 * @return string
	 */
	protected function getLink() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->file->getTimestamp(), $this->list->getUser() ) );

		if ( !$this->isDeleted() ) {
			# Regular files...
			return Html::rawElement( 'a', [ 'href' => $this->file->getUrl() ], $date );
		}

		# Hidden files...
		if ( !$this->canViewContent() ) {
			$link = $date;
		} else {
			$link = Linker::link(
				SpecialPage::getTitleFor( 'Revisiondelete' ),
				$date,
				[],
				[
					'target' => $this->list->title->getPrefixedText(),
					'file' => $this->file->getArchiveName(),
					'token' => $this->list->getUser()->getEditToken(
						$this->file->getArchiveName() )
				]
			);
		}

		return '<span class="history-deleted">' . $link . '</span>';
	}

	/**
	 * Generate a user tool link cluster if the current user is allowed to view it
	 * @return string HTML
	 */
	protected function getUserTools() {
		if ( $this->file->userCan( Revision::DELETED_USER, $this->list->getUser() ) ) {
			$uid = $this->file->getUser( 'id' );
			$name = $this->file->getUser( 'text' );
			$link = Linker::userLink( $uid, $name ) . Linker::userToolLinks( $uid, $name );
		} else {
			$link = $this->list->msg( 'rev-deleted-user' )->escaped();
		}
		if ( $this->file->isDeleted( Revision::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Wrap and format the file's comment block, if the current
	 * user is allowed to view it.
	 *
	 * @return string HTML
	 */
	protected function getComment() {
		if ( $this->file->userCan( File::DELETED_COMMENT, $this->list->getUser() ) ) {
			$block = Linker::commentBlock( $this->file->getDescription() );
		} else {
			$block = ' ' . $this->list->msg( 'rev-deleted-comment' )->escaped();
		}
		if ( $this->file->isDeleted( File::DELETED_COMMENT ) ) {
			return "<span class=\"history-deleted\">$block</span>";
		}

		return $block;
	}

	public function getHTML() {
		$data =
			$this->list->msg( 'widthheight' )->numParams(
				$this->file->getWidth(), $this->file->getHeight() )->text() .
			' (' . $this->list->msg( 'nbytes' )->numParams( $this->file->getSize() )->text() . ')';

		return '<li>' . $this->getLink() . ' ' . $this->getUserTools() . ' ' .
			$data . ' ' . $this->getComment() . '</li>';
	}

	public function getApiData( ApiResult $result ) {
		$file = $this->file;
		$user = $this->list->getUser();
		$ret = [
			'title' => $this->list->title->getPrefixedText(),
			'archivename' => $file->getArchiveName(),
			'timestamp' => wfTimestamp( TS_ISO_8601, $file->getTimestamp() ),
			'width' => $file->getWidth(),
			'height' => $file->getHeight(),
			'size' => $file->getSize(),
		];
		$ret += $file->isDeleted( Revision::DELETED_USER ) ? [ 'userhidden' => '' ] : [];
		$ret += $file->isDeleted( Revision::DELETED_COMMENT ) ? [ 'commenthidden' => '' ] : [];
		$ret += $this->isDeleted() ? [ 'contenthidden' => '' ] : [];
		if ( !$this->isDeleted() ) {
			$ret += [
				'url' => $file->getUrl(),
			];
		} elseif ( $this->canViewContent() ) {
			$ret += [
				'url' => SpecialPage::getTitleFor( 'Revisiondelete' )->getLinkURL(
					[
						'target' => $this->list->title->getPrefixedText(),
						'file' => $file->getArchiveName(),
						'token' => $user->getEditToken( $file->getArchiveName() )
					],
					false, PROTO_RELATIVE
				),
			];
		}
		if ( $file->userCan( Revision::DELETED_USER, $user ) ) {
			$ret += [
				'userid' => $file->user,
				'user' => $file->user_text,
			];
		}
		if ( $file->userCan( Revision::DELETED_COMMENT, $user ) ) {
			$ret += [
				'comment' => $file->description,
			];
		}

		return $ret;
	}
}
