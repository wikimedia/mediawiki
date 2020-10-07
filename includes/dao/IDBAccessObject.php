<?php
/**
 * This file contains database access object related constants.
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
 * @ingroup Database
 */

/**
 * Interface for database access objects.
 *
 * Classes using this support a set of constants in a bitfield argument to their data loading
 * functions. In general, objects should assume READ_NORMAL if no flags are explicitly given,
 * though certain objects may assume READ_LATEST for common use case or legacy reasons.
 *
 * There are four types of reads:
 *   - READ_NORMAL    : Potentially cached read of data (e.g. from a replica DB or stale replica)
 *   - READ_LATEST    : Up-to-date read as of transaction start (e.g. from master or a quorum read)
 *   - READ_LOCKING   : Up-to-date read as of now, that locks (shared) the records
 *   - READ_EXCLUSIVE : Up-to-date read as of now, that locks (exclusive) the records
 * All record locks persist for the duration of the transaction.
 *
 * A special constant READ_LATEST_IMMUTABLE can be used for fetching append-only data. Such
 * data is either (a) on a replica DB and up-to-date or (b) not yet there, but on the master/quorum.
 * Because the data is append-only, it can never be stale on a replica DB if present.
 *
 * Callers should use READ_NORMAL (or pass in no flags) unless the read determines a write.
 * In theory, such cases may require READ_LOCKING, though to avoid contention, READ_LATEST is
 * often good enough. If UPDATE race condition checks are required on a row and expensive code
 * must run after the row is fetched to determine the UPDATE, it may help to do something like:
 *   - a) Start transaction
 *   - b) Read the current row with READ_LATEST
 *   - c) Determine the new row (expensive, so we don't want to hold locks now)
 *   - d) Re-read the current row with READ_LOCKING; if it changed then bail out
 *   - e) otherwise, do the updates
 *   - f) Commit transaction
 *
 * @stable to implement
 *
 * @since 1.20
 */
interface IDBAccessObject {
	/** Constants for object loading bitfield flags (higher => higher QoS) */
	/** @var int Read from a replica DB/non-quorum */
	public const READ_NORMAL = 0;

	/** @var int Read from the master/quorum */
	public const READ_LATEST = 1;

	/* @var int Read from the master/quorum and lock out other writers */
	public const READ_LOCKING = self::READ_LATEST | 2; // READ_LATEST (1) and "LOCK IN SHARE MODE" (2)

	/** @var int Read from the master/quorum and lock out other writers and locking readers */
	public const READ_EXCLUSIVE = self::READ_LOCKING | 4; // READ_LOCKING (3) and "FOR UPDATE" (4)

	/** @var int Read from a replica DB or without a quorum, using the master/quorum on miss */
	public const READ_LATEST_IMMUTABLE = 8;

	// Convenience constant for tracking how data was loaded (higher => higher QoS)
	public const READ_NONE = -1; // not loaded yet (or the object was cleared)
}
