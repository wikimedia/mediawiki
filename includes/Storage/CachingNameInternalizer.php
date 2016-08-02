<?php

namespace MediaWiki\Storage;

use BagOStuff;
use InvalidArgumentException;
use LoadBalancer;
use Wikimedia\Assert\Assert;

/**
 * NameInterner implemented based on a tale in a SQL based DBMS.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class CachingNameInterner implements NameInterner {

	const DEFAULT_BACKOFF_TIME = 1.0; // one second max backoff

	const DEFAULT_CACHE_DURATION = 86400; // one day in seconds

	/**
	 * @var NameInterner the backing interner to fall back to if a mapping isn't found in the cache
	 */
	private $interner;

	/**
	 * @var BagOStuff
	 */
	private $cache;

	/**
	 * @var string
	 */
	private $cacheKey;

	/**
	 * @var int
	 */
	private $cacheExpiry;

	/**
	 * @var float seconds
	 */
	private $backoff;

	/**
	 * @var array mapping of ids to names
	 */
	private $names = null;

	/**
	 * @var array mapping of names to ids
	 */
	private $ids = null;

	/**
	 * CachingNameInterner constructor.
	 *
	 * @param NameInterner $interner
	 * @param BagOStuff $cache
	 * @param string $cacheKey
	 * @param int $cacheExpiry
	 * @param float|int $backoff
	 */
	public function __construct(
		NameInterner $interner,
		BagOStuff $cache,
		$cacheKey,
		$cacheExpiry = self::DEFAULT_CACHE_DURATION,
		$backoff = self::DEFAULT_BACKOFF_TIME
	) {
		$this->interner = $interner;
		$this->cache = $cache;
		$this->cacheKey = $cacheKey;
		$this->cacheExpiry = $cacheExpiry;
		$this->backoff = $backoff;
	}

	/**
	 * @param int $internalId
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function getName( $internalId ) {
		if ( $this->names === null ) {
			$this->init();
		}

		if ( isset( $this->names[$internalId] ) ) {
			return $this->names[$internalId];
		} else {
			throw new UnknownNameException( 'Unmapped internal id: ' . $internalId );
		}
	}

	/**
	 * Returns an internal ID for the given name.
	 *
	 * @param string $name
	 *
	 * @throws InvalidArgumentException if $name is not a string or not a valid name
	 * @throws UnknownNameException if $name has not been assigned an ID
	 * @return int
	 */
	public function getInternalId( $name ) {
		if ( $this->ids === null ) {
			$this->init();
		}

		if ( isset( $this->ids[$name] ) ) {
			return $this->ids[$name];
		} else {
			throw new UnknownNameException( 'Unknown name: ' . $name );
		}
	}

	/**
	 * Lists all known internal IDs by name.
	 *
	 * @return array An associative array mapping names to internal IDs
	 */
	public function listInternalIds() {
		if ( $this->ids === null ) {
			$this->init();
		}

		return $this->ids;
	}

	/**
	 * Returns an internal ID for the given name, attempting to create a permanent
	 * mapping if none is known.
	 *
	 * @param string $name
	 *
	 * @throws InvalidArgumentException if $name is not a string or not a valid name
	 * @throws NameMappingFailedException if no mapping for $name could be created.
	 * @return int
	 */
	public function acquireInternalId( $name ) {
		Assert::parameterType( 'string', $name, '$name' );

		try {
			$id = $this->getInternalId( $name );
			return $id;
		} catch ( UnknownNameException $ex ) {
			wfDebugLog( __CLASS__, 'Local lookup failed for ' . $name );
		}

		// hit the underlying interner (but don't create a mapping yet)
		try {
			$id = $this->interner->getInternalId( $name );
			$this->updateMapping( $id, $name );
			return $id;
		} catch ( UnknownNameException $ex ) {
			wfDebugLog( __CLASS__, 'First lookup on delegate failed for ' . $name . ', backing off' );
		}

		// use randomized backoff to avoid multiple processes trying to map a new name at once
		// XXX: that's rare, is it worth the trouble?
		if ( $this->backoff > 0 ) {
			// back off and then try again, in case another process has created a mapping now
			$this->backoff();

			try {
				$id = $this->interner->getInternalId( $name );
				$this->updateMapping( $id, $name );
				return $id;
			} catch ( UnknownNameException $ex ) {
				wfDebugLog( __CLASS__, 'Second lookup on delegate failed for ' . $name . ' after backing off' );
			}
		}

		// Finally try to create a mapping. This can still fail due to care conditions.
		try {
			$id = $this->interner->acquireInternalId( $name );
			$this->updateMapping( $id, $name );
			return $id;
		} catch ( NameMappingFailedException $ex ) {
			// hopefully a write conflict, so we can now read a mapping below
			wfDebugLog( __CLASS__, 'Acquisition failed for ' . $name );
		}

		// If acquisition failed because of a write conflict, we should now have a mapping.
		try {
			$id = $this->interner->getInternalId( $name );
			$this->updateMapping( $id, $name );
			return $id;
		} catch ( UnknownNameException $ex ) {
			wfLogWarning( __CLASS__ . ': ID Acquisition ultimately failed for ' . $name );
		}

		throw new NameMappingFailedException( 'Unable to aquire a mapping for ' . $name );
	}

	/**
	 * Loads available mappings from the cache or the underlying interner.
	 * When this methods returns normally, $this->ids and $this->names are initialized.
	 */
	private function init() {
		if ( $this->ids !== null && $this->names !== null ) {
			return;
		}

		$cached = $this->cache->get( $this->cacheKey );
		if ( is_array( $cached ) ) {
			$this->ids = $cached;
			$this->names = array_flip( $cached );
			return;
		}

		$loaded = $this->interner->listInternalIds();

		$this->ids = $loaded;
		$this->names = array_flip( $loaded );

		$this->cache->set( $this->cacheKey, $loaded, $this->cacheExpiry );
	}

	private function updateMapping( $id, $name ) {
		$this->ids[$name] = $id;
		$this->names[$id] = $name;

		// See if we need to update the cache.
		// Check first if the cache already contains the new key now.
		$cached = $this->cache->get( $this->cacheKey );

		if ( !$cached || !isset( $cached[$name] ) ) {
			$this->cache->set( $this->cacheKey, $this->ids, $this->cacheExpiry );
		}
	}

	/**
	 * Waits for a random time between 0 and $this->backoff seconds.
	 */
	private function backoff() {
		$usec = mt_rand( 0, intval( $this->backoff * 1000.0 * 1000.0 ) );

		if ( $usec > 0 ) {
			usleep( $usec );
		}
	}

}
