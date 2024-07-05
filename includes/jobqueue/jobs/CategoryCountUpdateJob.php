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
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\Category\Category;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\JobSpecification;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\RawSQLValue;

/**
 * Job to update category membership counts
 *
 * Parameters include:
 *   - pageId : page ID
 *   - namespace : namespace of the page
 *   - insertedLinks : inserted categories
 *   - deletedLinks : deleted categories
 *   - batchSize : batch size
 *
 * @since 1.45
 * @ingroup JobQueue
 */
class CategoryCountUpdateJob extends Job {
	private IConnectionProvider $connectionProvider;
	private NamespaceInfo $namespaceInfo;

	/**
	 * @param PageIdentity $page the page for which to update category membership.
	 * @param array $insertedLinks Inserted categories
	 * @param array $deletedLinks Removed categories
	 * @param int $batchSize
	 * @return JobSpecification
	 */
	public static function newSpec( PageIdentity $page, array $insertedLinks, array $deletedLinks, int $batchSize ) {
		return new JobSpecification(
			'CategoryCountUpdateJob',
			[
				'pageId' => $page->getId(),
				'namespace' => $page->getNamespace(),
				'insertedLinks' => $insertedLinks,
				'deletedLinks' => $deletedLinks,
				'batchSize' => $batchSize,
			],
			[],
			$page
		);
	}

	/**
	 * Constructor for use by the Job Queue infrastructure.
	 * @note Don't call this when queueing a new instance, use newSpec() instead.
	 * @param PageIdentity $page the categorized page.
	 * @param array $params Such latest revision instance of the categorized page.
	 */
	public function __construct(
		PageIdentity $page,
		array $params,
		IConnectionProvider $connectionProvider,
		NamespaceInfo $namespaceInfo
	) {
		parent::__construct( 'CategoryCountUpdateJob', $page, $params );

		$this->connectionProvider = $connectionProvider;
		$this->namespaceInfo = $namespaceInfo;
	}

	/** @inheritDoc */
	public function run() {
		$insertedLinks = $this->params['insertedLinks'];
		$deletedLinks = $this->params['deletedLinks'];

		if ( !$insertedLinks && !$deletedLinks ) {
			return true;
		}

		$ticket = $this->connectionProvider->getEmptyTransactionTicket( __METHOD__ );
		$size = $this->params['batchSize'] ?? 100;

		// T163801: try to release any row locks to reduce contention
		$this->connectionProvider->commitAndWaitForReplication( __METHOD__, $ticket );
		if ( count( $insertedLinks ) + count( $deletedLinks ) < $size ) {
			$this->updateCategoryCounts( $insertedLinks, $deletedLinks );
			$this->connectionProvider->commitAndWaitForReplication( __METHOD__, $ticket );
		} else {
			$addedChunks = array_chunk( $insertedLinks, $size );
			foreach ( $addedChunks as $chunk ) {
				$this->updateCategoryCounts( $chunk, [] );
				if ( count( $addedChunks ) > 1 ) {
					$this->connectionProvider->commitAndWaitForReplication( __METHOD__, $ticket );
				}
			}
			$deletedChunks = array_chunk( $deletedLinks, $size );
			foreach ( $deletedChunks as $chunk ) {
				$this->updateCategoryCounts( [], $chunk );
				if ( count( $deletedChunks ) > 1 ) {
					$this->connectionProvider->commitAndWaitForReplication( __METHOD__, $ticket );
				}
			}
		}

		return true;
	}

	private function updateCategoryCounts( array $added, array $deleted ) {
		$id = $this->params['pageId'];

		// Guard against data corruption T301433
		$added = array_map( 'strval', $added );
		$deleted = array_map( 'strval', $deleted );
		$type = $this->namespaceInfo->getCategoryLinkType( $this->params['namespace'] );

		$addFields = [ 'cat_pages' => new RawSQLValue( 'cat_pages + 1' ) ];
		$removeFields = [ 'cat_pages' => new RawSQLValue( 'cat_pages - 1' ) ];
		if ( $type !== 'page' ) {
			$addFields["cat_{$type}s"] = new RawSQLValue( "cat_{$type}s + 1" );
			$removeFields["cat_{$type}s"] = new RawSQLValue( "cat_{$type}s - 1" );
		}

		$dbw = $this->connectionProvider->getPrimaryDatabase();
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'cat_id', 'cat_title' ] )
			->from( 'category' )
			->where( [ 'cat_title' => array_merge( $added, $deleted ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		$existingCategories = [];
		foreach ( $res as $row ) {
			$existingCategories[$row->cat_id] = $row->cat_title;
		}
		$existingAdded = array_intersect( $existingCategories, $added );
		$existingDeleted = array_intersect( $existingCategories, $deleted );
		$missingAdded = array_diff( $added, $existingAdded );

		// For category rows that already exist, do a plain
		// UPDATE instead of INSERT...ON DUPLICATE KEY UPDATE
		// to avoid creating gaps in the cat_id sequence.
		if ( $existingAdded ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'category' )
				->set( $addFields )
				->where( [ 'cat_id' => array_keys( $existingAdded ) ] )
				->caller( __METHOD__ )->execute();
		}

		if ( $missingAdded ) {
			$queryBuilder = $dbw->newInsertQueryBuilder()
				->insertInto( 'category' )
				->onDuplicateKeyUpdate()
				->uniqueIndexFields( [ 'cat_title' ] )
				->set( $addFields );
			foreach ( $missingAdded as $cat ) {
				$queryBuilder->row( [
					'cat_title'   => $cat,
					'cat_pages'   => 1,
					'cat_subcats' => ( $type === 'subcat' ) ? 1 : 0,
					'cat_files'   => ( $type === 'file' ) ? 1 : 0,
				] );
			}
			$queryBuilder->caller( __METHOD__ )->execute();
		}

		if ( $existingDeleted ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'category' )
				->set( $removeFields )
				->where( [ 'cat_id' => array_keys( $existingDeleted ) ] )
				->caller( __METHOD__ )->execute();
		}

		foreach ( $deleted as $catName ) {
			$cat = Category::newFromName( $catName );
			// Refresh counts on categories that should be empty now (after commit, T166757)
			DeferredUpdates::addCallableUpdate( static function () use ( $cat ) {
				$cat->refreshCountsIfEmpty();
			} );
		}
	}
}
