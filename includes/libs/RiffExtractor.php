<?php
/**
 * @license GPL-2.0-or-later
 * @author Bryan Tong Minh
 */

/**
 * Extractor for the Resource Interchange File Format
 *
 * @ingroup Media
 */
class RiffExtractor {
	/**
	 * @param string $filename
	 * @param int $maxChunks
	 * @return array|false
	 */
	public static function findChunksFromFile( $filename, $maxChunks = -1 ) {
		$file = fopen( $filename, 'rb' );
		$info = self::findChunks( $file, $maxChunks );
		fclose( $file );
		return $info;
	}

	/**
	 * @param resource $file
	 * @param int $maxChunks
	 * @return array|false
	 */
	public static function findChunks( $file, $maxChunks = -1 ) {
		$riff = fread( $file, 4 );
		if ( $riff !== 'RIFF' ) {
			return false;
		}

		// Next four bytes are fileSize
		$fileSize = fread( $file, 4 );
		if ( !$fileSize || strlen( $fileSize ) != 4 ) {
			return false;
		}

		// Next four bytes are the FourCC
		$fourCC = fread( $file, 4 );
		if ( !$fourCC || strlen( $fourCC ) != 4 ) {
			return false;
		}

		// Create basic info structure
		$info = [
			'fileSize' => self::extractUInt32( $fileSize ),
			'fourCC' => $fourCC,
			'chunks' => [],
		];
		$numberOfChunks = 0;

		// Find out the chunks
		while ( !feof( $file ) && !( $numberOfChunks >= $maxChunks && $maxChunks >= 0 ) ) {
			$chunkStart = ftell( $file );

			$chunkFourCC = fread( $file, 4 );
			if ( !$chunkFourCC || strlen( $chunkFourCC ) != 4 ) {
				return $info;
			}

			$chunkSize = fread( $file, 4 );
			if ( !$chunkSize || strlen( $chunkSize ) != 4 ) {
				return $info;
			}
			$intChunkSize = self::extractUInt32( $chunkSize );

			// Add chunk info to the info structure
			$info['chunks'][] = [
				'fourCC' => $chunkFourCC,
				'start' => $chunkStart,
				'size' => $intChunkSize
			];

			// Uneven chunks have padding bytes
			$padding = $intChunkSize % 2;
			// Seek to the next chunk
			fseek( $file, $intChunkSize + $padding, SEEK_CUR );

		}

		return $info;
	}

	/**
	 * Extract a little-endian uint32 from a 4 byte string
	 * @param string $string 4-byte string
	 * @return int
	 */
	public static function extractUInt32( $string ) {
		return unpack( 'V', $string )[1];
	}
}
