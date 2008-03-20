<?php
/**
 * Two classes, Category and CategoryList, to deal with categories.  To reduce
 * code duplication, most of the logic is implemented for lists of categories,
 * and then single categories are a special case.  We use a separate class for
 * CategoryList so as to discourage stupid slow memory-hogging stuff like manu-
 * ally iterating through arrays of Titles and Articles, which we do way too
 * much, when a smarter class can do stuff all in one query.
 *
 * Category(List) objects are immutable, strictly speaking.  If you call me-
 * thods that change the database, like to refresh link counts, the objects
 * will be appropriately reinitialized.  Member variables are lazy-initialized.
 *
 * TODO: Move some stuff from CategoryPage.php to here, and use that.
 *
 * @author Simetrical
 */

abstract class CategoryListBase {
	# FIXME: Is storing all member variables as simple arrays a good idea?
	# Should we use some kind of associative array instead?
	/** Names of all member categories, normalized to DB-key form */
	protected $mNames = null;
	/** IDs of all member categories */
	protected $mIDs = null;
	/**
	 * Counts of membership (cat_pages, cat_subcats, cat_files) for all member
	 * categories
	 */
	protected $mPages = null, $mSubcats = null, $mFiles = null;

	protected function __construct() {}

	/** See CategoryList::newFromNames for details. */
	protected function setNames( $names ) {
		if( !is_array( $names ) ) {
			throw new MWException( __METHOD__.' passed non-array' );
		}
		$this->mNames = array_diff(
			array_map(
				array( 'CategoryListBase', 'setNamesCallback' ),
				$names
			),
			array( false )
		);
	}

	/**
	 * @param string $name Name of a putative category
	 * @return mixed Normalized name, or false if the name was invalid.
	 */
	private static function setNamesCallback( $name ) {
		$title = Title::newFromText( "Category:$name" );
		if( !is_object( $title ) ) {
			return false;
		}
		return $title->getDBKey();
	}

	/**
	 * Set up all member variables using a database query.
	 * @return bool True on success, false on failure.
	 */
	protected function initialize() {
		if( $this->mNames === null && $this->mIDs === null ) {
			throw new MWException( __METHOD__.' has both names and IDs null' );
		}
		$dbr = wfGetDB( DB_SLAVE );
		if( $this->mIDs === null ) {
			$where = array( 'cat_title' => $this->mNames );
		} elseif( $this->mNames === null ) {
			$where = array( 'cat_id' => $this->mIDs );
		} else {
			# Already initialized
			return true;
		}
		$res = $dbr->select(
			'category',
			array( 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats',
				'cat_files' ),
			$where,
			__METHOD__
		);
		if( !$res->fetchRow() ) {
			# Okay, there were no contents.  Nothing to initialize.
			return false;
		}
		$res->rewind();
		$this->mIDs = $this->mNames = $this->mPages = $this->mSubcats =
		$this->mFiles = array();
		while( $row = $res->fetchRow() ) {
			$this->mIDs     []= $row['cat_id'];
			$this->mNames   []= $row['cat_title'];
			$this->mPages   []= $row['cat_pages'];
			$this->mSubcats []= $row['cat_subcats'];
			$this->mFiles   []= $row['cat_files'];
		}
		$res->free();
	}
}

/** @todo make iterable. */
class CategoryList extends CategoryListBase {
	/**
	 * Factory function.  Any provided elements that don't correspond to a cat-
	 * egory that actually exists will be silently dropped.  FIXME: Is this
	 * sane error-handling?
	 *
	 * @param array $names An array of category names.  They need not be norma-
	 *   lized, with spaces replaced by underscores.
	 * @return CategoryList
	 */
	public static function newFromNames( $names ) {
		$cat = new self();
		$cat->setNames( $names );
		return $cat;
	}

	/**
	 * Factory function.  Any provided elements that don't correspond to a cat-
	 * egory that actually exists will be silently dropped.  FIXME: Is this
	 * sane error-handling?
	 *
	 * @param array $ids An array of category ids
	 * @return CategoryList
	 */
	public static function newFromIDs( $ids ) {
		if( !is_array( $ids ) ) {
			throw new MWException( __METHOD__.' passed non-array' );
		}
		$cat = new self();
		$cat->mIds = $ids;
		return $cat;
	}

	/** @return array Simple array of DB key names */
	public function getNames() {
		$this->initialize();
		return $this->mNames;
	}
	/**
	 * FIXME: Is this a good return type?
	 *
	 * @return array Associative array of DB key name => ID
	 */
	public function getIDs() {
		$this->initialize();
		return array_fill_keys( $this->mNames, $this->mIDs );
	}
	/**
	 * FIXME: Is this a good return type?
	 *
	 * @return array Associative array of DB key name => array(pages, subcats,
	 *   files)
	 */
	public function getCounts() {
		$this->initialize();
		$ret = array();
		foreach( array_keys( $this->mNames ) as $i ) {
			$ret[$this->mNames[$i]] = array(
				$this->mPages[$i],
				$this->mSubcats[$i],
				$this->mFiles[$i]
			);
		}
		return $ret;
	}
}

class Category extends CategoryListBase {
	/**
	 * Factory function.
	 *
	 * @param array $name A category name (no "Category:" prefix).  It need
	 *   not be normalized, with spaces replaced by underscores.
	 * @return mixed Category, or false on a totally invalid name
	 */
	public static function newFromName( $name ) {
		$cat = new self();
		$cat->setNames( array( $name ) );
		if( count( $cat->mNames ) !== 1 ) {
			return false;
		}
		return $cat;
	}

	/**
	 * Factory function.
	 *
	 * @param array $id A category id
	 * @return Category
	 */
	public static function newFromIDs( $id ) {
		$cat = new self();
		$cat->mIDs = array( $id );
		return $cat;
	}

	/** @return mixed DB key name, or false on failure */
	public function getName() { return $this->getX( 'mNames' ); }
	/** @return mixed Category ID, or false on failure */
	public function getID() { return $this->getX( 'mIDs' ); }
	/** @return mixed Total number of member pages, or false on failure */
	public function getPageCount() { return $this->getX( 'mPages' ); }
	/** @return mixed Number of subcategories, or false on failure */
	public function getSubcatCount() { return $this->getX( 'mSubcats' ); }
	/** @return mixed Number of member files, or false on failure */
	public function getFileCount() { return $this->getX( 'mFiles' ); }

	/**
	 * This is not implemented in the base class, because arrays of Titles are
	 * evil.
	 *
	 * @return mixed The Title for this category, or false on failure.
	 */
	public function getTitle() {
		if( !$this->initialize() ) {
			return false;
		}
		return Title::makeTitleSafe( NS_CATEGORY, $this->mNames[0] );
	}

	/** Generic accessor */
	private function getX( $key ) {
		if( !$this->initialize() ) {
			return false;
		}
		return $this->{$key}[0];
	}

	/**
	 * Override the parent class so that we can return false if things muck
	 * up, i.e., the name/ID we got was invalid.  Currently CategoryList si-
	 * lently eats errors so as not to kill the whole array for one bad name.
	 *
	 * @return bool True on success, false on failure.
	 */
	protected function initialize() {
		parent::initialize();
		if( count( $this->mNames ) != 1 || count( $this->mIDs ) != 1 ) {
			return false;
		}
		return true;
	}

	/**
	 * Refresh the counts for this category.
	 *
	 * FIXME: If there were some way to do this in MySQL 4 without an UPDATE
	 * for every row, it would be nice to move this to the parent class.
	 *
	 * @return bool True on success, false on failure
	 */
	public function refreshCounts() {
		if( wfReadOnly() ) {
			return false;
		}
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		# Note, we must use names for this, since categorylinks does.
		if( $this->mNames === null ) {
			if( !$this->initialize() ) {
				return false;
			}
		} else {
			# Let's be sure that the row exists in the table.  We don't need to
			# do this if we got the row from the table in initialization!
			$dbw->insert(
				'category',
				array( 'cat_title' => $this->mNames[0] ),
				__METHOD__,
				'IGNORE'
			);
		}

		$cond1 = $dbw->conditional( 'page_namespace='.NS_CATEGORY, 1, 'NULL' );
		$cond2 = $dbw->conditional( 'page_namespace='.NS_IMAGE, 1, 'NULL' );
		$result = $dbw->selectRow(
			array( 'categorylinks', 'page' ),
			array( 'COUNT(*) AS pages',
				   "COUNT($cond1) AS subcats",
				   "COUNT($cond2) AS files"
			),
			array( 'cl_to' => $this->mNames[0], 'page_id = cl_from' ),
			__METHOD__,
			'LOCK IN SHARE MODE'
		);
		$ret = $dbw->update(
			'category',
			array(
				'cat_pages' => $result->pages,
				'cat_subcats' => $result->subcats,
				'cat_files' => $result->files
			),
			array( 'cat_title' => $this->mNames[0] ),
			__METHOD__
		);
		$dbw->commit();

		# Now we should update our local counts.
		$this->mPages   = array( $result->pages );
		$this->mSubcats = array( $result->subcats );
		$this->mFiles   = array( $result->files );

		return $ret;
	}
}
