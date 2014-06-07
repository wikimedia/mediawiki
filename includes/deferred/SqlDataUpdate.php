<?php
/**
 * Base code for update jobs that put some secondary data extracted
 * from article content into the database.
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
 * Abstract base class for update jobs that put some secondary data extracted
 * from article content into the database.
 *
 * @note: subclasses should NOT start or commit transactions in their doUpdate() method,
 *        a transaction will automatically be wrapped around the update. Starting another
 *        one would break the outer transaction bracket. If need be, subclasses can override
 *        the beginTransaction() and commitTransaction() methods.
 */
abstract class SqlDataUpdate extends DataUpdate {
	/** @var DatabaseBase Database connection reference */
	protected $mDb;

	/** @var array SELECT options to be used (array) */
	protected $mOptions;

	/** @var bool Whether a transaction is open on this object (internal use only!) */
	private $mHasTransaction;

	/** @var bool Whether this update should be wrapped in a transaction */
	protected $mUseTransaction;

	/**
	 * Constructor
	 *
	 * @param bool $withTransaction whether this update should be wrapped in a
	 *   transaction (default: true). A transaction is only started if no
	 *   transaction is already in progress, see beginTransaction() for details.
	 */
	public function __construct( $withTransaction = true ) {
		global $wgAntiLockFlags;

		parent::__construct();

		if ( $wgAntiLockFlags & ALF_NO_LINK_LOCK ) {
			$this->mOptions = array();
		} else {
			$this->mOptions = array( 'FOR UPDATE' );
		}

		// @todo Get connection only when it's needed? Make sure that doesn't
		// break anything, especially transactions!
		$this->mDb = wfGetDB( DB_MASTER );

		$this->mWithTransaction = $withTransaction;
		$this->mHasTransaction = false;
	}

	/**
	 * Begin a database transaction, if $withTransaction was given as true in
	 * the constructor for this SqlDataUpdate.
	 *
	 * Because nested transactions are not supported by the Database class,
	 * this implementation checks Database::trxLevel() and only opens a
	 * transaction if none is already active.
	 */
	public function beginTransaction() {
		if ( !$this->mWithTransaction ) {
			return;
		}

		// NOTE: nested transactions are not supported, only start a transaction if none is open
		if ( $this->mDb->trxLevel() === 0 ) {
			$this->mDb->begin( get_class( $this ) . '::beginTransaction' );
			$this->mHasTransaction = true;
		}
	}

	/**
	 * Commit the database transaction started via beginTransaction (if any).
	 */
	public function commitTransaction() {
		if ( $this->mHasTransaction ) {
			$this->mDb->commit( get_class( $this ) . '::commitTransaction' );
			$this->mHasTransaction = false;
		}
	}

	/**
	 * Abort the database transaction started via beginTransaction (if any).
	 */
	public function abortTransaction() {
		if ( $this->mHasTransaction ) { //XXX: actually... maybe always?
			$this->mDb->rollback( get_class( $this ) . '::abortTransaction' );
			$this->mHasTransaction = false;
		}
	}

	/**
	 * Invalidate the cache of a list of pages from a single namespace.
	 * This is intended for use by subclasses.
	 *
	 * @param int $namespace Namespace number
	 * @param array $dbkeys
	 */
	protected function invalidatePages( $namespace, array $dbkeys ) {
		if ( $dbkeys === array() ) {
			return;
		}

		/**
		 * Determine which pages need to be updated
		 * This is necessary to prevent the job queue from smashing the DB with
		 * large numbers of concurrent invalidations of the same page
		 */
		$now = $this->mDb->timestamp();
		$ids = array();
		$res = $this->mDb->select( 'page', array( 'page_id' ),
			array(
				'page_namespace' => $namespace,
				'page_title' => $dbkeys,
				'page_touched < ' . $this->mDb->addQuotes( $now )
			), __METHOD__
		);

		foreach ( $res as $row ) {
			$ids[] = $row->page_id;
		}

		if ( $ids === array() ) {
			return;
		}

		/**
		 * Do the update
		 * We still need the page_touched condition, in case the row has changed since
		 * the non-locking select above.
		 */
		$this->mDb->update( 'page', array( 'page_touched' => $now ),
			array(
				'page_id' => $ids,
				'page_touched < ' . $this->mDb->addQuotes( $now )
			), __METHOD__
		);
	}
}
