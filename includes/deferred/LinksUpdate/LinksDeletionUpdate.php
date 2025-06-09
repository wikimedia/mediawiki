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

namespace MediaWiki\Deferred\LinksUpdate;

use InvalidArgumentException;
use MediaWiki\Category\Category;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\EnqueueableDataUpdate;
use MediaWiki\JobQueue\JobSpecification;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOutput;

/**
 * Update object handling the cleanup of links tables after a page was deleted.
 */
class LinksDeletionUpdate extends LinksUpdate implements EnqueueableDataUpdate {
	/** @var string */
	protected $timestamp;

	/**
	 * @param PageIdentity $page Page we are updating
	 * @param int|null $pageId ID of the page we are updating [optional]
	 * @param string|null $timestamp TS_MW timestamp of deletion
	 */
	public function __construct( PageIdentity $page, $pageId = null, $timestamp = null ) {
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
		// Use an immutable page identity to keep reference to the page id at time of deletion - T299244
		$immutablePageIdentity = new PageIdentityValue(
			$page->getId(),
			$page->getNamespace(),
			$page->getDBkey(),
			$page->getWikiId()
		);
		parent::__construct( $immutablePageIdentity, $fakePO, false );
	}

	protected function doIncrementalUpdate() {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();
		$dbProvider = $services->getConnectionProvider();
		$batchSize = $config->get( MainConfigNames::UpdateRowsPerQuery );

		$id = $this->mId;
		$title = $this->mTitle;

		$dbw = $this->getDB(); // convenience

		parent::doIncrementalUpdate();

		// Typically, a category is empty when deleted, so check that we don't leave
		// spurious row in the category table.
		if ( $title->getNamespace() === NS_CATEGORY ) {
			// T166757: do the update after the main job DB commit
			DeferredUpdates::addCallableUpdate( static function () use ( $title ) {
				$cat = Category::newFromName( $title->getDBkey() );
				$cat->refreshCountsIfSmall();
			} );
		}

		// Delete restrictions for the deleted page
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'page_restrictions' )
			->where( [ 'pr_page' => $id ] )
			->caller( __METHOD__ )->execute();

		// Delete any redirect entry
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'redirect' )
			->where( [ 'rd_from' => $id ] )
			->caller( __METHOD__ )->execute();

		// Find recentchanges entries to clean up...
		// Select RC IDs just by curid, and not by title (see T307865 and T140960)
		$rcIdsForPage = $dbw->newSelectQueryBuilder()
			->select( 'rc_id' )
			->from( 'recentchanges' )
			->where( [ $dbw->expr( 'rc_type', '!=', RC_LOG ), 'rc_cur_id' => $id ] )
			->caller( __METHOD__ )->fetchFieldValues();

		// T98706: delete by PK to avoid lock contention with RC delete log insertions
		$rcIdBatches = array_chunk( $rcIdsForPage, $batchSize );
		foreach ( $rcIdBatches as $rcIdBatch ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'recentchanges' )
				->where( [ 'rc_id' => $rcIdBatch ] )
				->caller( __METHOD__ )->execute();
			if ( count( $rcIdBatches ) > 1 ) {
				$dbProvider->commitAndWaitForReplication(
					__METHOD__, $this->ticket, [ 'domain' => $dbw->getDomainID() ]
				);
			}
		}
	}

	/** @inheritDoc */
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
