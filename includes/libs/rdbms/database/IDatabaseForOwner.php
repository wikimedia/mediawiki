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
namespace Wikimedia\Rdbms;

/**
 * Internal interface between a database object and load balancer.
 *
 * @ingroup Database
 * @internal
 */
interface IDatabaseForOwner extends IDatabase {

	/**
	 * Run a callback after each time any transaction commits or rolls back
	 *
	 * The callback takes two arguments:
	 *   - IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_ROLLBACK
	 *   - This IDatabase object
	 * Callbacks must commit any transactions that they begin.
	 *
	 * Registering a callback here will not affect writesOrCallbacks() pending.
	 *
	 * Since callbacks from this or onTransactionCommitOrIdle() can start and end transactions,
	 * a single call to IDatabase::commit might trigger multiple runs of the listener callbacks.
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback Use null to unset a listener
	 * @since 1.28
	 */
	public function setTransactionListener( $name, callable $callback = null );

	/**
	 * @return bool Whether this DB server is running in server-side read-only mode
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.28
	 */
	public function serverIsReadOnly();

	/**
	 * Get the replication position of this primary DB server
	 *
	 * @return DBPrimaryPos|false Position; false if this is not a primary DB
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.37
	 */
	public function getPrimaryPos();

	/**
	 * Get the time spend running write queries for this transaction
	 *
	 * High values could be due to scanning, updates, locking, and such.
	 *
	 * @param string $type IDatabase::ESTIMATE_* constant [default: ESTIMATE_ALL]
	 * @return float|false Returns false if not transaction is active
	 * @since 1.26
	 */
	public function pendingWriteQueryDuration( $type = self::ESTIMATE_TOTAL );

	/**
	 * Whether there is a transaction open with either possible write queries
	 * or unresolved pre-commit/commit/resolution callbacks pending
	 *
	 * This does *not* count recurring callbacks, e.g. from setTransactionListener().
	 *
	 * @return bool
	 */
	public function writesOrCallbacksPending();

	/**
	 * @return bool Whether there is a transaction open with possible write queries
	 * @since 1.27
	 */
	public function writesPending();
}
