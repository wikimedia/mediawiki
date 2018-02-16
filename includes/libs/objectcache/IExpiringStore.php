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
 * @ingroup Cache
 * @author 2015 Timo Tijhof
 */

/**
 * Generic base class for storage interfaces.
 *
 * Provides convenient TTL constants.
 *
 * @ingroup Cache
 * @since 1.27
 */
interface IExpiringStore {
	// Constants for TTL values, in seconds
	const TTL_SECOND = 1;
	const TTL_MINUTE = 60;
	const TTL_HOUR = 3600;
	const TTL_DAY = 86400; // 24 * 3600
	const TTL_WEEK = 604800; // 7 * 24 * 3600
	const TTL_MONTH = 2592000; // 30 * 24 * 3600
	const TTL_YEAR = 31536000; // 365 * 24 * 3600

	// Shorthand process cache TTLs (useful for web requests and CLI mode)
	const TTL_PROC_SHORT = 3; // reasonably strict cache time that last the life of quick requests
	const TTL_PROC_LONG = 30; // loose cache time that can survive slow web requests

	const TTL_INDEFINITE = 0;

	// Attribute and QoS constants; higher QOS values with the same prefix rank higher...
	// Medium attributes constants related to emulation or media type
	const ATTR_EMULATION = 1;
	const QOS_EMULATION_SQL = 1;
	// Medium attributes constants related to replica consistency
	const ATTR_SYNCWRITES = 2; // SYNC_WRITES flag support
	const QOS_SYNCWRITES_NONE = 1; // replication only supports eventual consistency or less
	const QOS_SYNCWRITES_BE = 2; // best effort synchronous with limited retries
	const QOS_SYNCWRITES_QC = 3; // write quorum applied directly to state machines where R+W > N
	const QOS_SYNCWRITES_SS = 4; // strict-serializable, nodes refuse reads if possible stale
	// Generic "unknown" value that is useful for comparisons (e.g. always good enough)
	const QOS_UNKNOWN = INF;
}
