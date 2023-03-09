<?php
/**
 * File deletion utilities.
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
 * @author Rob Church <robchur@gmail.com>
 * @ingroup Media
 */

namespace MediaWiki\Page\File;

use Hooks;
use LocalFile;
use ManualLogEntry;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\DeletePage;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MWException;
use Status;

/**
 * File deletion user interface
 *
 * @ingroup Media
 */
class FileDeleteForm {
	/**
	 * Really delete the file
	 *
	 * @param Title $title
	 * @param LocalFile $file
	 * @param string|null $oldimage Archive name
	 * @param string $reason Reason of the deletion
	 * @param bool $suppress Whether to mark all deleted versions as restricted
	 * @param UserIdentity $user
	 * @param string[] $tags Tags to apply to the deletion action
	 * @param bool $deleteTalk
	 * @return Status The value can be an integer with the log ID of the deletion, or false in case of
	 *   scheduled deletion.
	 * @throws MWException
	 */
	public static function doDelete(
		Title $title,
		LocalFile $file,
		?string $oldimage,
		$reason,
		$suppress,
		UserIdentity $user,
		$tags = [],
		bool $deleteTalk = false
	): Status {
		if ( $oldimage ) {
			$page = null;
			$status = $file->deleteOldFile( $oldimage, $reason, $user, $suppress );
			if ( $status->isOK() ) {
				// Need to do a log item
				$logComment = wfMessage( 'deletedrevision', $oldimage )->inContentLanguage()->text();
				if ( trim( $reason ) !== '' ) {
					$logComment .= wfMessage( 'colon-separator' )
						->inContentLanguage()->text() . $reason;
				}

				$logtype = $suppress ? 'suppress' : 'delete';

				$logEntry = new ManualLogEntry( $logtype, 'delete' );
				$logEntry->setPerformer( $user );
				$logEntry->setTarget( $title );
				$logEntry->setComment( $logComment );
				$logEntry->addTags( $tags );
				$logid = $logEntry->insert();
				$logEntry->publish( $logid );

				$status->value = $logid;
			}
		} else {
			$status = Status::newFatal( 'cannotdelete',
				wfEscapeWikiText( $title->getPrefixedText() )
			);
			$services = MediaWikiServices::getInstance();
			$page = $services->getWikiPageFactory()->newFromTitle( $title );
			'@phan-var \WikiFilePage $page';
			$deleter = $services->getUserFactory()->newFromUserIdentity( $user );
			$deletePage = $services->getDeletePageFactory()->newDeletePage( $page, $deleter );
			if ( $deleteTalk ) {
				$checkStatus = $deletePage->canProbablyDeleteAssociatedTalk();
				if ( !$checkStatus->isGood() ) {
					return Status::wrap( $checkStatus );
				}
				$deletePage->setDeleteAssociatedTalk( true );
			}
			$dbw = wfGetDB( DB_PRIMARY );
			$dbw->startAtomic( __METHOD__, $dbw::ATOMIC_CANCELABLE );
			// delete the associated article first
			$deleteStatus = $deletePage
				->setSuppress( $suppress )
				->setTags( $tags ?: [] )
				->deleteIfAllowed( $reason );

			// DeletePage returns a non-fatal error status if the page
			// or revision is missing, so check for isOK() rather than isGood().
			if ( $deleteStatus->isOK() ) {
				$status = $file->deleteFile( $reason, $user, $suppress );
				if ( $status->isOK() ) {
					if ( $deletePage->deletionsWereScheduled()[DeletePage::PAGE_BASE] ) {
						$status->value = false;
					} else {
						$deletedID = $deletePage->getSuccessfulDeletionsIDs()[DeletePage::PAGE_BASE];
						if ( $deletedID !== null ) {
							$status->value = $deletedID;
						} else {
							// Means that the page/revision didn't exist, so create a log entry here.
							$logtype = $suppress ? 'suppress' : 'delete';
							$logEntry = new ManualLogEntry( $logtype, 'delete' );
							$logEntry->setPerformer( $user );
							$logEntry->setTarget( $title );
							$logEntry->setComment( $reason );
							$logEntry->addTags( $tags );
							$logid = $logEntry->insert();
							$dbw->onTransactionPreCommitOrIdle(
								static function () use ( $logEntry, $logid ) {
									$logEntry->publish( $logid );
								},
								__METHOD__
							);
							$status->value = $logid;
						}
					}
					$dbw->endAtomic( __METHOD__ );
				} else {
					// Page deleted but file still there? rollback page delete
					$dbw->cancelAtomic( __METHOD__ );
				}
			} else {
				$dbw->endAtomic( __METHOD__ );
			}
		}

		if ( $status->isOK() ) {
			$legacyUser = MediaWikiServices::getInstance()
				->getUserFactory()
				->newFromUserIdentity( $user );
			Hooks::runner()->onFileDeleteComplete( $file, $oldimage, $page, $legacyUser, $reason );
		}

		return $status;
	}

	/**
	 * Is the provided `oldimage` value valid?
	 *
	 * @param string $oldimage
	 * @return bool
	 */
	public static function isValidOldSpec( $oldimage ) {
		return strlen( $oldimage ) >= 16
			&& strpos( $oldimage, '/' ) === false
			&& strpos( $oldimage, '\\' ) === false;
	}
}

class_alias( FileDeleteForm::class, 'FileDeleteForm' );
