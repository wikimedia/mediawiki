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

	public function __construct( Title $title, array $params ) {
		parent::__construct( 'categoryMembershipChange', $title, $params );
		// Only need one job per page. Note that ENQUEUE_FUDGE_SEC handles races where an
		// older revision job gets inserted while the newer revision job is de-duplicated.
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

		// Get the newest revision that has a SRC_CATEGORIZE row...
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
		$config = RequestContext::getMain()->getConfig();
		$title = $page->getTitle();

		// Get the new revision
		if ( !$newRev->getContent() ) {
			return; // deleted?
		}

		// Get the prior revision (the same for null edits)
		if ( $newRev->getParentId() ) {
			$oldRev = Revision::newFromId( $newRev->getParentId(), Revision::READ_LATEST );
			if ( !$oldRev->getContent() ) {
				return; // deleted?
			}
		} else {
			$oldRev = null;
		}

		// Parse the new revision and get the categories
		$categoryChanges = $this->getExplicitCategoriesChanges( $title, $newRev, $oldRev );
		list( $categoryInserts, $categoryDeletes ) = $categoryChanges;
		if ( !$categoryInserts && !$categoryDeletes ) {
			return; // nothing to do
		}

		$dbw = wfGetDB( DB_MASTER );
		$catMembChange = new CategoryMembershipChange( $title, $newRev );
		$catMembChange->checkTemplateLinks();

		$batchSize = $config->get( 'UpdateRowsPerQuery' );
		$insertCount = 0;

		foreach ( $categoryInserts as $categoryName ) {
			$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
			$catMembChange->triggerCategoryAddedNotification( $categoryTitle );
			if ( $insertCount++ && ( $insertCount % $batchSize ) == 0 ) {
				$dbw->commit( __METHOD__, 'flush' );
				wfWaitForSlaves();
			}
		}

		foreach ( $categoryDeletes as $categoryName ) {
			$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
			$catMembChange->triggerCategoryRemovedNotification( $categoryTitle );
			if ( $insertCount++ && ( $insertCount++ % $batchSize ) == 0 ) {
				$dbw->commit( __METHOD__, 'flush' );
				wfWaitForSlaves();
			}
		}
	}

	private function getExplicitCategoriesChanges(
		Title $title, Revision $newRev, Revision $oldRev = null
	) {
		// Inject the same timestamp for both revision parses to avoid seeing category changes
		// due to time-based parser functions. Inject the same page title for the parses too.
		// Note that REPEATABLE-READ makes template/file pages appear unchanged between parses.
		$parseTimestamp = $newRev->getTimestamp();
		// Parse the old rev and get the categories. Do not use link tables as that
		// assumes these updates are perfectly FIFO and that link tables are always
		// up to date, neither of which are true.
		$oldCategories = $oldRev
			? $this->getCategoriesAtRev( $title, $oldRev, $parseTimestamp )
			: array();
		// Parse the new revision and get the categories
		$newCategories = $this->getCategoriesAtRev( $title, $newRev, $parseTimestamp );

		$categoryInserts = array_values( array_diff( $newCategories, $oldCategories ) );
		$categoryDeletes = array_values( array_diff( $oldCategories, $newCategories ) );

		return array( $categoryInserts, $categoryDeletes );
	}

	private function getCategoriesAtRev( Title $title, Revision $rev, $parseTimestamp ) {
		$content = $rev->getContent();
		$options = $content->getContentHandler()->makeParserOptions( 'canonical' );
		$options->setTimestamp( $parseTimestamp );
		// This could possibly use the parser cache if it checked the revision ID,
		// but that's more complicated than it's worth.
		$output = $content->getParserOutput( $title, $rev->getId(), $options );

		return array_keys( $output->getCategories() );
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		unset( $info['params']['revTimestamp'] ); // first job wins

		return $info;
	}
}
