<?php

/**
 * Helper class for segmenting large cache values without relying on serializing classes
 *
 * @since 1.34
 */
class SerializedValueContainer {
	private const SCHEMA = '__svc_schema__';
	private const SCHEMA_UNIFIED = 'DAAIDgoKAQw'; // 64 bit UID
	private const SCHEMA_SEGMENTED = 'CAYCDAgCDw4'; // 64 bit UID

	public const UNIFIED_DATA = '__data__';
	public const SEGMENTED_HASHES = '__hashes__';

	/**
	 * @param string $serialized
	 * @return stdClass
	 */
	public static function newUnified( $serialized ) {
		return (object)[
			self::SCHEMA => self::SCHEMA_UNIFIED,
			self::UNIFIED_DATA => $serialized
		];
	}

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
	public static function isUnified( $value ) {
		return self::instanceOf( $value, self::SCHEMA_UNIFIED );
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public static function isSegmented( $value ) {
		return self::instanceOf( $value, self::SCHEMA_SEGMENTED );
	}

	/**
	 * @param mixed $value
	 * @param string $schema SCHEMA_* class constant
	 * @return bool
	 */
	private static function instanceOf( $value, $schema ) {
		return (
			$value instanceof stdClass &&
			property_exists( $value, self::SCHEMA ) &&
			$value->{self::SCHEMA} === $schema
		);
	}
}
