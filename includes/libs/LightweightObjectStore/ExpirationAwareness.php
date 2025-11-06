<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\LightweightObjectStore;

/**
 * Generic interface providing Time-To-Live constants for expirable object storage
 *
 * @ingroup Cache
 * @internal
 * @since 1.35
 */
interface ExpirationAwareness {
	/** @var int One second, in seconds */
	public const TTL_SECOND = 1;
	/** @var int One minute, in seconds */
	public const TTL_MINUTE = 60;
	/** @var int One hour, in seconds */
	public const TTL_HOUR = 3_600;
	/** @var int One day, in seconds */
	public const TTL_DAY = 86_400;
	/** @var int One week, in seconds */
	public const TTL_WEEK = 604_800;
	/** @var int One month, in seconds */
	public const TTL_MONTH = 2_592_000;
	/** @var int One year, in seconds */
	public const TTL_YEAR = 31_536_000;

	/** @var int Reasonably strict cache time that last the life of quick requests */
	public const TTL_PROC_SHORT = 3;
	/** @var int Loose cache time that can survive slow web requests */
	public const TTL_PROC_LONG = 30;

	/** @var int Idiom for "store indefinitely" */
	public const TTL_INDEFINITE = 0;
	/** @var int Idiom for "do not store the newly generated result" */
	public const TTL_UNCACHEABLE = -1;
}
