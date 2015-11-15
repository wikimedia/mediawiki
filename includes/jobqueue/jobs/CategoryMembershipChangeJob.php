<?php
/**
 * Updater for link tracking tables after a page edit.
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
 * Job to add recent change entries mentioning category membership changes
 *
 * Parameters include:
 *   - pageId : page ID
 *   - revTimestamp : timestamp of the triggering revision
 *
 * Category changes will be mentioned for revisions at/after the timestamp for this page
 *
 * @since 1.27
 */
class CategoryMembershipChangeJob extends Job {
	const ENQUEUE_FUDGE_SEC = 60;

	function __construct( Title $title, array $params ) {
		parent::__construct( 'categoryMembershipChange', $title, $params );
		// Only need one job per page. Note that ENQUEUE_FUDGE_SEC handles races where a
		// newer revision job gets inserted while the older revision job is de-duplicated.
		$this->removeDuplicates = true;
	}

	public function run() {
		$page = WikiPage::newFromID( $this->params['pageId'], WikiPage::READ_LATEST );
		if ( !$page ) {
			$this->setLastError( "Could not find page #{$this->params['pageId']}" );
			return false; // deleted?
		}

		$dbw = wfGetDB( DB_MASTER );

		// Use a named lock so that jobs for this page see each others' changes
		$fname = __METHOD__;
		$lockKey = "CategoryMembershipUpdates:{$page->getId()}";
		if ( !$dbw->lock( $lockKey, $fname, 10 ) ) {
			$this->setLastError( "Could not acquire lock '$lockKey'" );
			return false;
		}

		$unlocker = new ScopedCallback( function () use ( $dbw, $lockKey, $fname ) {
			$dbw->unlock( $lockKey, $fname );
		} );

		// Sanity: clear any DB transaction snapshot
		$dbw->commit( __METHOD__, 'flush' );

		$cutoffUnix = wfTimestamp( TS_UNIX, $this->params['revTimestamp'] );
		// Using ENQUEUE_FUDGE_SEC handles jobs inserted out of revision order due to the delay
		// between COMMIT and actual enqueueing of the CategoryMembershipChangeJob job.
		$cutoffUnix -= self::ENQUEUE_FUDGE_SEC;

		// Get the newest revision that has an SRC_CATEGORIZE row...
		$row = $dbw->selectRow(
			array( 'revision', 'recentchanges' ),
			array( 'rev_timestamp', 'rev_id' ),
			array(
				'rev_page' => $page->getId(),
				'rev_timestamp >= ' . $dbw->addQuotes( $dbw->timestamp( $cutoffUnix ) )
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_timestamp DESC, rev_id DESC' ),
			array(
				'recentchanges' => array(
					'INNER JOIN',
					array(
						'rc_this_oldid = rev_id',
						'rc_source' => RecentChange::SRC_CATEGORIZE,
						// Allow rc_cur_id or rc_timestamp index usage
						'rc_cur_id = rev_page',
						'rc_timestamp >= rev_timestamp'
					)
				)
			)
		);
		// Only consider revisions newer than any such revision
		if ( $row ) {
			$cutoffUnix = wfTimestamp( TS_UNIX, $row->rev_timestamp );
			$lastRevId = (int)$row->rev_id;
		} else {
			$lastRevId = 0;
		}

		// Find revisions to this page made around and after this revision which lack category
		// notifications in recent changes. This lets jobs pick up were the last one left off.
		$encCutoff = $dbw->addQuotes( $dbw->timestamp( $cutoffUnix ) );
		$res = $dbw->select(
			'revision',
			Revision::selectFields(),
			array(
				'rev_page' => $page->getId(),
				"rev_timestamp > $encCutoff" .
					" OR (rev_timestamp = $encCutoff AND rev_id > $lastRevId)"
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_timestamp ASC, rev_id ASC' )
		);

		// Apply all category updates in revision timestamp order
		foreach ( $res as $row ) {
			$this->notifyUpdatesForRevision( $page, Revision::newFromRow( $row ) );
		}

		ScopedCallback::consume( $unlocker );

		return true;
	}

	/**
	 * @param WikiPage $page
	 * @param Revision $newRev
	 * @throws MWException
	 */
	protected function notifyUpdatesForRevision( WikiPage $page, Revision $newRev ) {
		global $wgUpdateRowsPerQuery;

		$title = $page->getTitle();

		// Get the new revision
		$newContent = $newRev ? $newRev->getContent() : null;
		if ( !$newContent ) {
			return; // deleted?
		}

		// Get the prior revision (the same for null edits)
		if ( $newRev->getParentId() ) {
			$oldRev = Revision::newFromId( $newRev->getParentId(), Revision::READ_LATEST );
			$oldContent = $oldRev ? $oldRev->getContent() : null;
			if ( !$oldContent ) {
				return; // deleted?
			}
		} else {
			$oldRev = null;
			$oldContent = null;
		}

		// Inject the same timestamp for both revision parses to reduce the chance of seeing
		// category changes merely due to time-based parser functions.
		$parseTimestamp = $newRev->getTimestamp();

		// Parse the new revision and get the categories. This could possibly use the parser
		// cache if it checked the revision ID, but that's more complicated than it's worth.
		$nOpts = $newContent->getContentHandler()->makeParserOptions( 'canonical' );
		$nOpts->setTimestamp( $parseTimestamp );
		$newOutput = $newContent->getParserOutput( $title, $newRev->getId(), $nOpts );
		$newCategories = array_keys( $newOutput->getCategories() );

		if ( $oldContent ) {
			// Parse the old rev and get the categories. Do not use link tables as that
			// assumes this updates are perfectly FIFO and that link tables are always
			// up to date, neither of which are true.
			$oOpts = $oldContent->getContentHandler()->makeParserOptions( 'canonical' );
			$oOpts->setTimestamp( $parseTimestamp );
			$oldOutput = $oldContent->getParserOutput( $title, $oldRev->getId(), $oOpts );
			$oldCategories = array_keys( $oldOutput->getCategories() );
		} else {
			$oldCategories = array();
		}

		$categoryInserts = array_values( array_diff( $newCategories, $oldCategories ) );
		$categoryDeletes = array_values( array_diff( $oldCategories, $newCategories ) );
		if ( !$categoryInserts && !$categoryDeletes ) {
			return; // nothing to do
		}

		$dbw = wfGetDB( DB_MASTER );
		$catMembChange = new CategoryMembershipChange( $title, $newRev );
		$catMembChange->checkTemplateLinks();

		foreach ( $categoryInserts as $i => $categoryName ) {
			$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
			$catMembChange->triggerCategoryAddedNotification( $categoryTitle );
			if ( $i && ( $i % $wgUpdateRowsPerQuery ) == 0 ) {
				$dbw->commit( __METHOD__, 'flush' );
				wfWaitForSlaves();
			}
		}
		if ( $categoryInserts ) {
			wfDebugLog( 'CategoryNotif',
				"Added to '$title': " . implode( ', ', $categoryInserts ) );
		}

		foreach ( $categoryDeletes as $i => $categoryName ) {
			$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
			$catMembChange->triggerCategoryRemovedNotification( $categoryTitle );
			if ( $i && ( $i % $wgUpdateRowsPerQuery ) == 0 ) {
				$dbw->commit( __METHOD__, 'flush' );
				wfWaitForSlaves();
			}
		}
		if ( $categoryDeletes ) {
			wfDebugLog( 'CategoryNotif',
				"Removed from '$title': " . implode( ', ', $categoryDeletes ) );
		}
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		unset( $info['params']['revTimestamp'] ); // first job wins

		return $info;
	}
}
