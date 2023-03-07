<?php
/**
 * Representation for a category.
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
 * @author Simetrical
 */

namespace MediaWiki\Category;

use DeferredUpdates;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArray;
use MWException;
use ReadOnlyMode;
use stdClass;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Category objects are immutable, strictly speaking. If you call methods that change the database,
 * like to refresh link counts, the objects will be appropriately reinitialized.
 * Member variables are lazy-initialized.
 */
class Category {
	/** Name of the category, normalized to DB-key form */
	private $mName = null;
	private $mID = null;
	/**
	 * Category page title
	 * @var PageIdentity
	 */
	private $mPage = null;

	/** Counts of membership (cat_pages, cat_subcats, cat_files) */
	/** @var int */
	private $mPages = 0;

	/** @var int */
	private $mSubcats = 0;

	/** @var int */
	private $mFiles = 0;

	protected const LOAD_ONLY = 0;
	protected const LAZY_INIT_ROW = 1;

	public const ROW_COUNT_SMALL = 100;

	public const COUNT_ALL_MEMBERS = 0;
	public const COUNT_CONTENT_PAGES = 1;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	private function __construct() {
		$services = MediaWikiServices::getInstance();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->readOnlyMode = $services->getReadOnlyMode();
	}

	/**
	 * Set up all member variables using a database query.
	 * @param int $mode One of (Category::LOAD_ONLY, Category::LAZY_INIT_ROW)
	 * @throws MWException
	 * @return bool True on success, false on failure.
	 */
	protected function initialize( $mode = self::LOAD_ONLY ) {
		if ( $this->mName === null && $this->mID === null ) {
			throw new MWException( __METHOD__ . ' has both names and IDs null' );
		} elseif ( $this->mID === null ) {
			$where = [ 'cat_title' => $this->mName ];
		} elseif ( $this->mName === null ) {
			$where = [ 'cat_id' => $this->mID ];
		} else {
			# Already initialized
			return true;
		}

		$row = $this->loadBalancer->getConnectionRef( DB_REPLICA )->newSelectQueryBuilder()
			->select( [ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ] )
			->from( 'category' )
			->where( $where )
			->caller( __METHOD__ )->fetchRow();

		if ( !$row ) {
			# Okay, there were no contents.  Nothing to initialize.
			if ( $this->mPage ) {
				# If there is a page object but no record in the category table,
				# treat this as an empty category.
				$this->mID = false;
				$this->mName = $this->mPage->getDBkey();
				$this->mPages = 0;
				$this->mSubcats = 0;
				$this->mFiles = 0;

				# If the page exists, call refreshCounts to add a row for it.
				if ( $mode === self::LAZY_INIT_ROW && $this->mPage->exists() ) {
					DeferredUpdates::addCallableUpdate( [ $this, 'refreshCounts' ] );
				}

				return true;
			} else {
				return false; # Fail
			}
		}

		$this->mID = $row->cat_id;
		$this->mName = $row->cat_title;
		$this->mPages = (int)$row->cat_pages;
		$this->mSubcats = (int)$row->cat_subcats;
		$this->mFiles = (int)$row->cat_files;

		# (T15683) If the count is negative, then 1) it's obviously wrong
		# and should not be kept, and 2) we *probably* don't have to scan many
		# rows to obtain the correct figure, so let's risk a one-time recount.
		if ( $this->mPages < 0 || $this->mSubcats < 0 || $this->mFiles < 0 ) {
			$this->mPages = max( $this->mPages, 0 );
			$this->mSubcats = max( $this->mSubcats, 0 );
			$this->mFiles = max( $this->mFiles, 0 );

			if ( $mode === self::LAZY_INIT_ROW ) {
				DeferredUpdates::addCallableUpdate( [ $this, 'refreshCounts' ] );
			}
		}

		return true;
	}

	/**
	 * Factory function.
	 *
	 * @param string $name A category name (no "Category:" prefix).  It need
	 *   not be normalized, with spaces replaced by underscores.
	 * @return Category|bool Category, or false on a totally invalid name
	 */
	public static function newFromName( $name ) {
		$cat = new self();
		$title = Title::makeTitleSafe( NS_CATEGORY, $name );

		if ( !is_object( $title ) ) {
			return false;
		}

		$cat->mPage = $title;
		$cat->mName = $title->getDBkey();

		return $cat;
	}

	/**
	 * Factory function.
	 *
	 * @param PageIdentity $page Category page. Warning, no validation is performed!
	 * @return Category
	 */
	public static function newFromTitle( PageIdentity $page ): self {
		$cat = new self();

		$cat->mPage = $page;
		$cat->mName = $page->getDBkey();

		return $cat;
	}

	/**
	 * Factory function.
	 *
	 * @param int $id A category id. Warning, no validation is performed!
	 * @return Category
	 */
	public static function newFromID( $id ) {
		$cat = new self();
		$cat->mID = intval( $id );
		return $cat;
	}

	/**
	 * Factory function, for constructing a Category object from a result set
	 *
	 * @param stdClass $row Result set row, must contain the cat_xxx fields. If the fields are
	 *   null, the resulting Category object will represent an empty category if a page object was
	 *   given. If the fields are null and no PageIdentity was given, this method fails and returns
	 *   false.
	 * @param PageIdentity|null $page This must be provided if there is no cat_title field in $row.
	 * @return Category|false
	 */
	public static function newFromRow( stdClass $row, ?PageIdentity $page = null ) {
		$cat = new self();
		$cat->mPage = $page;

		# NOTE: the row often results from a LEFT JOIN on categorylinks. This may result in
		#       all the cat_xxx fields being null, if the category page exists, but nothing
		#       was ever added to the category. This case should be treated link an empty
		#       category, if possible.

		if ( $row->cat_title === null ) {
			if ( $page === null ) {
				# the name is probably somewhere in the row, for example as page_title,
				# but we can't know that here...
				return false;
			} else {
				# if we have a PageIdentity object, fetch the category name from there
				$cat->mName = $page->getDBkey();
			}

			$cat->mID = false;
			$cat->mSubcats = 0;
			$cat->mPages = 0;
			$cat->mFiles = 0;
		} else {
			$cat->mName = $row->cat_title;
			$cat->mID = $row->cat_id;
			$cat->mSubcats = (int)$row->cat_subcats;
			$cat->mPages = (int)$row->cat_pages;
			$cat->mFiles = (int)$row->cat_files;
		}

		return $cat;
	}

	/**
	 * @return string|false DB key name, or false on failure
	 */
	public function getName() {
		return $this->getX( 'mName' );
	}

	/**
	 * @return string|false Category ID, or false on failure
	 */
	public function getID() {
		return $this->getX( 'mID' );
	}

	/**
	 * @return int Total number of members count (sum of subcats, files and pages)
	 */
	public function getMemberCount(): int {
		$this->initialize( self::LAZY_INIT_ROW );

		return $this->mPages;
	}

	/**
	 * @param int $type One of self::COUNT_ALL_MEMBERS and self::COUNT_CONTENT_PAGES
	 * @return int Total number of member count or content page count
	 */
	public function getPageCount( $type = self::COUNT_ALL_MEMBERS ): int {
		$allCount = $this->getMemberCount();

		if ( $type === self::COUNT_CONTENT_PAGES ) {
			return $allCount - ( $this->getSubcatCount() + $this->getFileCount() );
		}

		return $allCount;
	}

	/**
	 * @return int Number of subcategories
	 */
	public function getSubcatCount(): int {
		return $this->getX( 'mSubcats' );
	}

	/**
	 * @return int Number of member files
	 */
	public function getFileCount(): int {
		return $this->getX( 'mFiles' );
	}

	/**
	 * @since 1.37
	 * @return ?PageIdentity the page associated with this category, or null on failure. NOTE: This
	 *   returns null on failure, unlike getTitle() which returns false.
	 */
	public function getPage(): ?PageIdentity {
		if ( $this->mPage ) {
			return $this->mPage;
		}

		if ( !$this->initialize( self::LAZY_INIT_ROW ) ) {
			return null;
		}

		$this->mPage = Title::makeTitleSafe( NS_CATEGORY, $this->mName );
		return $this->mPage;
	}

	/**
	 * @deprecated since 1.37, use getPage() instead.
	 * @return Title|bool Title for this category, or false on failure.
	 */
	public function getTitle() {
		return Title::castFromPageIdentity( $this->getPage() ) ?? false;
	}

	/**
	 * Fetch a TitleArray of up to $limit category members, beginning after the
	 * category sort key $offset.
	 * @param int|false $limit
	 * @param string $offset
	 * @return TitleArray TitleArray object for category members.
	 */
	public function getMembers( $limit = false, $offset = '' ) {
		$dbr = $this->loadBalancer->getConnection( DB_REPLICA );
		$queryBuilder = $dbr->newSelectQueryBuilder();
		$queryBuilder->select( [ 'page_id', 'page_namespace', 'page_title', 'page_len',
				'page_is_redirect', 'page_latest' ] )
			->from( 'categorylinks' )
			->join( 'page', null, [ 'cl_from = page_id' ] )
			->where( [ 'cl_to' => $this->getName() ] )
			->orderBy( 'cl_sortkey' );

		if ( $limit ) {
			$queryBuilder->limit( $limit );
		}

		if ( $offset !== '' ) {
			$queryBuilder->andWhere( $dbr->buildComparison( '>', [ 'cl_sortkey' => $offset ] ) );
		}

		$result = TitleArray::newFromResult( $queryBuilder->caller( __METHOD__ )->fetchResultSet() );

		return $result;
	}

	/**
	 * Generic accessor
	 * @param string $key
	 * @return mixed
	 */
	private function getX( $key ) {
		$this->initialize( self::LAZY_INIT_ROW );

		return $this->{$key} ?? false;
	}

	/**
	 * Refresh the counts for this category.
	 *
	 * @return bool True on success, false on failure
	 */
	public function refreshCounts() {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		# If we have just a category name, find out whether there is an
		# existing row. Or if we have just an ID, get the name, because
		# that's what categorylinks uses.
		if ( !$this->initialize( self::LOAD_ONLY ) ) {
			return false;
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		# Avoid excess contention on the same category (T162121)
		$name = __METHOD__ . ':' . md5( $this->mName );
		$scopedLock = $dbw->getScopedLockAndFlush( $name, __METHOD__, 0 );
		if ( !$scopedLock ) {
			return false;
		}

		$dbw->startAtomic( __METHOD__ );

		// Lock the `category` row before locking `categorylinks` rows to try
		// to avoid deadlocks with LinksDeletionUpdate (T195397)
		$dbw->newSelectQueryBuilder()
			->from( 'category' )
			->where( [ 'cat_title' => $this->mName ] )
			->caller( __METHOD__ )
			->forUpdate()
			->acquireRowLocks();

		// Lock all the `categorylinks` records and gaps for this category;
		// this is a separate query due to postgres limitations
		$dbw->newSelectQueryBuilder()
			->select( '*' )
			->from( 'categorylinks' )
			->join( 'page', null, 'page_id = cl_from' )
			->where( [ 'cl_to' => $this->mName ] )
			->lockInShareMode()
			->caller( __METHOD__ )
			->acquireRowLocks();

		// Get the aggregate `categorylinks` row counts for this category
		$catCond = $dbw->conditional( [ 'page_namespace' => NS_CATEGORY ], '1', 'NULL' );
		$fileCond = $dbw->conditional( [ 'page_namespace' => NS_FILE ], '1', 'NULL' );
		$result = $dbw->newSelectQueryBuilder()
			->select( [
				'pages' => 'COUNT(*)',
				'subcats' => "COUNT($catCond)",
				'files' => "COUNT($fileCond)"
			] )
			->from( 'categorylinks' )
			->join( 'page', null, 'page_id = cl_from' )
			->where( [ 'cl_to' => $this->mName ] )
			->caller( __METHOD__ )->fetchRow();

		$shouldExist = $result->pages > 0 || $this->getPage()->exists();

		if ( $this->mID ) {
			if ( $shouldExist ) {
				# The category row already exists, so do a plain UPDATE instead
				# of INSERT...ON DUPLICATE KEY UPDATE to avoid creating a gap
				# in the cat_id sequence. The row may or may not be "affected".
				$dbw->newUpdateQueryBuilder()
					->update( 'category' )
					->set( [
						'cat_pages' => $result->pages,
						'cat_subcats' => $result->subcats,
						'cat_files' => $result->files
					] )
					->where( [ 'cat_title' => $this->mName ] )
					->caller( __METHOD__ )->execute();
			} else {
				# The category is empty and has no description page, delete it
				$dbw->delete(
					'category',
					[ 'cat_title' => $this->mName ],
					__METHOD__
				);
				$this->mID = false;
			}
		} elseif ( $shouldExist ) {
			# The category row doesn't exist but should, so create it. Use
			# upsert in case of races.
			$dbw->upsert(
				'category',
				[
					'cat_title' => $this->mName,
					'cat_pages' => $result->pages,
					'cat_subcats' => $result->subcats,
					'cat_files' => $result->files
				],
				'cat_title',
				[
					'cat_pages' => $result->pages,
					'cat_subcats' => $result->subcats,
					'cat_files' => $result->files
				],
				__METHOD__
			);
			// @todo: Should we update $this->mID here? Or not since Category
			// objects tend to be short lived enough to not matter?
		}

		$dbw->endAtomic( __METHOD__ );

		# Now we should update our local counts.
		$this->mPages = (int)$result->pages;
		$this->mSubcats = (int)$result->subcats;
		$this->mFiles = (int)$result->files;

		return true;
	}

	/**
	 * Call refreshCounts() if there are no entries in the categorylinks table
	 * or if the category table has a row that states that there are no entries
	 *
	 * Due to lock errors or other failures, the precomputed counts can get out of sync,
	 * making it hard to know when to delete the category row without checking the
	 * categorylinks table.
	 *
	 * @return bool Whether links were refreshed
	 * @since 1.32
	 */
	public function refreshCountsIfEmpty() {
		return $this->refreshCountsIfSmall( 0 );
	}

	/**
	 * Call refreshCounts() if there are few entries in the categorylinks table
	 *
	 * Due to lock errors or other failures, the precomputed counts can get out of sync,
	 * making it hard to know when to delete the category row without checking the
	 * categorylinks table.
	 *
	 * This method will do a non-locking select first to reduce contention.
	 *
	 * @param int $maxSize Only refresh if there are this or less many backlinks
	 * @return bool Whether links were refreshed
	 * @since 1.34
	 */
	public function refreshCountsIfSmall( $maxSize = self::ROW_COUNT_SMALL ) {
		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$dbw->startAtomic( __METHOD__ );

		$typeOccurances = $dbw->newSelectQueryBuilder()
			->select( 'cl_type' )
			->from( 'categorylinks' )
			->where( [ 'cl_to' => $this->getName() ] )
			->limit( $maxSize + 1 )
			->caller( __METHOD__ )->fetchFieldValues();

		if ( !$typeOccurances ) {
			$doRefresh = true; // delete any category table entry
		} elseif ( count( $typeOccurances ) <= $maxSize ) {
			$countByType = array_count_values( $typeOccurances );
			$doRefresh = !$dbw->newSelectQueryBuilder()
				->select( '1' )
				->from( 'category' )
				->where( [
					'cat_title' => $this->getName(),
					'cat_pages' => $countByType['page'] ?? 0,
					'cat_subcats' => $countByType['subcat'] ?? 0,
					'cat_files' => $countByType['file'] ?? 0
				] )
				->caller( __METHOD__ )->fetchField();
		} else {
			$doRefresh = false; // category is too big
		}

		$dbw->endAtomic( __METHOD__ );

		if ( $doRefresh ) {
			$this->refreshCounts(); // update the row

			return true;
		}

		return false;
	}
}

class_alias( Category::class, 'Category' );
