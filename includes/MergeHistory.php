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

	/** @const int Maximum number of revisions that can be merged at once */
	const REVISION_LIMIT = 5000;

	/** @var Title Page from which history will be merged */
	protected $source;

	/** @var Title Page to which history will be merged */
	protected $dest;

	/** @var IDatabase Database that we are using */
	protected $dbw;

	/** @var MWTimestamp Maximum timestamp that we can use (oldest timestamp of dest) */
	protected $maxTimestamp;

	/** @var string SQL WHERE condition that selects source revisions to insert into destination */
	protected $timeWhere;

	/** @var MWTimestamp|bool Timestamp upto which history from the source will be merged */
	protected $timestampLimit;

	/** @var integer Number of revisions merged (for Special:MergeHistory success message) */
	protected $revisionsMerged;

	/**
	 * MergeHistory constructor.
	 * @param Title $source Page from which history will be merged
	 * @param Title $dest Page to which history will be merged
	 * @param string|bool $timestamp Timestamp up to which history from the source will be merged
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
			[ 'rev_page' => $this->dest->getArticleID() ],
			__METHOD__
		);
		$this->maxTimestamp = new MWTimestamp( $firstDestTimestamp );

		// Get the timestamp pivot condition
		try {
			if ( $timestamp ) {
				// If we have a requested timestamp, use the
				// latest revision up to that point as the insertion point
				$mwTimestamp = new MWTimestamp( $timestamp );
				$lastWorkingTimestamp = $this->dbw->selectField(
					'revision',
					'MAX(rev_timestamp)',
					[
						'rev_timestamp <= ' .
							$this->dbw->addQuotes( $this->dbw->timestamp( $mwTimestamp ) ),
						'rev_page' => $this->source->getArticleID()
					],
					__METHOD__
				);
				$mwLastWorkingTimestamp = new MWTimestamp( $lastWorkingTimestamp );

				$timeInsert = $mwLastWorkingTimestamp;
				$this->timestampLimit = $mwLastWorkingTimestamp;
			} else {
				// If we don't, merge entire source page history into the
				// beginning of destination page history

				// Get the latest timestamp of the source
				$lastSourceTimestamp = $this->dbw->selectField(
					[ 'page', 'revision' ],
					'rev_timestamp',
					[ 'page_id' => $this->source->getArticleID(),
						'page_latest = rev_id'
					],
					__METHOD__
				);
				$lasttimestamp = new MWTimestamp( $lastSourceTimestamp );

				$timeInsert = $this->maxTimestamp;
				$this->timestampLimit = $lasttimestamp;
			}

			$this->timeWhere = "rev_timestamp <= " .
				$this->dbw->addQuotes( $this->dbw->timestamp( $timeInsert ) );
		} catch ( TimestampException $ex ) {
			// The timestamp we got is screwed up and merge cannot continue
			// This should be detected by $this->isValidMerge()
			$this->timestampLimit = false;
		}
	}

	/**
	 * Get the number of revisions that will be moved
	 * @return int
	 */
	public function getRevisionCount() {
		$count = $this->dbw->selectRowCount( 'revision', '1',
			[ 'rev_page' => $this->source->getArticleID(), $this->timeWhere ],
			__METHOD__,
			[ 'LIMIT' => self::REVISION_LIMIT + 1 ]
		);

		return $count;
	}

	/**
	 * Get the number of revisions that were moved
	 * Used in the SpecialMergeHistory success message
	 * @return int
	 */
	public function getMergedRevisionCount() {
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
				call_user_func_array( [ $status, 'fatal' ], $error );
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
			$status->fatal( 'mergehistory-fail-permission' );
		}

		return $status;
	}

	/**
	 * Does various sanity checks that the merge is
	 * valid. Only things based on the two pages
	 * should be checked here.
	 *
	 * @return Status
	 */
	public function isValidMerge() {
		$status = new Status();

		// If either article ID is 0, then revisions cannot be reliably selected
		if ( $this->source->getArticleID() === 0 ) {
			$status->fatal( 'mergehistory-fail-invalid-source' );
		}
		if ( $this->dest->getArticleID() === 0 ) {
			$status->fatal( 'mergehistory-fail-invalid-dest' );
		}

		// Make sure page aren't the same
		if ( $this->source->equals( $this->dest ) ) {
			$status->fatal( 'mergehistory-fail-self-merge' );
		}

		// Make sure the timestamp is valid
		if ( !$this->timestampLimit ) {
			$status->fatal( 'mergehistory-fail-bad-timestamp' );
		}

		// $this->timestampLimit must be older than $this->maxTimestamp
		if ( $this->timestampLimit > $this->maxTimestamp ) {
			$status->fatal( 'mergehistory-fail-timestamps-overlap' );
		}

		// Check that there are not too many revisions to move
		if ( $this->timestampLimit && $this->getRevisionCount() > self::REVISION_LIMIT ) {
			$status->fatal( 'mergehistory-fail-toobig', Message::numParam( self::REVISION_LIMIT ) );
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
	public function merge( User $user, $reason = '' ) {
		$status = new Status();

		// Check validity and permissions required for merge
		$validCheck = $this->isValidMerge(); // Check this first to check for null pages
		if ( !$validCheck->isOK() ) {
			return $validCheck;
		}
		$permCheck = $this->checkPermissions( $user, $reason );
		if ( !$permCheck->isOK() ) {
			return $permCheck;
		}

		$this->dbw->update(
			'revision',
			[ 'rev_page' => $this->dest->getArticleID() ],
			[ 'rev_page' => $this->source->getArticleID(), $this->timeWhere ],
			__METHOD__
		);

		// Check if this did anything
		$this->revisionsMerged = $this->dbw->affectedRows();
		if ( $this->revisionsMerged < 1 ) {
			$status->fatal( 'mergehistory-fail-no-change' );
			return $status;
		}

		// Make the source page a redirect if no revisions are left
		$haveRevisions = $this->dbw->selectField(
			'revision',
			'rev_timestamp',
			[ 'rev_page' => $this->source->getArticleID() ],
			__METHOD__,
			[ 'FOR UPDATE' ]
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
			$redirectContent = $contentHandler->makeRedirectContent(
				$this->dest,
				wfMessage( 'mergehistory-redirect-text' )->inContentLanguage()->plain()
			);

			if ( $redirectContent ) {
				$redirectPage = WikiPage::factory( $this->source );
				$redirectRevision = new Revision( [
					'title' => $this->source,
					'page' => $this->source->getArticleID(),
					'comment' => $reason,
					'content' => $redirectContent ] );
				$redirectRevision->insertOn( $this->dbw );
				$redirectPage->updateRevisionOn( $this->dbw, $redirectRevision );

				// Now, we record the link from the redirect to the new title.
				// It should have no other outgoing links...
				$this->dbw->delete(
					'pagelinks',
					[ 'pl_from' => $this->dest->getArticleID() ],
					__METHOD__
				);
				$this->dbw->insert( 'pagelinks',
					[
						'pl_from' => $this->dest->getArticleID(),
						'pl_from_namespace' => $this->dest->getNamespace(),
						'pl_namespace' => $this->dest->getNamespace(),
						'pl_title' => $this->dest->getDBkey() ],
					__METHOD__
				);
			} else {
				// Warning if we couldn't create the redirect
				$status->warning( 'mergehistory-warning-redirect-not-created' );
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
		$logEntry->setParameters( [
			'4::dest' => $this->dest->getPrefixedText(),
			'5::mergepoint' => $this->timestampLimit->getTimestamp( TS_MW )
		] );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		Hooks::run( 'ArticleMergeComplete', [ $this->source, $this->dest ] );

		return $status;
	}
}
