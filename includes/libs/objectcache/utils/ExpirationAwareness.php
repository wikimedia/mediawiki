<?php
/**
 * Generic interface providing Time-To-Live constants for expirable object storage
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
 * Generic interface providing Time-To-Live constants for expirable object storage
 *
 * @ingroup Cache
 * @since 1.35
 */
interface ExpirationAwareness {
	/** @var int One second, in seconds */
	public const TTL_SECOND = 1;
	/** @var int One minute, in seconds */
	public const TTL_MINUTE = 60;
	/** @var int One hour, in seconds */
	public const TTL_HOUR = 3600;
	/** @var int One day, in seconds */
	public const TTL_DAY = 86400; // 24 * 3600
	/** @var int One week, in seconds */
	public const TTL_WEEK = 604800; // 7 * 24 * 3600
	/** @var int One month, in seconds */
	public const TTL_MONTH = 2592000; // 30 * 24 * 3600
	/** @var int One year, in seconds */
	public const TTL_YEAR = 31536000; // 365 * 24 * 3600

	/** @var int Reasonably strict cache time that last the life of quick requests */
	public const TTL_PROC_SHORT = 3;
	/** @var int Loose cache time that can survive slow web requests */
	public const TTL_PROC_LONG = 30;

	/** @var int Idom for "store indefinitely" */
	public const TTL_INDEFINITE = 0;
	/** @var int Idiom for "do not store the newly generated result" */
	public const TTL_UNCACHEABLE = -1;
}
