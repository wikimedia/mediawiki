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

use MediaWiki\Api\ApiResult;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\OldLocalFile;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\MediaWikiServices;
use MediaWiki\RevisionList\RevisionListBase;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Item class for an oldimage table row
 */
class RevDelFileItem extends RevDelItem {
	/** @var RevDelFileList */
	protected $list;
	/** @var OldLocalFile */
	protected $file;
	protected IConnectionProvider $dbProvider;

	/** @inheritDoc */
	public function __construct( RevisionListBase $list, $row ) {
		parent::__construct( $list, $row );
		$this->file = static::initFile( $list, $row );
		$this->dbProvider = MediaWikiServices::getInstance()->getConnectionProvider();
	}

	/**
	 * Create file object from $row sourced from $list
	 *
	 * @param RevisionListBase $list
	 * @param mixed $row
	 * @return mixed
	 */
	protected static function initFile( $list, $row ) {
		return MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
			->newFileFromRow( $row );
	}

	/** @inheritDoc */
	public function getIdField() {
		return 'oi_archive_name';
	}

	/** @inheritDoc */
	public function getTimestampField() {
		return 'oi_timestamp';
	}

	/** @inheritDoc */
	public function getAuthorIdField() {
		return 'oi_user';
	}

	/** @inheritDoc */
	public function getAuthorNameField() {
		return 'oi_user_text';
	}

	/** @inheritDoc */
	public function getAuthorActorField() {
		return 'oi_actor';
	}

	/** @inheritDoc */
	public function getId() {
		$parts = explode( '!', $this->row->oi_archive_name );

		return $parts[0];
	}

	/** @inheritDoc */
	public function canView() {
		return $this->file->userCan( File::DELETED_RESTRICTED, $this->list->getAuthority() );
	}

	/** @inheritDoc */
	public function canViewContent() {
		return $this->file->userCan( File::DELETED_FILE, $this->list->getAuthority() );
	}

	/** @inheritDoc */
	public function getBits() {
		return $this->file->getVisibility();
	}

	/** @inheritDoc */
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
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'oldimage' )
			->set( [ 'oi_deleted' => $bits ] )
			->where( [
				'oi_name' => $this->row->oi_name,
				'oi_timestamp' => $this->row->oi_timestamp,
				'oi_deleted' => $this->getBits()
			] )
			->caller( __METHOD__ )->execute();

		return (bool)$dbw->affectedRows();
	}

	/**
	 * @return bool
	 */
	public function isDeleted() {
		return $this->file->isDeleted( File::DELETED_FILE );
	}

	/**
	 * Get the link to the file.
	 * Overridden by RevDelArchivedFileItem.
	 * @return string
	 */
	protected function getLink() {
		$date = $this->list->getLanguage()->userTimeAndDate(
			$this->file->getTimestamp(), $this->list->getUser() );

		if ( !$this->isDeleted() ) {
			# Regular files...
			return Html::element( 'a', [ 'href' => $this->file->getUrl() ], $date );
		}

		# Hidden files...
		if ( !$this->canViewContent() ) {
			$link = htmlspecialchars( $date );
		} else {
			$link = $this->getLinkRenderer()->makeLink(
				SpecialPage::getTitleFor( 'Revisiondelete' ),
				$date,
				[],
				[
					'target' => $this->list->getPageName(),
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
		$uploader = $this->file->getUploader( File::FOR_THIS_USER, $this->list->getAuthority() );
		if ( $uploader ) {
			$link = Linker::userLink( $uploader->getId(), $uploader->getName() ) .
				Linker::userToolLinks( $uploader->getId(), $uploader->getName() );
			return $link;
		} else {
			$link = $this->list->msg( 'rev-deleted-user' )->escaped();
		}
		if ( $this->file->isDeleted( File::DELETED_USER ) ) {
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
		if ( $this->file->userCan( File::DELETED_COMMENT, $this->list->getAuthority() ) ) {
			$block = MediaWikiServices::getInstance()->getCommentFormatter()
				->formatBlock( $this->file->getDescription() );
		} else {
			$block = ' ' . $this->list->msg( 'rev-deleted-comment' )->escaped();
		}
		if ( $this->file->isDeleted( File::DELETED_COMMENT ) ) {
			return "<span class=\"history-deleted\">$block</span>";
		}

		return $block;
	}

	/** @inheritDoc */
	public function getHTML() {
		$data =
			$this->list->msg( 'widthheight' )->numParams(
				$this->file->getWidth(),
				$this->file->getHeight() )->escaped() .
			' (' . $this->list->msg( 'nbytes' )->numParams(
				$this->file->getSize() )->escaped() . ')';

		return '<li>' . $this->getLink() . ' ' . $this->getUserTools() . ' ' .
			$data . ' ' . $this->getComment() . '</li>';
	}

	/** @inheritDoc */
	public function getApiData( ApiResult $result ) {
		$file = $this->file;
		$user = $this->list->getUser();
		$ret = [
			'title' => $this->list->getPageName(),
			'archivename' => $file->getArchiveName(),
			'timestamp' => wfTimestamp( TS_ISO_8601, $file->getTimestamp() ),
			'width' => $file->getWidth(),
			'height' => $file->getHeight(),
			'size' => $file->getSize(),
			'userhidden' => (bool)$file->isDeleted( File::DELETED_USER ),
			'commenthidden' => (bool)$file->isDeleted( File::DELETED_COMMENT ),
			'contenthidden' => (bool)$this->isDeleted(),
		];
		if ( !$this->isDeleted() ) {
			$ret += [
				'url' => $file->getUrl(),
			];
		} elseif ( $this->canViewContent() ) {
			$ret += [
				'url' => SpecialPage::getTitleFor( 'Revisiondelete' )->getLinkURL(
					[
						'target' => $this->list->getPageName(),
						'file' => $file->getArchiveName(),
						'token' => $user->getEditToken( $file->getArchiveName() )
					]
				),
			];
		}
		$uploader = $file->getUploader( File::FOR_THIS_USER, $user );
		if ( $uploader ) {
			$ret += [
				'userid' => $uploader->getId(),
				'user' => $uploader->getName(),
			];
		}
		$comment = $file->getDescription( File::FOR_THIS_USER, $user );
		if ( ( $comment ?? '' ) !== '' ) {
			$ret += [
				'comment' => $comment,
			];
		}

		return $ret;
	}

	/** @inheritDoc */
	public function lock() {
		return $this->file->acquireFileLock();
	}

	/** @inheritDoc */
	public function unlock() {
		return $this->file->releaseFileLock();
	}
}
