<?php

namespace Wikimedia\ObjectCache;

/**
 * Key-encoding methods for object caching (BagOStuff and WANObjectCache)
 *
 * @ingroup Cache
 * @since 1.34
 */
interface IStoreKeyEncoder {
	/**
	 * @see BagOStuff::makeGlobalKey
	 *
	 * @param string $keygroup
	 * @param string|int ...$components
	 *
	 * @return string
	 */
	public function makeGlobalKey( $keygroup, ...$components );

	/**
	 * @see BagOStuff::makeKey
	 *
	 * @param string $keygroup
	 * @param string|int ...$components
	 *
	 * @return string
	 */
	public function makeKey( $keygroup, ...$components );
}

/** @deprecated class alias since 1.43 */
class_alias( IStoreKeyEncoder::class, 'IStoreKeyEncoder' );
