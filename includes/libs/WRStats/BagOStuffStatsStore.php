<?php

namespace Wikimedia\WRStats;

use Wikimedia\ObjectCache\BagOStuff;

/**
 * An adaptor allowing WRStats to store data in MediaWiki's BagOStuff
 *
 * @newable
 * @since 1.39
 */
class BagOStuffStatsStore implements StatsStore {
	/** @var BagOStuff */
	private $cache;

	public function __construct( BagOStuff $cache ) {
		$this->cache = $cache;
	}

	/**
	 * @inheritDoc
	 * @suppress PhanParamTooFewUnpack
	 */
	public function makeKey( $prefix, $internals, $entity ) {
		if ( $entity->isGlobal() ) {
			return $this->cache->makeGlobalKey(
				...$prefix, ...$internals, ...$entity->getComponents() );
		} else {
			return $this->cache->makeKey(
				...$prefix, ...$internals, ...$entity->getComponents() );
		}
	}

	/** @inheritDoc */
	public function incr( array $values, $ttl ) {
		foreach ( $values as $key => $value ) {
			$this->cache->incrWithInit(
				$key,
				$ttl,
				$value,
				$value,
				BagOStuff::WRITE_BACKGROUND
			);
		}
	}

	public function delete( array $keys ) {
		$this->cache->deleteMulti( $keys, BagOStuff::WRITE_BACKGROUND );
	}

	/** @inheritDoc */
	public function query( array $keys ) {
		return $this->cache->getMulti( $keys );
	}
}
