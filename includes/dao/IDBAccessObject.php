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
 * There are three types of reads:
 *   - READ_NORMAL  : Potentially cached read of data (e.g. from a slave or stale replica)
 *   - READ_LATEST  : Up-to-date read as of transaction start (e.g. from master or a quorum read)
 *   - READ_LOCKING : Up-to-date read as of now, that locks the records for the transaction
 *
 * Callers should use READ_NORMAL (or pass in no flags) unless the read determines a write.
 * In theory, such cases may require READ_LOCKING, though to avoid contention, READ_LATEST is
 * often good enough. If UPDATE race condition checks are required on a row and expensive code
 * must run after the row is fetched to determine the UPDATE, it may help to do something like:
 *   - a) Read the current row
 *   - b) Determine the new row (expensive, so we don't want to hold locks now)
 *   - c) Re-read the current row with READ_LOCKING; if it changed then bail out
 *   - d) otherwise, do the updates
 *
 * @since 1.20
 */
interface IDBAccessObject {
	// Constants for object loading bitfield flags (higher => higher QoS)
	const READ_LATEST = 1; // read from the master
	const READ_LOCKING = 3; // READ_LATEST and "FOR UPDATE"

	// Convenience constant for callers to explicitly request slave data
	const READ_NORMAL = 0; // read from the slave

	// Convenience constant for tracking how data was loaded (higher => higher QoS)
	const READ_NONE = -1; // not loaded yet (or the object was cleared)
}
