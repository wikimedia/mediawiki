<?php
/**
 * Generic interface providing error code and quality-of-service constants for object stores
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
 * @ingroup Cache
 */

namespace Wikimedia\LightweightObjectStore;

/**
 * Generic interface providing error code and quality-of-service constants for object stores
 *
 * @ingroup Cache
 * @since 1.35
 */
interface StorageAwareness {
	/** No storage medium error */
	public const ERR_NONE = 0;
	/** Storage medium failed to yield a complete response to an operation */
	public const ERR_NO_RESPONSE = 1;
	/** Storage medium could not be reached to establish a connection */
	public const ERR_UNREACHABLE = 2;
	/** Storage medium operation failed due to usage limitations or an I/O error */
	public const ERR_UNEXPECTED = 3;

	/** @deprecated Since 1.41; Emulation/fallback mode; see QOS_EMULATION_*; higher is better */
	public const ATTR_EMULATION = 1;
	/** Durability of writes; see QOS_DURABILITY_* (higher means stronger) */
	public const ATTR_DURABILITY = 2;

	/** Data is never saved to begin with (blackhole store) */
	public const QOS_DURABILITY_NONE = 1;
	/** Data is lost at the end of the current web request or CLI script */
	public const QOS_DURABILITY_SCRIPT = 2;
	/** Data is lost once the service storing the data restarts */
	public const QOS_DURABILITY_SERVICE = 3;
	/** Data is saved to disk and writes do not usually block on fsync() */
	public const QOS_DURABILITY_DISK = 4;
	/** Data is saved to disk and writes usually block on fsync(), like a standard RDBMS */
	public const QOS_DURABILITY_RDBMS = 5;

	/** Generic "unknown" value; useful for comparisons (always "good enough") */
	public const QOS_UNKNOWN = INF;
}
