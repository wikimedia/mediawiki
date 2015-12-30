<?php

/**
 *
 *
 * Created on Dec 29, 2015
 *
 * Copyright © 2015 Geoffrey Mon <geofbot@gmail.com>
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

	/** @const int Maximum number of revisions that can be merged at once (avoid too much slave lag) */
	const REVISION_LIMIT = 5000;

	/** @var Title Page from which history will be merged */
	protected $source;

	/** @var Title Page to which history will be merged */
	protected $dest;

	/** @var MWTimestamp Requested timestamp up to which history from the source will be merged */
	protected $timestamp;

	/** @var DatabaseBase Database that we are using */
	protected $dbw;

	/** @var MWTimestamp Maximum timestamp that we can use for MergeHistory (oldest timestamp of destination page) */
	protected $maxtimestamp;

	/** @var MWTImestamp Latest timestamp of the source page */
	protected $lasttimestamp;

	/** @var string String containing the timestamp where source page history will be inserted into destination */
	public $timewhere;

	/** @var MWTimestamp|boolean Timestamp upto which history from the source will be merged */
	protected $timestampLimit;

	/** @var integer Number of revisions merged (for Special:MergeHistory success message) */
	protected $revisionsMerged;

	/**
	 * MergeHistory constructor.
	 * @param Title $source Page from which history will be merged
	 * @param Title $dest Page to which history will be merged
	 * @param string|boolean $timestamp Timestamp up to which history from the source will be merged
	 */
	public function __construct( Title $source, Title $dest, $timestamp = false ) {
		// Save the parameters
		$this->source = $source;
		$this->dest = $dest;

		// Get the database
		$this->dbw = wfGetDB( DB_MASTER );

		// Max timestamp should be min of destination page
		$firstDestTimestamp = $this->dbw->selectField(
			'revision',
			'MIN(rev_timestamp)',
			array( 'rev_page' => $this->dest->getArticleID() ),
			__METHOD__
		);
		$this->maxtimestamp = new MWTimestamp( $firstDestTimestamp );

		// Get the latest timestamp of the source
		$lastSourceTimestamp = $this->dbw->selectField(
			array( 'page', 'revision' ),
			'rev_timestamp',
			array( 'page_id' => $this->source->getArticleID(), 'page_latest = rev_id' ),
			__METHOD__
		);
		$this->lasttimestamp = new MWTimestamp( $lastSourceTimestamp );

		// Get the timestamp pivot condition
		try {
			if ( $timestamp ) {
				// If we have a requested timestamp, use it as the insertion point
				$this->timestamp = new MWTimestamp( $timestamp );
				$timeInsert = $this->timestamp;
				$this->timestampLimit = $this->timestamp;
			} else {
				// If we don't, merge entire source page history into the beginning of destination page history
				$timeInsert = $this->maxtimestamp;
				$this->timestampLimit = $this->lasttimestamp;
			}

			$this->timewhere = "rev_timestamp <= {$this->dbw->timestamp( $timeInsert )}";
		} catch ( TimestampException $ex ) {
			// The timestamp we got is screwed up and merge cannot continue
			$this->timestampLimit = false;
		}
	}

	/**
	 * Get the number of revisions that will be moved
	 * @return int
	 */
	public function getRevisionCount() {
		$count = $this->dbw->selectRowCount( 'revision', '1',
			array( 'rev_page' => $this->source->getArticleID(), $this->timewhere ),
			__METHOD__,
			array( 'LIMIT' => self::REVISION_LIMIT + 1 )
		);

		return $count;
	}

	/**
	 * Get the number of revisions that were moved
	 * Used in the SpecialMergeHistory success message
	 * @return int
	 */
	public function getMergedRevisionCount() {
		if ( empty( $this->revisionsMerged ) ) {
			$this->revisionsMerged = $this->dbw->affectedRows();
		}
		return $this->revisionsMerged;
	}

	/**
	 * Check if the merge is possible
	 * @param User $user
	 * @param string $reason
	 * @return Status
	 */
	public function checkPermissions( User $user, $reason ) {
		$status = new Status();

		// Check if user can edit both pages
		$errors = wfMergeErrorArrays(
			$this->source->getUserPermissionsErrors( 'edit', $user ),
			$this->dest->getUserPermissionsErrors( 'edit', $user )
		);

		// Convert into a Status object
		if ( $errors ) {
			foreach ( $errors as $error ) {
				call_user_func_array( array( $status, 'fatal' ), $error );
			}
		}

		// Anti-spam
		if ( EditPage::matchSummarySpamRegex( $reason ) !== false ) {
			// This is kind of lame, won't display nice
			$status->fatal( 'spamprotectiontext' );
		}

		// Check mergehistory permission
		if ( !$user->isAllowed( 'mergehistory' ) ) {
			// User doesn't have the right to merge histories
			$status->fatal( 'mergehistory-fail-noright' );
		}

		return $status;
	}

	/**
	 * Does various sanity checks that the merge is
	 * valid. Only things based on the two titles
	 * should be checked here.
	 *
	 * @return Status
	 */
	public function isValidMerge() {
		$status = new Status();

		// Make sure pages exist
		if ( is_null( $this->source ) || is_null( $this->dest ) ) {
			$status->fatal( 'mergehistory-fail-noexist' );
			return $status;
		}
		// Make sure page aren't the same
		if ( $this->source->getArticleID() == $this->dest->getArticleID() ) {
			$status->fatal( 'mergehistory-fail-same' );
		}

		# Destination page must exist with revisions
		if ( !$this->maxtimestamp ) {
			$status->fatal( 'mergehistory-fail-noexist' );
		}

		// Make sure the timestamp is valid
		if ( !$this->timestampLimit ) {
			$status->fatal( 'mergehistory-fail-badtimestamp' );
		}

		# $this->timestamp must be older than $maxtimestamp
		if ( $this->timestamp >= $this->maxtimestamp ) {
			$status->fatal( 'mergehistory-fail-notold' );
		}


		# Check that there are not too many revisions to move
		if ( $this->getRevisionCount() > self::REVISION_LIMIT ) {
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
	 * Maybe this should delete redirects at the source page of merges?
	 *
	 * @param User $user
	 * @param string $reason
	 * @return Status status of the history merge
	 */
	function merge( User $user, $reason = '' ) {
		$status = new Status();

		// Be paranoid, check things just in case
		$permCheck = $this->checkPermissions( $user, $reason );
		$validCheck = $this->isValidMerge();
		if( !$permCheck->isOK() ) {
			return $permCheck;
		} else if ( !$validCheck->isOK() ) {
			return $validCheck;
		}

		$this->dbw->update(
			'revision',
			array( 'rev_page' => $this->dest->getArticleID() ),
			array( 'rev_page' => $this->source->getArticleID(), $this->timewhere ),
			__METHOD__
		);

		// Check if this did anything
		if ( $this->getMergedRevisionCount() < 1 ) {
			$status->fatal( 'mergehistory-fail-nochange' );
			return $status;
		}

		// Make the source page a redirect if no revisions are left
		$haveRevisions = $this->dbw->selectField(
			'revision',
			'rev_timestamp',
			array( 'rev_page' => $this->source->getArticleID() ),
			__METHOD__,
			array( 'FOR UPDATE' )
		);
		if ( !$haveRevisions ) {
			if ( $reason ) {
				$reason = wfMessage(
					'mergehistory-comment',
					$this->source->getPrefixedText(),
					$this->dest->getPrefixedText(),
					$reason
				)->inContentLanguage()->text();
			} else {
				$reason = wfMessage(
					'mergehistory-autocomment',
					$this->source->getPrefixedText(),
					$this->dest->getPrefixedText()
				)->inContentLanguage()->text();
			}

			$contentHandler = ContentHandler::getForTitle( $this->source );
			$redirectContent = $contentHandler->makeRedirectContent( $this->dest );

			if ( $redirectContent ) {
				$redirectPage = WikiPage::factory( $this->source );
				$redirectRevision = new Revision( array(
					'title' => $this->source,
					'page' => $this->source->getArticleID(),
					'comment' => $reason,
					'content' => $redirectContent ) );
				$redirectRevision->insertOn( $this->dbw );
				$redirectPage->updateRevisionOn( $this->dbw, $redirectRevision );

				// Now, we record the link from the redirect to the new title.
				// It should have no other outgoing links...
				$this->dbw->delete( 'pagelinks', array( 'pl_from' => $this->dest->getArticleID() ), __METHOD__ );
				$this->dbw->insert( 'pagelinks',
					array(
						'pl_from' => $this->dest->getArticleID(),
						'pl_from_namespace' => $this->dest->getNamespace(),
						'pl_namespace' => $this->dest->getNamespace(),
						'pl_title' => $this->dest->getDBkey() ),
					__METHOD__
				);
			} else {
				// would be nice to show a warning if we couldn't create a redirect
			}
		} else {
			$this->source->invalidateCache(); // update histories
		}
		$this->dest->invalidateCache(); // update histories

		// Update our logs
		$logEntry = new ManualLogEntry( 'merge', 'merge' );
		$logEntry->setPerformer( $user );
		$logEntry->setComment( $reason );
		$logEntry->setTarget( $this->source );
		$logEntry->setParameters( array(
			'4::dest' => $this->dest->getPrefixedText(),
			'5::mergepoint' => wfTimestamp( TS_MW, $this->timestampLimit )
		) );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		Hooks::run( 'ArticleMergeComplete', array( $this->source, $this->dest ) );

		return $status;
	}
}
