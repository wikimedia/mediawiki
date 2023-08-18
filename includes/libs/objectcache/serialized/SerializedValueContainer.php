<?php

/**
 * Helper class for segmenting large cache values without relying on serializing classes
 *
 * @since 1.34
 */
class SerializedValueContainer {
	private const SCHEMA = '__svc_schema__';
	// 64 bit UID
	private const SCHEMA_UNIFIED = 'DAAIDgoKAQw';
	// 64 bit UID
	private const SCHEMA_SEGMENTED = 'CAYCDAgCDw4';

	public const UNIFIED_DATA = '__data__';
	public const SEGMENTED_HASHES = '__hashes__';

	/**
	 * @deprecated since 1.41
	 * @param string $serialized
	 * @return stdClass
	 */
	public static function newUnified( $serialized ) {
		wfDeprecated( __METHOD__, '1.41' );
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
		return (
			$value instanceof stdClass &&
			( $value->{self::SCHEMA} ?? null ) === self::SCHEMA_UNIFIED
		);
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public static function isSegmented( $value ) {
		return (
			$value instanceof stdClass &&
			( $value->{self::SCHEMA} ?? null ) === self::SCHEMA_SEGMENTED
		);
	}
}
