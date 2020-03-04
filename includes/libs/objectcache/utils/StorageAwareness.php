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
	/** @var int No error */
	public const ERR_NONE = 0;
	/** @var int No store server/medium response */
	public const ERR_NO_RESPONSE = 1;
	/** @var int Cannot connect to store server/medium */
	public const ERR_UNREACHABLE = 2;
	/** @var int Operation failed */
	public const ERR_UNEXPECTED = 3;

	/** @var int Emulation/fallback mode; see ATTR_EMULATION_*; higher is better */
	public const ATTR_EMULATION = 1;
	/** @var int Multi-DC consistency of SYNC_WRITES; see ATTR_SYNCWRITES_*; higher is better */
	public const ATTR_SYNCWRITES = 2;
	/** @var int Locality; see ATTR_LOCALITY_*; higher is better */
	public const ATTR_LOCALITY = 3;
	/** @var int Durability; see ATTR_DURABILITY_*; higher is better */
	public const ATTR_DURABILITY = 4;

	/** @var int Fallback disk-based SQL store */
	public const QOS_EMULATION_SQL = 1;

	/** @var int Asynchronous; eventually consistent or even "best effort" replicated */
	public const QOS_SYNCWRITES_NONE = 1;
	/** @var int Synchronous; eventually consistent or even "best effort" replicated */
	public const QOS_SYNCWRITES_BE = 2;
	/** @var int Synchronous with quorum; (replicas read + replicas written) > quorum */
	public const QOS_SYNCWRITES_QC = 3;
	/** @var int Synchronous; strict serializable */
	public const QOS_SYNCWRITES_SS = 4;

	/** @var int Data is replicated accross a wide area network */
	public const QOS_LOCALITY_WAN = 1;
	/** @var int Data is stored on servers within the local area network */
	public const QOS_LOCALITY_LAN = 2;
	/** @var int Data is stored in RAM owned by another process or in the local filesystem */
	public const QOS_LOCALITY_SRV = 3;
	/** @var int Data is stored in RAM owned by this process */
	public const QOS_LOCALITY_PROC = 4;

	/** @var int Data is never saved to begin with (blackhole store) */
	public const QOS_DURABILITY_NONE = 1;
	/** @var int Data is lost at the end of the current web request or CLI script */
	public const QOS_DURABILITY_SCRIPT = 2;
	/** @var int Data is lost once the service storing the data restarts */
	public const QOS_DURABILITY_SERVICE = 3;
	/** @var int Data is saved to disk, though without immediate fsync() */
	public const QOS_DURABILITY_DISK = 4;
	/** @var int Data is saved to disk via an RDBMS, usually with immediate fsync() */
	public const QOS_DURABILITY_RDBMS = 5;

	/** @var int Generic "unknown" value; useful for comparisons (always "good enough") */
	public const QOS_UNKNOWN = INF;
}
