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
 * @note subclasses should NOT start or commit transactions in their doUpdate() method,
 *       a transaction will automatically be wrapped around the update. If need be,
 *       subclasses can override the beginTransaction() and commitTransaction() methods.
 */
abstract class DataUpdate implements DeferrableUpdate {
	/** @var mixed Result from LBFactory::getEmptyTransactionTicket() */
	protected $ticket;

	public function __construct() {
		// noop
	}

	/**
	 * @param mixed $ticket Result of getEmptyTransactionTicket()
	 * @since 1.28
	 */
	public function setTransactionTicket( $ticket ) {
		$this->ticket = $ticket;
	}

	/**
	 * @return bool Whether this should be in the DataUpdate transaction round
	 * @since 1.28
	 */
	public function useTransaction() {
		return false;
	}

	/**
	 * Convenience method, calls doUpdate() on every DataUpdate in the array.
	 *
	 * This methods supports transactions logic by first calling beginTransaction()
	 * on all updates in the array, then calling doUpdate() on each, and, if all goes well,
	 * then calling commitTransaction() on each update.
	 *
	 * This allows for limited transactional logic across multiple backends for storing
	 * secondary data.
	 *
	 * @param DataUpdate[] $updates A list of DataUpdate instances
	 * @param string $mode Use "enqueue" to use the job queue when possible [Default: run]
	 * @throws Exception
	 * @deprecated Since 1.28 Use DeferredUpdates::execute()
	 */
	public static function runUpdates( array $updates, $mode = 'run' ) {
		DeferredUpdates::execute( $updates, $mode );
	}
}
