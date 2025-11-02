<?php

use MediaWiki\Storage\BlobAccessException;

class HistoryBlobUtils {

	/**
	 * Get the classes which are allowed to be contained in a text or ES row
	 *
	 * @return string[]
	 */
	public static function getAllowedClasses() {
		return [
			ConcatenatedGzipHistoryBlob::class,
			DiffHistoryBlob::class,
			HistoryBlobCurStub::class,
			HistoryBlobStub::class
		];
	}

	/**
	 * Unserialize a HistoryBlob
	 *
	 * @param string $str
	 * @param bool $allowDouble Allow double serialization
	 * @return HistoryBlob|HistoryBlobStub|HistoryBlobCurStub|null
	 */
	public static function unserialize( string $str, bool $allowDouble = false ) {
		$obj = unserialize( $str, [ 'allowed_classes' => self::getAllowedClasses() ] );
		if ( is_string( $obj ) && $allowDouble ) {
			// Correct for old double-serialization bug (needed by HistoryBlobStub only)
			$obj = unserialize( $obj, [ 'allowed_classes' => self::getAllowedClasses() ] );
		}
		foreach ( self::getAllowedClasses() as $class ) {
			if ( $obj instanceof $class ) {
				return $obj;
			}
		}
		return null;
	}

	/**
	 * Unserialize array data with no classes
	 *
	 * @param string $str
	 * @return array
	 */
	public static function unserializeArray( string $str ): array {
		$array = unserialize( $str, [ 'allowed_classes' => false ] );
		if ( !is_array( $array ) ) {
			throw new BlobAccessException( "Expected array in serialized string" );
		}
		return $array;
	}
}
