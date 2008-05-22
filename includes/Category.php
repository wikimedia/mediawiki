<?php
/**
 * Category objects are immutable, strictly speaking.  If you call methods that change the database, like to refresh link counts, the objects will be appropriately reinitialized.  Member variables are lazy-initialized.
 *
 * TODO: Move some stuff from CategoryPage.php to here, and use that.
 *
 * @author Simetrical
 */

class Category {
	/** Name of the category, normalized to DB-key form */
	private $mName = null;
	private $mID = null;
	/** Counts of membership (cat_pages, cat_subcats, cat_files) */
	private $mPages = null, $mSubcats = null, $mFiles = null;

	private function __construct() {}

	/**
	 * Set up all member variables using a database query.
	 * @return bool True on success, false on failure.
	 */
	protected function initialize() {
		if( $this->mName === null && $this->mID === null ) {
			throw new MWException( __METHOD__.' has both names and IDs null' );
		}
		$dbr = wfGetDB( DB_SLAVE );
		if( $this->mID === null ) {
			$where = array( 'cat_title' => $this->mName );
		} elseif( $this->mName === null ) {
			$where = array( 'cat_id' => $this->mID );
		} else {
			# Already initialized
			return true;
		}
		$row = $dbr->selectRow(
			'category',
			array( 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats',
				'cat_files' ),
			$where,
			__METHOD__
		);
		if( !$row ) {
			# Okay, there were no contents.  Nothing to initialize.
			return false;
		}
		$this->mID      = $row->cat_id;
		$this->mName    = $row->cat_title;
		$this->mPages   = $row->cat_pages;
		$this->mSubcats = $row->cat_subcats;
		$this->mFiles   = $row->cat_files;

		# (bug 13683) If the count is negative, then 1) it's obviously wrong
		# and should not be kept, and 2) we *probably* don't have to scan many
		# rows to obtain the correct figure, so let's risk a one-time recount.
		if( $this->mPages < 0 || $this->mSubcats < 0 ||
		$this->mFiles < 0 ) {
			$this->refreshCounts();
		}

		return true;
	}

	/**
	 * Factory function.
	 *
	 * @param array $name A category name (no "Category:" prefix).  It need
	 *   not be normalized, with spaces replaced by underscores.
	 * @return mixed Category, or false on a totally invalid name
	 */
	public static function newFromName( $name ) {
		$cat = new self();
		$title = Title::newFromText( "Category:$name" );
		if( !is_object( $title ) ) {
			return false;
		}
		$cat->mName = $title->getDBKey();

		return $cat;
	}

	/**
	 * Factory function.
	 *
	 * @param array $id A category id
	 * @return Category
	 */
	public static function newFromID( $id ) {
		$cat = new self();
		$cat->mID = intval( $id );
		return $cat;
	}

	/** @return mixed DB key name, or false on failure */
	public function getName() { return $this->getX( 'mName' ); }
	/** @return mixed Category ID, or false on failure */
	public function getID() { return $this->getX( 'mID' ); }
	/** @return mixed Total number of member pages, or false on failure */
	public function getPageCount() { return $this->getX( 'mPages' ); }
	/** @return mixed Number of subcategories, or false on failure */
	public function getSubcatCount() { return $this->getX( 'mSubcats' ); }
	/** @return mixed Number of member files, or false on failure */
	public function getFileCount() { return $this->getX( 'mFiles' ); }

	/**
	 * @return mixed The Title for this category, or false on failure.
	 */
	public function getTitle() {
		if( !$this->initialize() ) {
			return false;
		}
		return Title::makeTitleSafe( NS_CATEGORY, $this->mName );
	}

	/** Generic accessor */
	private function getX( $key ) {
		if( !$this->initialize() ) {
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
		if( wfReadOnly() ) {
			return false;
		}
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		# Note, we must use names for this, since categorylinks does.
		if( $this->mName === null ) {
			if( !$this->initialize() ) {
				return false;
			}
		} else {
			# Let's be sure that the row exists in the table.  We don't need to
			# do this if we got the row from the table in initialization!
			$dbw->insert(
				'category',
				array( 'cat_title' => $this->mName ),
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
			array( 'cl_to' => $this->mName, 'page_id = cl_from' ),
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
			array( 'cat_title' => $this->mName ),
			__METHOD__
		);
		$dbw->commit();

		# Now we should update our local counts.
		$this->mPages   = $result->pages;
		$this->mSubcats = $result->subcats;
		$this->mFiles   = $result->files;

		return $ret;
	}
}
