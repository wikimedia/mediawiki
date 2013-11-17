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

/**
 * Handler for Google's WebP format <https://developers.google.com/speed/webp/>
 *
 * @ingroup Media
 */
class WebPHandler extends BitmapHandler {
	/**
	 * @var int Minimum file size to be able to read all header types
	 */
	const MINIMUM_FILESIZE = 30;

	/**
	 * Decodes a header string and returns the image size and WebP type
	 * @param string $header First bytes of the WebP file, minimum length self::MINIMUM_SIZE
	 * @return bool|array Header data array with entries 'type', 'width' and 'height', where
	 * 'type' can be 'lossy', 'lossless', 'animated' or 'unknown'
	 */
	public static function decodeHeader( $header ) {
		if ( strlen( $header ) < self::MINIMUM_FILESIZE ) {
			wfDebugLog( 'WebP', __METHOD__ . ': Unexpected header size: ' . 
				strlen( $header ) . "\n" );
			return false;
		}

		// Bytes 0-3 should be RIFF
		$riff = substr( $header, 0, 4 );
		if ( $riff != 'RIFF' ) {
			wfDebugLog( 'WebP', __METHOD__ . ': RIFF header not found: ' .
				bin2hex( $riff ) . "\n" );
			return false;
		}

		// Bytes 4-7 are file size
		// Bytes 8-11 are the FourCC WEBP
		$fourCC = substr( $header, 8, 4 );
		if ( $fourCC != 'WEBP' ) {
			wfDebugLog( 'WebP', __METHOD__ . ': FourCC is not WEBP: ' .
				bin2hex( $fourCC ) . "\n" );
			return false;
		}

		// Bytes 12-15 are the VP8 type
		$vp8Type = substr( $header, 12, 4 );
		$chunk = substr( $header, 12 );
		switch ( $vp8Type ) {
			case 'VP8 ':
				return self::decodeLossyChunkHeader( $chunk );
			case 'VP8L':
				return self::decodeLosslessChunkHeader( $chunk );
			case 'VP8X':
				return self::decodeExtendedChunkHeader( $chunk );
			default:
				wfDebugLog( 'WebP', __METHOD__ . ': Unknown VP8 type: ' . 
					bin2hex( $vp8Type ) . "\n" );
				return false;
		}
	}

	/**
	 * Decodes a lossy chunk header
	 * @param string $header Header string
	 * @return boolean|array See WebPHandler::decodeHeader
	 */
	protected static function decodeLossyChunkHeader( $header ) {
		// Bytes 0-3 are 'VP8 '
		// Bytes 4-7 are the VP8 stream size
		// Bytes 8-10 are the frame tag
		// Bytes 11-13 are 0x9D 0x01 0x2A called the sync code
		$syncCode = substr( $header, 11, 3 );
		if ( $syncCode != "\x9D\x01\x2A" ) {
			wfDebugLog( 'WebP', __METHOD__ . ': Invalid sync code: ' .
				bin2hex( $syncCode ) . "\n" );
			return false;
		}
		// Bytes 14-17 are image size
		$imageSize = unpack( 'v2', substr( $header, 14, 4 ) );
		// Image sizes are 14 bit, 2 MSB are scaling parameters which are ignored here
		return array(
			'type' => 'lossy',
			'width' => $imageSize[1] & 0x3FFF,
			'height' => $imageSize[2] & 0x3FFF
		);
	}

	/**
	 * Decodes a lossless chunk header
	 * @param string $header Header string
	 * @return boolean|array See WebPHandler::decodeHeader
	 */
	public static function decodeLosslessChunkHeader( $header ) {
		// Bytes 0-3 are 'VP8L'
		// Bytes 4-7 are chunk stream size
		// Byte 8 is 0x2F called the signature
		if ( $header{8} != "\x2F" ) {
			wfDebugLog( 'WebP',  __METHOD__ . ': Invalid signature: ' .
				bin2hex( $header{8} ) . "\n" );
			return false;
		}

		// Bytes 9-12 contain the image size
		// Bits 0-13 are width-1; bits 15-27 are height-1
		$imageSize = unpack( 'C4', substr( $header, 9, 4 ) );
		return array(
				'type' => 'lossless',
				'width' => ( $imageSize[1] | ( ( $imageSize[2] & 0x3F ) << 8 ) ) + 1,
				'height' => ( ( ( $imageSize[2] & 0xC0 ) >> 6 ) |
						( $imageSize[3] << 2 ) | ( ( $imageSize[4] & 0x03 ) << 10 ) ) + 1
		);
	}

	/**
	 * Decodes an extended chunk header
	 * @param string $header Header string
	 * @return boolean|array See WebPHandler::decodeHeader
	 */
	public static function decodeExtendedChunkHeader( $header ) {
		// Bytes 0-3 are 'VP8X'
		// Byte 4-7 are chunk length
		// Byte 8-11 are a flag bytes
		// Byte 12-17 are image size (24 bits)
		$width = unpack( 'V', substr( $header, 12, 3 ) . "\x00" );
		$height = unpack( 'V', substr( $header, 15, 3 ) . "\x00" );
		return array(
			'type' => 'unknown', // More magic is necessary to detect the real type
			'width' => ( $width[1] & 0xFFFFFF ) + 1,
			'height' => ( $height[1] & 0xFFFFFF ) + 1
		);
	}

	/**
	 * Reads the first 27 bytes from the file and returns the image size
	 * @see ImageHandler::getImageSize()
	 */
	public function getImageSize( $file, $path ) {
		$header = file_get_contents( $path, false, null, 0, self::MINIMUM_FILESIZE );
		if ( $header === false ) {
			return false;
		}
		$data = self::decodeHeader( $header );
		if ( $data == false ) {
			return false;
		}
		return array( $data['width'], $data['height'] );
	}

	/**
	 * @param $file
	 * @return bool True, not all browsers support WebP
	 */
	function mustRender( $file ) {
		return true;
	}

	/**
	 * Render files as JPG
	 *
	 * @param $ext
	 * @param $mime
	 * @param $params
	 * @return array
	 */
	function getThumbType( $ext, $mime, $params = null ) {
		return array( 'jpg', 'image/jpeg' );
	}

	/**
	 * Must use "im" for XCF
	 *
	 * @return string
	 */
	 protected static function getScalerType( $dstPath, $checkDstPath = true ) {
	 	return "im";
	 }
}