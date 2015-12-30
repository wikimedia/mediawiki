<?php

/**
 *
 *
 * Created on Dec 29, 2015
 *
 * Copyright © 2015 Geoffrey Mon "geofbot@gmail.com"
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

/**
 * Handles the backend logic of merging the histories of two
 * pages.
 *
 * @since 1.27
 */
class MergeHistory {

	/** @var Title Page from which history will be merged */
	protected $mTarget;

	/** @var Title Page to which history will be merged */
	protected $mDest;

	/** @var string Timestamp up to which history will be merged */
	protected $mTimestamp;

	/**
	 * MergeHistory constructor.
	 * @param Title $mTarget
	 * @param Title $mDest 
	 */
	public function __construct( Title $mTarget, Title $mDest, $mTimestamp ) {
		$this->mTarget = $mTarget;
		$this->mDest = $mDest;
		$this->mTimestamp = $mTimestamp;
	}

	public function checkPermissions( User $user, $reason ) {
		return new Status();
		/*$status = new Status();

		$errors = wfMergeErrorArrays(
			$this->mTarget->getUserPermissionsErrors( 'move', $user ),
			$this->mTarget->getUserPermissionsErrors( 'edit', $user ),
			$this->mDest->getUserPermissionsErrors( 'move-target', $user ),
			$this->mDest->getUserPermissionsErrors( 'edit', $user )
		);

		// Convert into a Status object
		if ( $errors ) {
			foreach ( $errors as $error ) {
				call_user_func_array( array( $status, 'fatal' ), $error );
			}
		}

		if ( EditPage::matchSummarySpamRegex( $reason ) !== false ) {
			// This is kind of lame, won't display nice
			$status->fatal( 'spamprotectiontext' );
		}

		$tp = $this->newTitle->getTitleProtection();
		if ( $tp !== false && !$user->isAllowed( $tp['permission'] ) ) {
				$status->fatal( 'cantmove-titleprotected' );
		}

		return $status;*/
	}

	/**
	 * Does various sanity checks that the move is
	 * valid. Only things based on the two titles
	 * should be checked here.
	 *
	 * @return Status
	 */
	public function isValidMerge() {
		global $wgContentHandlerUseDB;
		$status = new Status();

		// Make sure pages exist
		if ( is_null( $this->mTarget ) || is_null( $this->mDest ) ) {
			$status->fatal( 'test' );
		}
		// Make sure page aren't the same
		if ( $this->mTarget->getArticleID() == $this->mDest->getArticleID() ) {
			$status->fatal( 'test' );
		}
		# Verify that this timestamp is valid
		# Must be older than the destination page
		$dbw = wfGetDB( DB_MASTER );
		# Get timestamp into DB format
		$this->mTimestamp = $this->mTimestamp ? $dbw->timestamp( $this->mTimestamp ) : '';
		# Max timestamp should be min of destination page
		$maxtimestamp = $dbw->selectField(
			'revision',
			'MIN(rev_timestamp)',
			array( 'rev_page' => $this->mDest->getArticleID() ),
			__METHOD__
		);
		# Destination page must exist with revisions
		if ( !$maxtimestamp ) {
			$status->fatal( 'mergehistory-fail' );
		}
		# Get the latest timestamp of the source
		$lasttimestamp = $dbw->selectField(
			array( 'page', 'revision' ),
			'rev_timestamp',
			array( 'page_id' => $this->mTarget->getArticleID(), 'page_latest = rev_id' ),
			__METHOD__
		);
		# $this->mTimestamp must be older than $maxtimestamp
		if ( $this->mTimestamp >= $maxtimestamp ) {
			$status->fatal( 'mergehistory-fail' );
		}
		# Get the timestamp pivot condition
		if ( $this->mTimestamp ) {
			$this->timewhere = "rev_timestamp <= {$this->mTimestamp}";
			$this->timestampLimit = wfTimestamp( TS_MW, $this->mTimestamp );
		} else {
			$this->timewhere = "rev_timestamp <= {$maxtimestamp}";
			$this->timestampLimit = wfTimestamp( TS_MW, $lasttimestamp );
		}
		# Check that there are not too many revisions to move
		$limit = 5000; // avoid too much slave lag
		$count = $dbw->selectRowCount( 'revision', '1',
			array( 'rev_page' => $this->mTarget->getArticleID(), $this->timewhere ),
			__METHOD__,
			array( 'LIMIT' => $limit + 1 )
		);
		if ( $count > $limit ) {
			$status->fatal( 'mergehistory-fail-toobig' );
		}

		return $status;
	}

	/**
	 * Actually attempt the history move
	 *
	 * @todo if all versions of page A are moved to B and then a user
	 * tries to do a reverse-merge via the "unmerge" log link, then page
	 * A will still be a redirect (as it was after the original merge),
	 * though it will have the old revisions back from before (as expected).
	 * The user may have to "undo" the redirect manually to finish the "unmerge".
	 * Maybe this should delete redirects at the target page of merges?
	 *
	 * @param User $user
	 * @param string $reason
	 * @return bool Success
	 */
	function merge( User $user, $reason = '' ) {
		$status = new Status(); //TODO


		$dbw = wfGetDB( DB_MASTER );
		# Do the moving...
		$dbw->update(
			'revision',
			array( 'rev_page' => $this->mDest->getArticleID() ),
			array( 'rev_page' => $this->mTarget->getArticleID(), $this->timewhere ),
			__METHOD__
		);

		$count = $dbw->affectedRows();
		# Make the source page a redirect if no revisions are left
		$haveRevisions = $dbw->selectField(
			'revision',
			'rev_timestamp',
			array( 'rev_page' => $this->mTarget->getArticleID() ),
			__METHOD__,
			array( 'FOR UPDATE' )
		);
		if ( !$haveRevisions ) {
			if ( $reason ) {
				$reason = wfMessage(
					'mergehistory-comment',
					$this->mTarget->getPrefixedText(),
					$this->mDest->getPrefixedText(),
					$reason
				)->inContentLanguage()->text();
			} else {
				$reason = wfMessage(
					'mergehistory-autocomment',
					$this->mTarget->getPrefixedText(),
					$this->mDest->getPrefixedText()
				)->inContentLanguage()->text();
			}

			$contentHandler = ContentHandler::getForTitle( $this->mTarget );
			$redirectContent = $contentHandler->makeRedirectContent( $this->mDest );

			if ( $redirectContent ) {
				$redirectPage = WikiPage::factory( $this->mTarget );
				$redirectRevision = new Revision( array(
					'title' => $this->mTarget,
					'page' => $this->mTarget->getArticleID(),
					'comment' => $reason,
					'content' => $redirectContent ) );
				$redirectRevision->insertOn( $dbw );
				$redirectPage->updateRevisionOn( $dbw, $redirectRevision );

				# Now, we record the link from the redirect to the new title.
				# It should have no other outgoing links...
				$dbw->delete( 'pagelinks', array( 'pl_from' => $this->mDest->getArticleID() ), __METHOD__ );
				$dbw->insert( 'pagelinks',
					array(
						'pl_from' => $this->mDest->getArticleID(),
						'pl_from_namespace' => $this->mDest->getNamespace(),
						'pl_namespace' => $this->mDest->getNamespace(),
						'pl_title' => $this->mDest->getDBkey() ),
					__METHOD__
				);
			} else {
				// would be nice to show a warning if we couldn't create a redirect
			}
		} else {
			$this->mTarget->invalidateCache(); // update histories
		}
		$this->mDest->invalidateCache(); // update histories
		# Check if this did anything
		if ( !$count ) {
			//$this->getOutput()->addWikiMsg( 'mergehistory-fail' );
	// TODO
			return false;
		}
		# Update our logs
		$logEntry = new ManualLogEntry( 'merge', 'merge' );
		$logEntry->setPerformer( $user );
		$logEntry->setComment( $reason );
		$logEntry->setTarget( $this->mTarget );
		$logEntry->setParameters( array(
			'4::dest' => $this->mDest->getPrefixedText(),
			'5::mergepoint' => $this->timestampLimit
		) );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		$targetLink = Linker::link(
			$this->mTarget,
			$this->mTarget->getPrefixedText(),
			array(),
			array( 'redirect' => 'no' )
		);

		Hooks::run( 'ArticleMergeComplete', array( $this->mTarget, $this->mDest ) );

		return $status;
	}
}
