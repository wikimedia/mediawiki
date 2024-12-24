<?php

namespace Wikimedia\ObjectCache;

use stdClass;

/**
 * Helper class for segmenting large cache values without relying
 * on serializing classes.
 *
 * @internal
 * @since 1.34
 * @ingroup Cache
 */
class SerializedValueContainer {
	private const SCHEMA = '__svc_schema__';
	// 64 bit UID
	private const SCHEMA_SEGMENTED = 'CAYCDAgCDw4';
	public const SEGMENTED_HASHES = '__hashes__';

	/**
	 * @param string[] $segmentHashList Ordered list of hashes for each segment
	 * @return stdClass
	 */
	public static function newSegmented( array $segmentHashList ) {
		return (object)[
			self::SCHEMA => self::SCHEMA_SEGMENTED,
			self::SEGMENTED_HASHES => $segmentHashList
		];
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public static function isSegmented( $value ): bool {
		return (
			$value instanceof stdClass &&
			( $value->{self::SCHEMA} ?? null ) === self::SCHEMA_SEGMENTED
		);
	}
}
