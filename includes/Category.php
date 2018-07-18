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

/**
 * Category objects are immutable, strictly speaking. If you call methods that change the database,
 * like to refresh link counts, the objects will be appropriately reinitialized.
 * Member variables are lazy-initialized.
 *
 * @todo Move some stuff from CategoryPage.php to here, and use that.
 */
class Category {
	/** Name of the category, normalized to DB-key form */
	private $mName = null;
	private $mID = null;
	/**
	 * Category page title
	 * @var Title
	 */
	private $mTitle = null;
	/** Counts of membership (cat_pages, cat_subcats, cat_files) */
	private $mPages = null, $mSubcats = null, $mFiles = null;

	const LOAD_ONLY = 0;
	const LAZY_INIT_ROW = 1;

	private function __construct() {
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

		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow(
			'category',
			[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
			$where,
			__METHOD__
		);

		if ( !$row ) {
			# Okay, there were no contents.  Nothing to initialize.
			if ( $this->mTitle ) {
				# If there is a title object but no record in the category table,
				# treat this as an empty category.
				$this->mID = false;
				$this->mName = $this->mTitle->getDBkey();
				$this->mPages = 0;
				$this->mSubcats = 0;
				$this->mFiles = 0;

				# If the title exists, call refreshCounts to add a row for it.
				if ( $mode === self::LAZY_INIT_ROW && $this->mTitle->exists() ) {
					DeferredUpdates::addCallableUpdate( [ $this, 'refreshCounts' ] );
				}

				return true;
			} else {
				return false; # Fail
			}
		}

		$this->mID = $row->cat_id;
		$this->mName = $row->cat_title;
		$this->mPages = $row->cat_pages;
		$this->mSubcats = $row->cat_subcats;
		$this->mFiles = $row->cat_files;

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

		$cat->mTitle = $title;
		$cat->mName = $title->getDBkey();

		return $cat;
	}

	/**
	 * Factory function.
	 *
	 * @param Title $title Title for the category page
	 * @return Category|bool On a totally invalid name
	 */
	public static function newFromTitle( $title ) {
		$cat = new self();

		$cat->mTitle = $title;
		$cat->mName = $title->getDBkey();

		return $cat;
	}

	/**
	 * Factory function.
	 *
	 * @param int $id A category id
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
	 * @param object $row Result set row, must contain the cat_xxx fields. If the
	 *   fields are null, the resulting Category object will represent an empty
	 *   category if a title object was given. If the fields are null and no
	 *   title was given, this method fails and returns false.
	 * @param Title|null $title Optional title object for the category represented by
	 *   the given row. May be provided if it is already known, to avoid having
	 *   to re-create a title object later.
	 * @return Category|false
	 */
	public static function newFromRow( $row, $title = null ) {
		$cat = new self();
		$cat->mTitle = $title;

		# NOTE: the row often results from a LEFT JOIN on categorylinks. This may result in
		#       all the cat_xxx fields being null, if the category page exists, but nothing
		#       was ever added to the category. This case should be treated link an empty
		#       category, if possible.

		if ( $row->cat_title === null ) {
			if ( $title === null ) {
				# the name is probably somewhere in the row, for example as page_title,
				# but we can't know that here...
				return false;
			} else {
				# if we have a title object, fetch the category name from there
				$cat->mName = $title->getDBkey();
			}

			$cat->mID = false;
			$cat->mSubcats = 0;
			$cat->mPages = 0;
			$cat->mFiles = 0;
		} else {
			$cat->mName = $row->cat_title;
			$cat->mID = $row->cat_id;
			$cat->mSubcats = $row->cat_subcats;
			$cat->mPages = $row->cat_pages;
			$cat->mFiles = $row->cat_files;
		}

		return $cat;
	}

	/**
	 * @return mixed DB key name, or false on failure
	 */
	public function getName() {
		return $this->getX( 'mName' );
	}

	/**
	 * @return mixed Category ID, or false on failure
	 */
	public function getID() {
		return $this->getX( 'mID' );
	}

	/**
	 * @return mixed Total number of member pages, or false on failure
	 */
	public function getPageCount() {
		return $this->getX( 'mPages' );
	}

	/**
	 * @return mixed Number of subcategories, or false on failure
	 */
	public function getSubcatCount() {
		return $this->getX( 'mSubcats' );
	}

	/**
	 * @return mixed Number of member files, or false on failure
	 */
	public function getFileCount() {
		return $this->getX( 'mFiles' );
	}

	/**
	 * @return Title|bool Title for this category, or false on failure.
	 */
	public function getTitle() {
		if ( $this->mTitle ) {
			return $this->mTitle;
		}

		if ( !$this->initialize( self::LAZY_INIT_ROW ) ) {
			return false;
		}

		$this->mTitle = Title::makeTitleSafe( NS_CATEGORY, $this->mName );
		return $this->mTitle;
	}

	/**
	 * Fetch a TitleArray of up to $limit category members, beginning after the
	 * category sort key $offset.
	 * @param int|bool $limit
	 * @param string $offset
	 * @return TitleArray TitleArray object for category members.
	 */
	public function getMembers( $limit = false, $offset = '' ) {
		$dbr = wfGetDB( DB_REPLICA );

		$conds = [ 'cl_to' => $this->getName(), 'cl_from = page_id' ];
		$options = [ 'ORDER BY' => 'cl_sortkey' ];

		if ( $limit ) {
			$options['LIMIT'] = $limit;
		}

		if ( $offset !== '' ) {
			$conds[] = 'cl_sortkey > ' . $dbr->addQuotes( $offset );
		}

		$result = TitleArray::newFromResult(
			$dbr->select(
				[ 'page', 'categorylinks' ],
				[ 'page_id', 'page_namespace', 'page_title', 'page_len',
					'page_is_redirect', 'page_latest' ],
				$conds,
				__METHOD__,
				$options
			)
		);

		return $result;
	}

	/**
	 * Generic accessor
	 * @param string $key
	 * @return bool
	 */
	private function getX( $key ) {
		if ( !$this->initialize( self::LAZY_INIT_ROW ) ) {
			return false;
		}
		return $this->{$key};
	}

	/**
	 * Refresh the counts for this category.
	 *
	 * @return bool True on success, false on failure
	 */
	public function refreshCounts() {
		if ( wfReadOnly() ) {
			return false;
		}

		# If we have just a category name, find out whether there is an
		# existing row. Or if we have just an ID, get the name, because
		# that's what categorylinks uses.
		if ( !$this->initialize( self::LOAD_ONLY ) ) {
			return false;
		}

		$dbw = wfGetDB( DB_MASTER );
		# Avoid excess contention on the same category (T162121)
		$name = __METHOD__ . ':' . md5( $this->mName );
		$scopedLock = $dbw->getScopedLockAndFlush( $name, __METHOD__, 0 );
		if ( !$scopedLock ) {
			return false;
		}

		$dbw->startAtomic( __METHOD__ );

		// Lock the `category` row before locking `categorylinks` rows to try
		// to avoid deadlocks with LinksDeletionUpdate (T195397)
		$dbw->lockForUpdate( 'category', [ 'cat_title' => $this->mName ], __METHOD__ );

		// Lock all the `categorylinks` records and gaps for this category;
		// this is a separate query due to postgres/oracle limitations
		$dbw->selectRowCount(
			[ 'categorylinks', 'page' ],
			'*',
			[ 'cl_to' => $this->mName, 'page_id = cl_from' ],
			__METHOD__,
			[ 'LOCK IN SHARE MODE' ]
		);
		// Get the aggregate `categorylinks` row counts for this category
		$catCond = $dbw->conditional( [ 'page_namespace' => NS_CATEGORY ], 1, 'NULL' );
		$fileCond = $dbw->conditional( [ 'page_namespace' => NS_FILE ], 1, 'NULL' );
		$result = $dbw->selectRow(
			[ 'categorylinks', 'page' ],
			[
				'pages' => 'COUNT(*)',
				'subcats' => "COUNT($catCond)",
				'files' => "COUNT($fileCond)"
			],
			[ 'cl_to' => $this->mName, 'page_id = cl_from' ],
			__METHOD__
		);

		$shouldExist = $result->pages > 0 || $this->getTitle()->exists();

		if ( $this->mID ) {
			if ( $shouldExist ) {
				# The category row already exists, so do a plain UPDATE instead
				# of INSERT...ON DUPLICATE KEY UPDATE to avoid creating a gap
				# in the cat_id sequence. The row may or may not be "affected".
				$dbw->update(
					'category',
					[
						'cat_pages' => $result->pages,
						'cat_subcats' => $result->subcats,
						'cat_files' => $result->files
					],
					[ 'cat_title' => $this->mName ],
					__METHOD__
				);
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
				[ 'cat_title' ],
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
		$this->mPages = $result->pages;
		$this->mSubcats = $result->subcats;
		$this->mFiles = $result->files;

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
		$dbw = wfGetDB( DB_MASTER );

		$hasLink = $dbw->selectField(
			'categorylinks',
			'1',
			[ 'cl_to' => $this->getName() ],
			__METHOD__
		);
		if ( !$hasLink ) {
			$this->refreshCounts(); // delete any category table entry

			return true;
		}

		$hasBadRow = $dbw->selectField(
			'category',
			'1',
			[ 'cat_title' => $this->getName(), 'cat_pages <= 0' ],
			__METHOD__
		);
		if ( $hasBadRow ) {
			$this->refreshCounts(); // clean up this row

			return true;
		}

		return false;
	}
}
