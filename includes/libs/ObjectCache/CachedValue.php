<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\ObjectCache;

/**
 * A value read from WANObjectCache, along with what is known about the key holding it
 *
 * This is what WANObjectCache::getWithInfo() returns, in place of the by-reference $curTTL
 * and $info parameters of WANObjectCache::get().
 *
 * @see WANObjectCache::getWithInfo()
 * @since 1.47
 * @ingroup Cache
 */
class CachedValue {
	/** @var mixed Value found at the key, or false if there was none */
	private $value;
	/** @var int|null Version of the value; null if the key is non-existent */
	private $version;
	/** @var float|null Generation timestamp; null if the key is non-existent */
	private ?float $asOf;
	/** @var int|float|null Assigned lifetime; null if the key is non-existent/tombstoned */
	private int|float|null $lifetime;
	/** @var float|null Remaining lifetime; null if the key is non-existent */
	private ?float $remainingLifetime;
	/** @var float|null Tombstone timestamp; null if the key is not tombstoned */
	private ?float $tombstoneAsOf;
	/** @var float|null Highest "check" key timestamp; null if there is none */
	private ?float $checkKeyAsOf;

	/**
	 * @internal Use WANObjectCache::getWithInfo()
	 * @param mixed $value Value found at the key, or false if there was none
	 * @param int|null $version Version of the value
	 * @param float|null $asOf UNIX timestamp of when the value was generated
	 * @param int|float|null $lifetime Seconds that the value was allowed to be reused for
	 * @param float|null $remainingLifetime Seconds of that lifetime left; may be negative
	 * @param float|null $tombstoneAsOf UNIX timestamp of when the key was tombstoned
	 * @param float|null $checkKeyAsOf UNIX timestamp of the newest relevant "check" key purge
	 */
	public function __construct(
		$value,
		$version,
		?float $asOf,
		int|float|null $lifetime,
		?float $remainingLifetime,
		?float $tombstoneAsOf,
		?float $checkKeyAsOf
	) {
		$this->value = $value;
		$this->version = $version;
		$this->asOf = $asOf;
		$this->lifetime = $lifetime;
		$this->remainingLifetime = $remainingLifetime;
		$this->tombstoneAsOf = $tombstoneAsOf;
		$this->checkKeyAsOf = $checkKeyAsOf;
	}

	/**
	 * Get the value found at the key
	 *
	 * @return mixed Value, or false if the key held nothing usable
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Check whether a usable value was found at the key
	 */
	public function exists(): bool {
		return $this->value !== false;
	}

	/**
	 * Check whether the value was found and has not expired nor been purged
	 */
	public function isFresh(): bool {
		return $this->remainingLifetime !== null && $this->remainingLifetime > 0;
	}

	/**
	 * Get the format version of the value, as set by WANGetWithSetCallbackBuilder::valueVersion()
	 *
	 * @return int|null Null if the key is non-existent or the value is unversioned
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * Get the UNIX timestamp of when the value was generated
	 *
	 * @return float|null Null if the key is non-existent
	 */
	public function getAsOf(): ?float {
		return $this->asOf;
	}

	/**
	 * Get how long the value was allowed to be reused for when it was stored
	 *
	 * @return int|float|null Seconds; null if the key is non-existent or tombstoned
	 */
	public function getLifetime(): int|float|null {
		return $this->lifetime;
	}

	/**
	 * Get how much of that lifetime is left
	 *
	 * @return float|null Seconds, negative if the value is stale; null if the key is non-existent
	 */
	public function getRemainingLifetime(): ?float {
		return $this->remainingLifetime;
	}

	/**
	 * Get the UNIX timestamp of when the key was tombstoned by a delete()
	 *
	 * @return float|null Null if the key is not tombstoned
	 */
	public function getTombstoneAsOf(): ?float {
		return $this->tombstoneAsOf;
	}

	/**
	 * Get the UNIX timestamp of the newest "check" key purge that applies to the value
	 *
	 * @return float|null Null if no "check" keys were given or none were ever purged
	 */
	public function getCheckKeyAsOf(): ?float {
		return $this->checkKeyAsOf;
	}
}
