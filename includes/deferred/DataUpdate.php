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
	 * @param DataUpdate[] $updates A list of DataUpdate instances
	 * @param string $mode Use "enqueue" to use the job queue when possible [Default: run]
	 * @throws Exception|null
	 */
	public static function runUpdates( array $updates, $mode = 'run' ) {
		if ( $mode === 'enqueue' ) {
			// When possible, push updates as jobs instead of calling doUpdate()
			$updates = self::enqueueUpdates( $updates );
		}

		foreach ( $updates as $update ) {
			$update->doUpdate();
		}
	}

	/**
	 * Enqueue jobs for every DataUpdate that support enqueueUpdate()
	 * and return the remaining DataUpdate objects (those that do not)
	 *
	 * @param DataUpdate[] $updates A list of DataUpdate instances
	 * @return DataUpdate[]
	 * @since 1.27
	 */
	protected static function enqueueUpdates( array $updates ) {
		$remaining = [];

		foreach ( $updates as $update ) {
			if ( $update instanceof EnqueueableDataUpdate ) {
				$spec = $update->getAsJobSpecification();
				JobQueueGroup::singleton( $spec['wiki'] )->push( $spec['job'] );
			} else {
				$remaining[] = $update;
			}
		}

		return $remaining;
	}
}
