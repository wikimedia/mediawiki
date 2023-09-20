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

namespace MediaWiki\SiteStats;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use SiteStatsUpdate;
use Wikimedia\Rdbms\IDatabase;

/**
 * Class designed for counting of stats.
 */
class SiteStatsInit {
	/** @var IDatabase */
	private $dbr;
	/** @var int */
	private $edits;
	/** @var int */
	private $articles;
	/** @var int */
	private $pages;
	/** @var int */
	private $users;
	/** @var int */
	private $files;

	/**
	 * @param bool|IDatabase $database
	 * - bool: Whether to use the primary DB
	 * - IDatabase: Database connection to use
	 */
	public function __construct( $database = false ) {
		if ( $database instanceof IDatabase ) {
			$this->dbr = $database;
		} elseif ( $database ) {
			$this->dbr = self::getDB( DB_PRIMARY );
		} else {
			$this->dbr = self::getDB( DB_REPLICA, 'vslow' );
		}
	}

	/**
	 * Count the total number of edits
	 * @return int
	 */
	public function edits() {
		$this->edits = $this->countTableRows( 'revision' );
		$this->edits += $this->countTableRows( 'archive' );

		return $this->edits;
	}

	private function countTableRows( string $tableName ) {
		return (int)$this->dbr->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( $tableName )
			->caller( __METHOD__ )->fetchField();
	}

	/**
	 * Count pages in article space(s)
	 * @return int
	 */
	public function articles() {
		$services = MediaWikiServices::getInstance();
		$queryBuilder = $this->dbr->newSelectQueryBuilder()
			->select( 'COUNT(DISTINCT page_id)' )
			->from( 'page' )
			->where( [
					'page_namespace' => $services->getNamespaceInfo()->getContentNamespaces(),
					'page_is_redirect' => 0,
				] );

		if ( $services->getMainConfig()->get( MainConfigNames::ArticleCountMethod ) == 'link' ) {
			$queryBuilder->join( 'pagelinks', null, 'pl_from=page_id' );
		}

		$this->articles = $queryBuilder->caller( __METHOD__ )->fetchField();

		return $this->articles;
	}

	/**
	 * Count total pages
	 * @return int
	 */
	public function pages() {
		$this->pages = $this->countTableRows( 'page' );

		return $this->pages;
	}

	/**
	 * Count total users
	 * @return int
	 */
	public function users() {
		$this->users = $this->countTableRows( 'user' );

		return $this->users;
	}

	/**
	 * Count total files
	 * @return int
	 */
	public function files() {
		$this->files = $this->countTableRows( 'image' );

		return $this->files;
	}

	/**
	 * Do all updates and commit them. More or less a replacement
	 * for the original initStats, but without output.
	 *
	 * @param IDatabase|bool $database
	 * - bool: Whether to use the primary DB
	 * - IDatabase: Database connection to use
	 * @param array $options Array of options, may contain the following values
	 * - activeUsers bool: Whether to update the number of active users (default: false)
	 */
	public static function doAllAndCommit( $database, array $options = [] ) {
		$options += [ 'update' => false, 'activeUsers' => false ];

		// Grab the object and count everything
		$counter = new self( $database );

		$counter->edits();
		$counter->articles();
		$counter->pages();
		$counter->users();
		$counter->files();

		$counter->refresh();

		// Count active users if need be
		if ( $options['activeUsers'] ) {
			SiteStatsUpdate::cacheUpdate( self::getDB( DB_PRIMARY ) );
		}
	}

	/**
	 * Insert a dummy row with all zeroes if no row is present
	 */
	public static function doPlaceholderInit() {
		$dbw = self::getDB( DB_PRIMARY );
		$exists = (bool)$dbw->newSelectQueryBuilder()
			->select( '1' )
			->from( 'site_stats' )
			->where( [ 'ss_row_id' => 1 ] )
			->caller( __METHOD__ )->fetchField();
		if ( !$exists ) {
			$dbw->newInsertQueryBuilder()
				->insertInto( 'site_stats' )
				->ignore()
				->row( [ 'ss_row_id' => 1 ] + array_fill_keys( SiteStats::selectFields(), 0 ) )
				->caller( __METHOD__ )->execute();
		}
	}

	private function getShardedValue( $value, $noShards, $rowId ) {
		$remainder = $value % $noShards;
		$quotient = (int)( ( $value - $remainder ) / $noShards );
		// Add the reminder to the first row
		if ( $rowId === 1 ) {
			return $quotient + $remainder;
		}
		return $quotient;
	}

	/**
	 * Refresh site_stats
	 */
	public function refresh() {
		if ( MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::MultiShardSiteStats ) ) {
			$shardCnt = SiteStatsUpdate::SHARDS_ON;
			for ( $i = 1; $i <= $shardCnt; $i++ ) {
				$set = [
					'ss_total_edits' => $this->getShardedValue( $this->edits ?? $this->edits(), $shardCnt, $i ),
					'ss_good_articles' => $this->getShardedValue( $this->articles ?? $this->articles(), $shardCnt, $i ),
					'ss_total_pages' => $this->getShardedValue( $this->pages ?? $this->pages(), $shardCnt, $i ),
					'ss_users' => $this->getShardedValue( $this->users ?? $this->users(), $shardCnt, $i ),
					'ss_images' => $this->getShardedValue( $this->files ?? $this->files(), $shardCnt, $i ),
				];
				$row = [ 'ss_row_id' => $i ] + $set;
				self::getDB( DB_PRIMARY )->newInsertQueryBuilder()
					->insertInto( 'site_stats' )
					->row( $row )
					->onDuplicateKeyUpdate()
					->uniqueIndexFields( [ 'ss_row_id' ] )
					->set( $set )
					->caller( __METHOD__ )->execute();
			}
		} else {
			$set = [
				'ss_total_edits' => $this->edits ?? $this->edits(),
				'ss_good_articles' => $this->articles ?? $this->articles(),
				'ss_total_pages' => $this->pages ?? $this->pages(),
				'ss_users' => $this->users ?? $this->users(),
				'ss_images' => $this->files ?? $this->files(),
			];
			$row = [ 'ss_row_id' => 1 ] + $set;

			self::getDB( DB_PRIMARY )->newInsertQueryBuilder()
				->insertInto( 'site_stats' )
				->row( $row )
				->onDuplicateKeyUpdate()
				->uniqueIndexFields( [ 'ss_row_id' ] )
				->set( $set )
				->caller( __METHOD__ )->execute();
		}
	}

	/**
	 * @param int $index
	 * @param string[]|string $groups
	 * @return IDatabase
	 */
	private static function getDB( $index, $groups = [] ) {
		return MediaWikiServices::getInstance()
			->getDBLoadBalancer()
			->getConnectionRef( $index, $groups );
	}
}

/**
 * @deprecated since 1.41
 */
class_alias( SiteStatsInit::class, 'SiteStatsInit' );
