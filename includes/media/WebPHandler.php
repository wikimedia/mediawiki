<?php
/**
 * Handler for Google's WebP format <https://developers.google.com/speed/webp/>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Media
 */

use MediaWiki\FileRepo\File\File;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\XMPReader\Reader as XMPReader;

/**
 * Handler for Google's WebP format <https://developers.google.com/speed/webp/>
 *
 * @ingroup Media
 */
class WebPHandler extends BitmapHandler {
	/**
	 * Value to store in img_metadata if there was an error extracting metadata
	 */
	private const BROKEN_FILE = '0';
	/**
	 * Minimum chunk header size to be able to read all header types
	 */
	private const MINIMUM_CHUNK_HEADER_LENGTH = 18;
	/**
	 * Max size of metadata chunk to extract
	 */
	private const MAX_METADATA_CHUNK_SIZE = 1024 * 1024 * 2;
	/**
	 * Version of the metadata stored in db records
	 */
	private const _MW_WEBP_VERSION = 2;

	private const VP8X_ICC = 32;
	private const VP8X_ALPHA = 16;
	private const VP8X_EXIF = 8;
	private const VP8X_XMP = 4;
	private const VP8X_ANIM = 2;

	/** @inheritDoc */
	public function getSizeAndMetadata( $state, $filename ) {
		$parsedWebPData = self::extractMetadata( $filename );
		if ( !$parsedWebPData ) {
			return [ 'metadata' => [ '_error' => self::BROKEN_FILE ] ];
		}

		$parsedWebPData['metadata']['_MW_WEBP_VERSION'] = self::_MW_WEBP_VERSION;
		$info = [
			'width' => $parsedWebPData['width'],
			'height' => $parsedWebPData['height'],
			'metadata' => $parsedWebPData
		];
		return $info;
	}

	/** @inheritDoc */
	public function getMetadataType( $image ) {
		return 'parsed-webp';
	}

	/** @inheritDoc */
	public function isFileMetadataValid( $image ) {
		$data = $image->getMetadataArray();
		if ( $data === [ '_error' => self::BROKEN_FILE ] ) {
				// Do not repetitivly regenerate metadata on broken file.
				return self::METADATA_GOOD;
		}

		if ( !$data || !isset( $data['_error'] ) ) {
				wfDebug( __METHOD__ . " invalid WebP metadata" );

				return self::METADATA_BAD;
		}

		if ( !isset( $data['metadata']['_MW_WEBP_VERSION'] )
				|| $data['metadata']['_MW_WEBP_VERSION'] != self::_MW_WEBP_VERSION
		) {
				wfDebug( __METHOD__ . " old but compatible WebP metadata" );

				return self::METADATA_COMPATIBLE;
		}
		return self::METADATA_GOOD;
	}

	/**
	 * Extracts the image size and WebP type from a file
	 *
	 * @param string $filename
	 * @return array|false Header data array with entries 'compression', 'width' and 'height',
	 * where 'compression' can be 'lossy', 'lossless', 'animated' or 'unknown'. False if
	 * file is not a valid WebP file.
	 */
	public static function extractMetadata( $filename ) {
		wfDebugLog( 'WebP', __METHOD__ . ": Extracting metadata from $filename" );

		$info = RiffExtractor::findChunksFromFile( $filename, 100 );
		if ( $info === false ) {
			wfDebugLog( 'WebP', __METHOD__ . ": Not a valid RIFF file" );
			return false;
		}

		if ( $info['fourCC'] !== 'WEBP' ) {
			wfDebugLog( 'WebP', __METHOD__ . ': FourCC was not WEBP: ' .
				bin2hex( $info['fourCC'] ) );
			return false;
		}
		$metadata = self::extractMetadataFromChunks( $info['chunks'], $filename );
		if ( !$metadata ) {
			wfDebugLog( 'WebP', __METHOD__ . ": No VP8 chunks found" );
			return false;
		}

		return $metadata;
	}

	/**
	 * Extracts the image size and WebP type from a file based on the chunk list
	 * @param array[] $chunks Chunks as extracted by RiffExtractor
	 * @param string $filename
	 * @return array Header data array with entries 'compression', 'width' and 'height', where
	 * 'compression' can be 'lossy', 'lossless', 'animated' or 'unknown'
	 */
	public static function extractMetadataFromChunks( $chunks, $filename ) {
		$vp8Info = [];
		$exifData = null;
		$xmpData = null;

		foreach ( $chunks as $chunk ) {
			// Note, spec says it should be 'XMP ' but some real life files use "XMP\0"
			if ( !in_array( $chunk['fourCC'], [ 'VP8 ', 'VP8L', 'VP8X', 'EXIF', 'XMP ', "XMP\0" ] ) ) {
				// Not a chunk containing interesting metadata
				continue;
			}

			$chunkHeader = file_get_contents( $filename, false, null,
				$chunk['start'], self::MINIMUM_CHUNK_HEADER_LENGTH );
			wfDebugLog( 'WebP', __METHOD__ . ": {$chunk['fourCC']}" );

			switch ( $chunk['fourCC'] ) {
				case 'VP8 ':
					$vp8Info = array_merge( $vp8Info,
						self::decodeLossyChunkHeader( $chunkHeader ) );
					break;
				case 'VP8L':
					$vp8Info = array_merge( $vp8Info,
						self::decodeLosslessChunkHeader( $chunkHeader ) );
					break;
				case 'VP8X':
					$vp8Info = array_merge( $vp8Info,
						self::decodeExtendedChunkHeader( $chunkHeader ) );
					// Continue looking for other chunks to improve the metadata
					break;
				case 'EXIF':
					// Spec says ignore all but first one
					$exifData ??= self::extractChunk( $chunk, $filename );
					break;
				case 'XMP ':
				case "XMP\0":
					$xmpData ??= self::extractChunk( $chunk, $filename );
					break;
			}
		}
		$vp8Info = array_merge( $vp8Info,
			self::decodeMediaMetadata( $exifData, $xmpData, $filename ) );
		return $vp8Info;
	}

	/**
	 * Decode metadata about the file (XMP & Exif).
	 *
	 * @param string|null $exifData Binary exif data from file
	 * @param string|null $xmpData XMP data from file
	 * @param string|null $filename (Used for logging only)
	 * @return array
	 */
	private static function decodeMediaMetadata( $exifData, $xmpData, $filename ) {
		if ( $exifData === null && $xmpData === null ) {
			// Nothing to do
			return [];
		}
		$bitmapMetadataHandler = new BitmapMetadataHandler;

		if ( $xmpData && XMPReader::isSupported() ) {
			$xmpReader = new XMPReader( LoggerFactory::getInstance( 'XMP' ), $filename );
			$xmpReader->parse( $xmpData );
			$res = $xmpReader->getResults();
			foreach ( $res as $type => $array ) {
				$bitmapMetadataHandler->addMetadata( $array, $type );
			}
		}

		if ( $exifData ) {
			// The Exif section of a webp file is basically a tiff file without an image.
			// Some files start with an Exif\0\0. This is wrong according to standard and
			// will prevent us from reading file, so remove for compatibility.
			if ( str_starts_with( $exifData, "Exif\x00\x00" ) ) {
				$exifData = substr( $exifData, 6 );
			}
			$tmpFile = MediaWikiServices::getInstance()->
				getTempFSFileFactory()->
				newTempFSFile( 'webp-exif_', 'tiff' );

			$exifDataFile = $tmpFile->getPath();
			file_put_contents( $exifDataFile, $exifData );
			$byteOrder = BitmapMetadataHandler::getTiffByteOrder( $exifDataFile );
			$bitmapMetadataHandler->getExif( $exifDataFile, $byteOrder );
		}
		return [ 'media-metadata' => $bitmapMetadataHandler->getMetadataArray() ];
	}

	/**
	 * @param array $chunk Information about chunk
	 * @param string $filename
	 * @return null|string Contents of chunk (excluding fourcc, size and padding)
	 */
	private static function extractChunk( $chunk, $filename ) {
		if ( $chunk['size'] > self::MAX_METADATA_CHUNK_SIZE || $chunk['size'] < 1 ) {
			return null;
		}

		// Skip first 8 bytes as that is the fourCC header followed by size of chunk.
		return file_get_contents( $filename, false, null, $chunk['start'] + 8, $chunk['size'] );
	}

	/**
	 * Decodes a lossy chunk header
	 * @param string $header First few bytes of the header, expected to be at least 18 bytes long
	 * @return bool|array See WebPHandler::decodeHeader
	 */
	protected static function decodeLossyChunkHeader( $header ) {
		// Bytes 0-3 are 'VP8 '
		// Bytes 4-7 are the VP8 stream size
		// Bytes 8-10 are the frame tag
		// Bytes 11-13 are 0x9D 0x01 0x2A called the sync code
		$syncCode = substr( $header, 11, 3 );
		if ( $syncCode !== "\x9D\x01\x2A" ) {
			wfDebugLog( 'WebP', __METHOD__ . ': Invalid sync code: ' .
				bin2hex( $syncCode ) );
			return [];
		}
		// Bytes 14-17 are image size
		$imageSize = unpack( 'v2', substr( $header, 14, 4 ) );
		// Image sizes are 14 bit, 2 MSB are scaling parameters which are ignored here
		return [
			'compression' => 'lossy',
			'width' => $imageSize[1] & 0x3FFF,
			'height' => $imageSize[2] & 0x3FFF
		];
	}

	/**
	 * Decodes a lossless chunk header
	 * @param string $header First few bytes of the header, expected to be at least 13 bytes long
	 * @return bool|array See WebPHandler::decodeHeader
	 */
	public static function decodeLosslessChunkHeader( $header ) {
		// Bytes 0-3 are 'VP8L'
		// Bytes 4-7 are chunk stream size
		// Byte 8 is 0x2F called the signature
		if ( $header[8] !== "\x2F" ) {
			wfDebugLog( 'WebP', __METHOD__ . ': Invalid signature: ' .
				bin2hex( $header[8] ) );
			return [];
		}
		// Bytes 9-12 contain the image size
		// Bits 0-13 are width-1; bits 14-27 are height-1
		$imageSize = unpack( 'C4', substr( $header, 9, 4 ) );
		return [
				'compression' => 'lossless',
				'width' => ( $imageSize[1] | ( ( $imageSize[2] & 0x3F ) << 8 ) ) + 1,
				'height' => ( ( ( $imageSize[2] & 0xC0 ) >> 6 ) |
						( $imageSize[3] << 2 ) | ( ( $imageSize[4] & 0x0F ) << 10 ) ) + 1
		];
	}

	/**
	 * Decodes an extended chunk header
	 * @param string $header First few bytes of the header, expected to be at least 18 bytes long
	 * @return bool|array See WebPHandler::decodeHeader
	 */
	public static function decodeExtendedChunkHeader( $header ) {
		// Bytes 0-3 are 'VP8X'
		// Byte 4-7 are chunk length
		// Byte 8-11 are a flag bytes
		$flags = unpack( 'c', substr( $header, 8, 1 ) );

		// Byte 12-17 are image size (24 bits)
		$width = unpack( 'V', substr( $header, 12, 3 ) . "\x00" );
		$height = unpack( 'V', substr( $header, 15, 3 ) . "\x00" );

		return [
			'compression' => 'unknown',
			'animated' => ( $flags[1] & self::VP8X_ANIM ) === self::VP8X_ANIM,
			'transparency' => ( $flags[1] & self::VP8X_ALPHA ) === self::VP8X_ALPHA,
			'width' => ( $width[1] & 0xFFFFFF ) + 1,
			'height' => ( $height[1] & 0xFFFFFF ) + 1
		];
	}

	/**
	 * @param File $file
	 * @return bool True, not all browsers support WebP
	 */
	public function mustRender( $file ) {
		return true;
	}

	/**
	 * @param File $file
	 * @return bool False if we are unable to render this image
	 */
	public function canRender( $file ) {
		if ( $this->isAnimatedImage( $file ) ) {
			return false;
		}
		return true;
	}

	/**
	 * @param File $image
	 * @return bool
	 */
	public function isAnimatedImage( $image ) {
		$metadata = $image->getMetadataArray();
		if ( isset( $metadata['animated'] ) && $metadata['animated'] === true ) {
			return true;
		}

		return false;
	}

	/** @inheritDoc */
	public function canAnimateThumbnail( $file ) {
		return false;
	}

	/**
	 * Render files as PNG
	 *
	 * @param string $ext
	 * @param string $mime
	 * @param array|null $params
	 * @return array
	 */
	public function getThumbType( $ext, $mime, $params = null ) {
		return [ 'png', 'image/png' ];
	}

	/** @inheritDoc */
	protected function hasGDSupport() {
		return function_exists( 'gd_info' ) && ( gd_info()['WebP Support'] ?? false );
	}

	/** @inheritDoc */
	public function getCommonMetaArray( File $image ) {
		$meta = $image->getMetadataArray();
		return $meta['media-metadata'] ?? [];
	}

	/** @inheritDoc */
	public function formatMetadata( $image, $context = false ) {
		$meta = $this->getCommonMetaArray( $image );
		if ( !$meta ) {
			return false;
		}

		return $this->formatMetadataHelper( $meta, $context );
	}
}
