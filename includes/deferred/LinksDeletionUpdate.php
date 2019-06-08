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

/**
 * Update object handling the cleanup of links tables after a page was deleted.
 */
class LinksDeletionUpdate extends LinksUpdate implements EnqueueableDataUpdate {
	/** @var WikiPage */
	protected $page;
	/** @var string */
	protected $timestamp;

	/**
	 * @param WikiPage $page Page we are updating
	 * @param int|null $pageId ID of the page we are updating [optional]
	 * @param string|null $timestamp TS_MW timestamp of deletion
	 * @throws MWException
	 */
	function __construct( WikiPage $page, $pageId = null, $timestamp = null ) {
		$this->page = $page;
		if ( $pageId ) {
			$this->mId = $pageId; // page ID at time of deletion
		} elseif ( $page->exists() ) {
			$this->mId = $page->getId();
		} else {
			throw new InvalidArgumentException( "Page ID not known. Page doesn't exist?" );
		}

		$this->timestamp = $timestamp ?: wfTimestampNow();

		$fakePO = new ParserOutput();
		$fakePO->setCacheTime( $timestamp );
		parent::__construct( $page->getTitle(), $fakePO, false );
	}

	protected function doIncrementalUpdate() {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$batchSize = $config->get( 'UpdateRowsPerQuery' );

		$id = $this->mId;
		$title = $this->mTitle;

		$dbw = $this->getDB(); // convenience

		parent::doIncrementalUpdate();

		// Typically, a category is empty when deleted, so check that we don't leave
		// spurious row in the category table.
		if ( $title->getNamespace() === NS_CATEGORY ) {
			// T166757: do the update after the main job DB commit
			DeferredUpdates::addCallableUpdate( function () use ( $title ) {
				$cat = Category::newFromName( $title->getDBkey() );
				$cat->refreshCountsIfEmpty();
			} );
		}

		// Delete restrictions for the deleted page
		$dbw->delete( 'page_restrictions', [ 'pr_page' => $id ], __METHOD__ );

		// Delete any redirect entry
		$dbw->delete( 'redirect', [ 'rd_from' => $id ], __METHOD__ );

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
					__METHOD__, $this->ticket, [ 'domain' => $dbw->getDomainID() ]
				);
			}
		}

		// Commit and release the lock (if set)
		ScopedCallback::consume( $scopedLock );
	}

	public function getAsJobSpecification() {
		return [
			'domain' => $this->getDB()->getDomainID(),
			'job' => new JobSpecification(
				'deleteLinks',
				[ 'pageId' => $this->mId, 'timestamp' => $this->timestamp ],
				[ 'removeDuplicates' => true ],
				$this->mTitle
			)
		];
	}
}
