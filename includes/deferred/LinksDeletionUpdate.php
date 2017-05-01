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
use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;
use Wikimedia\Rdbms\IDatabase;

/**
 * Update object handling the cleanup of links tables after a page was deleted.
 */
class LinksDeletionUpdate extends DataUpdate implements EnqueueableDataUpdate {
	/** @var WikiPage */
	protected $page;
	/** @var integer */
	protected $pageId;
	/** @var string */
	protected $timestamp;

	/** @var IDatabase */
	private $db;

	/**
	 * @param WikiPage $page Page we are updating
	 * @param integer|null $pageId ID of the page we are updating [optional]
	 * @param string|null $timestamp TS_MW timestamp of deletion
	 * @throws MWException
	 */
	function __construct( WikiPage $page, $pageId = null, $timestamp = null ) {
		parent::__construct();

		$this->page = $page;
		if ( $pageId ) {
			$this->pageId = $pageId; // page ID at time of deletion
		} elseif ( $page->exists() ) {
			$this->pageId = $page->getId();
		} else {
			throw new InvalidArgumentException( "Page ID not known. Page doesn't exist?" );
		}

		$this->timestamp = $timestamp ?: wfTimestampNow();
	}

	public function doUpdate() {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$batchSize = $config->get( 'UpdateRowsPerQuery' );

		// Page may already be deleted, so don't just getId()
		$id = $this->pageId;

		if ( $this->ticket ) {
			// Make sure all links update threads see the changes of each other.
			// This handles the case when updates have to batched into several COMMITs.
			$scopedLock = LinksUpdate::acquirePageLock( $this->getDB(), $id );
		}

		$title = $this->page->getTitle();
		$dbw = $this->getDB(); // convenience

		// Delete restrictions for it
		$dbw->delete( 'page_restrictions', [ 'pr_page' => $id ], __METHOD__ );

		// Fix category table counts
		$cats = $dbw->selectFieldValues(
			'categorylinks',
			'cl_to',
			[ 'cl_from' => $id ],
			__METHOD__
		);
		$catBatches = array_chunk( $cats, $batchSize );
		foreach ( $catBatches as $catBatch ) {
			$this->page->updateCategoryCounts( [], $catBatch, $id );
			if ( count( $catBatches ) > 1 ) {
				$lbFactory->commitAndWaitForReplication(
					__METHOD__, $this->ticket, [ 'wiki' => $dbw->getWikiID() ]
				);
			}
		}

		// Refresh the category table entry if it seems to have no pages. Check
		// master for the most up-to-date cat_pages count.
		if ( $title->getNamespace() === NS_CATEGORY ) {
			$row = $dbw->selectRow(
				'category',
				[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
				[ 'cat_title' => $title->getDBkey(), 'cat_pages <= 0' ],
				__METHOD__
			);
			if ( $row ) {
				Category::newFromRow( $row, $title )->refreshCounts();
			}
		}

		$this->batchDeleteByPK(
			'pagelinks',
			[ 'pl_from' => $id ],
			[ 'pl_from', 'pl_namespace', 'pl_title' ],
			$batchSize
		);
		$this->batchDeleteByPK(
			'imagelinks',
			[ 'il_from' => $id ],
			[ 'il_from', 'il_to' ],
			$batchSize
		);
		$this->batchDeleteByPK(
			'categorylinks',
			[ 'cl_from' => $id ],
			[ 'cl_from', 'cl_to' ],
			$batchSize
		);
		$this->batchDeleteByPK(
			'templatelinks',
			[ 'tl_from' => $id ],
			[ 'tl_from', 'tl_namespace', 'tl_title' ],
			$batchSize
		);
		$this->batchDeleteByPK(
			'externallinks',
			[ 'el_from' => $id ],
			[ 'el_id' ],
			$batchSize
		);
		$this->batchDeleteByPK(
			'langlinks',
			[ 'll_from' => $id ],
			[ 'll_from', 'll_lang' ],
			$batchSize
		);
		$this->batchDeleteByPK(
			'iwlinks',
			[ 'iwl_from' => $id ],
			[ 'iwl_from', 'iwl_prefix', 'iwl_title' ],
			$batchSize
		);

		// Delete any redirect entry or page props entries
		$dbw->delete( 'redirect', [ 'rd_from' => $id ], __METHOD__ );
		$dbw->delete( 'page_props', [ 'pp_page' => $id ], __METHOD__ );

		// Find recentchanges entries to clean up...
		$rcIdsForTitle = $dbw->selectFieldValues(
			'recentchanges',
			'rc_id',
			[
				'rc_type != ' . RC_LOG,
				'rc_namespace' => $title->getNamespace(),
				'rc_title' => $title->getDBkey(),
				'rc_timestamp < ' .
					$dbw->addQuotes( $dbw->timestamp( $this->timestamp ) )
			],
			__METHOD__
		);
		$rcIdsForPage = $dbw->selectFieldValues(
			'recentchanges',
			'rc_id',
			[ 'rc_type != ' . RC_LOG, 'rc_cur_id' => $id ],
			__METHOD__
		);

		// T98706: delete by PK to avoid lock contention with RC delete log insertions
		$rcIdBatches = array_chunk( array_merge( $rcIdsForTitle, $rcIdsForPage ), $batchSize );
		foreach ( $rcIdBatches as $rcIdBatch ) {
			$dbw->delete( 'recentchanges', [ 'rc_id' => $rcIdBatch ], __METHOD__ );
			if ( count( $rcIdBatches ) > 1 ) {
				$lbFactory->commitAndWaitForReplication(
					__METHOD__, $this->ticket, [ 'wiki' => $dbw->getWikiID() ]
				);
			}
		}

		// Commit and release the lock (if set)
		ScopedCallback::consume( $scopedLock );
	}

	private function batchDeleteByPK( $table, array $conds, array $pk, $bSize ) {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$dbw = $this->getDB(); // convenience

		$res = $dbw->select( $table, $pk, $conds, __METHOD__ );

		$pkDeleteConds = [];
		foreach ( $res as $row ) {
			$pkDeleteConds[] = $dbw->makeList( (array)$row, LIST_AND );
			if ( count( $pkDeleteConds ) >= $bSize ) {
				$dbw->delete( $table, $dbw->makeList( $pkDeleteConds, LIST_OR ), __METHOD__ );
				$lbFactory->commitAndWaitForReplication(
					__METHOD__, $this->ticket, [ 'wiki' => $dbw->getWikiID() ]
				);
				$pkDeleteConds = [];
			}
		}

		if ( $pkDeleteConds ) {
			$dbw->delete( $table, $dbw->makeList( $pkDeleteConds, LIST_OR ), __METHOD__ );
		}
	}

	protected function getDB() {
		if ( !$this->db ) {
			$this->db = wfGetDB( DB_MASTER );
		}

		return $this->db;
	}

	public function getAsJobSpecification() {
		return [
			'wiki' => $this->getDB()->getWikiID(),
			'job'  => new JobSpecification(
				'deleteLinks',
				[ 'pageId' => $this->pageId, 'timestamp' => $this->timestamp ],
				[ 'removeDuplicates' => true ],
				$this->page->getTitle()
			)
		];
	}
}
