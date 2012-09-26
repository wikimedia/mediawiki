<?php
/**
 * Base code for update jobs that do something with some secondary
 * data extracted from article.
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
 * Abstract base class for update jobs that do something with some secondary
 * data extracted from article.
 *
 * @note: subclasses should NOT start or commit transactions in their doUpdate() method,
 *        a transaction will automatically be wrapped around the update. If need be,
 *        subclasses can override the beginTransaction() and commitTransaction() methods.
 */
abstract class DataUpdate implements DeferrableUpdate {

	/**
	 * Constructor
	 */
	public function __construct() {
		# noop
	}

	/**
	 * Begin an appropriate transaction, if any.
	 * This default implementation does nothing.
	 */
	public function beginTransaction() {
		//noop
	}

	/**
	 * Commit the transaction started via beginTransaction, if any.
	 * This default implementation does nothing.
	 */
	public function commitTransaction() {
		//noop
	}

	/**
	 * Abort / roll back the transaction started via beginTransaction, if any.
	 * This default implementation does nothing.
	 */
	public function rollbackTransaction() {
		//noop
	}

	/**
	 * Convenience method, calls doUpdate() on every DataUpdate in the array.
	 *
	 * This methods supports transactions logic by first calling beginTransaction()
	 * on all updates in the array, then calling doUpdate() on each, and, if all goes well,
	 * then calling commitTransaction() on each update. If an error occurs,
	 * rollbackTransaction() will be called on any update object that had beginTransaction()
	 * called but not yet commitTransaction().
	 *
	 * This allows for limited transactional logic across multiple backends for storing
	 * secondary data.
	 *
	 * @param array $updates a list of DataUpdate instances
	 * @throws Exception|null
	 */
	public static function runUpdates( $updates ) {
		if ( empty( $updates ) ) {
			return; # nothing to do
		}

		$open_transactions = array();
		$exception = null;

		/**
		 * @var $update DataUpdate
		 * @var $trans DataUpdate
		 */

		try {
			// begin transactions
			foreach ( $updates as $update ) {
				$update->beginTransaction();
				$open_transactions[] = $update;
			}

			// do work
			foreach ( $updates as $update ) {
				$update->doUpdate();
			}

			// commit transactions
			while ( count( $open_transactions ) > 0 ) {
				$trans = array_pop( $open_transactions );
				$trans->commitTransaction();
			}
		} catch ( Exception $ex ) {
			$exception = $ex;
			wfDebug( "Caught exception, will rethrow after rollback: " . $ex->getMessage() );
		}

		// rollback remaining transactions
		while ( count( $open_transactions ) > 0 ) {
			$trans = array_pop( $open_transactions );
			$trans->rollbackTransaction();
		}

		if ( $exception ) {
			throw $exception; // rethrow after cleanup
		}
	}

}
