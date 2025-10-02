<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\ObjectCache;

/**
 * No-op implementation that stores nothing.
 *
 * Used as placeholder or fallback when disabling caching.
 *
 * This can be used in configuration via the CACHE_NONE constant.
 *
 * @ingroup Cache
 */
class EmptyBagOStuff extends MediumSpecificBagOStuff {
	public function __construct( array $params = [] ) {
		parent::__construct( $params );

		$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_NONE;
	}

	/** @inheritDoc */
	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		return false;
	}

	/** @inheritDoc */
	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		return true;
	}

	/** @inheritDoc */
	protected function doDelete( $key, $flags = 0 ) {
		return true;
	}

	/** @inheritDoc */
	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		return true;
	}

	/** @inheritDoc */
	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		// faster
		return $init;
	}

	/** @inheritDoc */
	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		// faster
		return true;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( EmptyBagOStuff::class, 'EmptyBagOStuff' );
