<?php
/**
 * PNG frame counter and metadata extractor.
 *
 * Slightly derived from GIFMetadataExtractor.php
 * Deliberately not using MWExceptions to avoid external dependencies, encouraging
 * redistribution.
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

use Wikimedia\AtEase\AtEase;

/**
 * PNG frame counter.
 *
 * @ingroup Media
 */
class PNGMetadataExtractor {
	/** @var string */
	private static $pngSig;

	/** @var int */
	private static $crcSize;

	/** @var array */
	private static $textChunks;

	public const VERSION = 1;
	private const MAX_CHUNK_SIZE = 3_145_728; // 3 mebibytes

	/**
	 * @param string $filename
	 * @return array
	 */
	public static function getMetadata( $filename ) {
		self::$pngSig = pack( "C8", 137, 80, 78, 71, 13, 10, 26, 10 );
		self::$crcSize = 4;
		/* based on list at http://owl.phy.queensu.ca/~phil/exiftool/TagNames/PNG.html#TextualData
		 * and https://www.w3.org/TR/PNG/#11keywords
		 */
		self::$textChunks = [
			'xml:com.adobe.xmp' => 'xmp',
			# Artist is unofficial. Author is the recommended
			# keyword in the PNG spec. However some people output
			# Artist so support both.
			'artist' => 'Artist',
			'model' => 'Model',
			'make' => 'Make',
			'author' => 'Artist',
			'comment' => 'PNGFileComment',
			'description' => 'ImageDescription',
			'title' => 'ObjectName',
			'copyright' => 'Copyright',
			# Source as in original device used to make image
			# not as in who gave you the image
			'source' => 'Model',
			'software' => 'Software',
			'disclaimer' => 'Disclaimer',
			'warning' => 'ContentWarning',
			'url' => 'Identifier', # Not sure if this is best mapping. Maybe WebStatement.
			'label' => 'Label',
			'creation time' => 'DateTimeDigitized',
			/* Other potentially useful things - Document */
		];

		$frameCount = 0;
		$loopCount = 1;
		$text = [];
		$duration = 0.0;
		$width = 0;
		$height = 0;
		$bitDepth = 0;
		$colorType = 'unknown';

		if ( !$filename ) {
			throw new InvalidArgumentException( __METHOD__ . ": No file name specified" );
		}

		if ( !file_exists( $filename ) || is_dir( $filename ) ) {
			throw new InvalidArgumentException( __METHOD__ . ": File $filename does not exist" );
		}

		$fh = fopen( $filename, 'rb' );

		if ( !$fh ) {
			throw new InvalidArgumentException( __METHOD__ . ": Unable to open file $filename" );
		}

		// Check for the PNG header
		$buf = self::read( $fh, 8 );
		if ( $buf !== self::$pngSig ) {
			throw new InvalidArgumentException( __METHOD__ . ": Not a valid PNG file; header: $buf" );
		}

		// Read chunks
		while ( !feof( $fh ) ) {
			$buf = self::read( $fh, 4 );
			$chunk_size = unpack( "N", $buf )[1];

			if ( $chunk_size < 0 || $chunk_size > self::MAX_CHUNK_SIZE ) {
				wfDebug( __METHOD__ . ': Chunk size of ' . $chunk_size .
					' too big, skipping. Max size is: ' . self::MAX_CHUNK_SIZE );
				if ( fseek( $fh, 4 + $chunk_size + self::$crcSize, SEEK_CUR ) !== 0 ) {
					throw new InvalidArgumentException( __METHOD__ . ': seek error' );
				}
				continue;
			}

			$chunk_type = self::read( $fh, 4 );
			$buf = self::read( $fh, $chunk_size );
			$crc = self::read( $fh, self::$crcSize );
			$computed = crc32( $chunk_type . $buf );
			if ( pack( 'N', $computed ) !== $crc ) {
				wfDebug( __METHOD__ . ': chunk has invalid CRC, skipping' );
				continue;
			}

			if ( $chunk_type === "IHDR" ) {
				$width = unpack( 'N', substr( $buf, 0, 4 ) )[1];
				$height = unpack( 'N', substr( $buf, 4, 4 ) )[1];
				$bitDepth = ord( substr( $buf, 8, 1 ) );
				// Detect the color type in British English as per the spec
				// https://www.w3.org/TR/PNG/#11IHDR
				switch ( ord( substr( $buf, 9, 1 ) ) ) {
					case 0:
						$colorType = 'greyscale';
						break;
					case 2:
						$colorType = 'truecolour';
						break;
					case 3:
						$colorType = 'index-coloured';
						break;
					case 4:
						$colorType = 'greyscale-alpha';
						break;
					case 6:
						$colorType = 'truecolour-alpha';
						break;
					default:
						$colorType = 'unknown';
						break;
				}
			} elseif ( $chunk_type === "acTL" ) {
				if ( $chunk_size < 4 ) {
					wfDebug( __METHOD__ . ": acTL chunk too small" );
					continue;
				}

				$actl = unpack( "Nframes/Nplays", $buf );
				$frameCount = $actl['frames'];
				$loopCount = $actl['plays'];
			} elseif ( $chunk_type === "fcTL" ) {
				$buf = substr( $buf, 20 );
				if ( strlen( $buf ) < 4 ) {
					wfDebug( __METHOD__ . ": fcTL chunk too small" );
					continue;
				}

				$fctldur = unpack( "ndelay_num/ndelay_den", $buf );
				if ( $fctldur['delay_den'] == 0 ) {
					$fctldur['delay_den'] = 100;
				}
				if ( $fctldur['delay_num'] ) {
					$duration += $fctldur['delay_num'] / $fctldur['delay_den'];
				}
			} elseif ( $chunk_type === "iTXt" ) {
				// Extracts iTXt chunks, uncompressing if necessary.
				$items = [];
				if ( preg_match(
					'/^([^\x00]{1,79})\x00(\x00|\x01)\x00([^\x00]*)(.)[^\x00]*\x00(.*)$/Ds',
					$buf, $items )
				) {
					/* $items[1] = text chunk name, $items[2] = compressed flag,
					 * $items[3] = lang code (or ""), $items[4]= compression type.
					 * $items[5] = content
					 */

					// Theoretically should be case-sensitive, but in practise...
					$items[1] = strtolower( $items[1] );
					if ( !isset( self::$textChunks[$items[1]] ) ) {
						// Only extract textual chunks on our list.
						continue;
					}

					$items[3] = strtolower( $items[3] );
					if ( $items[3] == '' ) {
						// if no lang specified use x-default like in xmp.
						$items[3] = 'x-default';
					}

					// if compressed
					if ( $items[2] === "\x01" ) {
						if ( function_exists( 'gzuncompress' ) && $items[4] === "\x00" ) {
							AtEase::suppressWarnings();
							$items[5] = gzuncompress( $items[5] );
							AtEase::restoreWarnings();

							if ( $items[5] === false ) {
								// decompression failed
								wfDebug( __METHOD__ . ' Error decompressing iTxt chunk - ' . $items[1] );
								continue;
							}
						} else {
							wfDebug( __METHOD__ . ' Skipping compressed png iTXt chunk due to lack of zlib,'
								. " or potentially invalid compression method" );
							continue;
						}
					}
					$finalKeyword = self::$textChunks[$items[1]];
					$text[$finalKeyword][$items[3]] = $items[5];
					$text[$finalKeyword]['_type'] = 'lang';
				} else {
					// Error reading iTXt chunk
					wfDebug( __METHOD__ . ": Invalid iTXt chunk" );
				}
			} elseif ( $chunk_type === 'tEXt' ) {
				// In case there is no \x00 which will make explode fail.
				if ( strpos( $buf, "\x00" ) === false ) {
					wfDebug( __METHOD__ . ": Invalid tEXt chunk: no null byte" );
					continue;
				}

				[ $keyword, $content ] = explode( "\x00", $buf, 2 );
				if ( $keyword === '' ) {
					wfDebug( __METHOD__ . ": Empty tEXt keyword" );
					continue;
				}

				// Theoretically should be case-sensitive, but in practise...
				$keyword = strtolower( $keyword );
				if ( !isset( self::$textChunks[$keyword] ) ) {
					// Don't recognize chunk, so skip.
					continue;
				}
				AtEase::suppressWarnings();
				$content = iconv( 'ISO-8859-1', 'UTF-8', $content );
				AtEase::restoreWarnings();

				if ( $content === false ) {
					wfDebug( __METHOD__ . ": Read error (error with iconv)" );
					continue;
				}

				$finalKeyword = self::$textChunks[$keyword];
				$text[$finalKeyword]['x-default'] = $content;
				$text[$finalKeyword]['_type'] = 'lang';
			} elseif ( $chunk_type === 'zTXt' ) {
				if ( function_exists( 'gzuncompress' ) ) {
					// In case there is no \x00 which will make explode fail.
					if ( strpos( $buf, "\x00" ) === false ) {
						wfDebug( __METHOD__ . ": No null byte in zTXt chunk" );
						continue;
					}

					[ $keyword, $postKeyword ] = explode( "\x00", $buf, 2 );
					if ( $keyword === '' || $postKeyword === '' ) {
						wfDebug( __METHOD__ . ": Empty zTXt chunk" );
						continue;
					}
					// Theoretically should be case-sensitive, but in practise...
					$keyword = strtolower( $keyword );

					if ( !isset( self::$textChunks[$keyword] ) ) {
						// Don't recognize chunk, so skip.
						continue;
					}
					$compression = substr( $postKeyword, 0, 1 );
					$content = substr( $postKeyword, 1 );
					if ( $compression !== "\x00" ) {
						wfDebug( __METHOD__ . " Unrecognized compression method in zTXt ($keyword). Skipping." );
						continue;
					}

					AtEase::suppressWarnings();
					$content = gzuncompress( $content );
					AtEase::restoreWarnings();

					if ( $content === false ) {
						// decompression failed
						wfDebug( __METHOD__ . ' Error decompressing zTXt chunk - ' . $keyword );
						continue;
					}

					AtEase::suppressWarnings();
					$content = iconv( 'ISO-8859-1', 'UTF-8', $content );
					AtEase::restoreWarnings();

					if ( $content === false ) {
						wfDebug( __METHOD__ . ": iconv error in zTXt chunk" );
						continue;
					}

					$finalKeyword = self::$textChunks[$keyword];
					$text[$finalKeyword]['x-default'] = $content;
					$text[$finalKeyword]['_type'] = 'lang';
				} else {
					wfDebug( __METHOD__ . " Cannot decompress zTXt chunk due to lack of zlib. Skipping." );
				}
			} elseif ( $chunk_type === 'tIME' ) {
				// last mod timestamp.
				if ( $chunk_size !== 7 ) {
					wfDebug( __METHOD__ . ": tIME wrong size" );
					continue;
				}

				// Note: spec says this should be UTC.
				$t = unpack( "ny/Cm/Cd/Ch/Cmin/Cs", $buf );
				$strTime = sprintf( "%04d%02d%02d%02d%02d%02d",
					$t['y'], $t['m'], $t['d'], $t['h'],
					$t['min'], $t['s'] );

				$exifTime = wfTimestamp( TS_EXIF, $strTime );

				if ( $exifTime ) {
					$text['DateTime'] = $exifTime;
				}
			} elseif ( $chunk_type === 'pHYs' ) {
				// how big pixels are (dots per meter).
				if ( $chunk_size !== 9 ) {
					wfDebug( __METHOD__ . ": pHYs wrong size" );
					continue;
				}

				$dim = unpack( "Nwidth/Nheight/Cunit", $buf );
				if ( $dim['unit'] === 1 ) {
					// Need to check for negative because php
					// doesn't deal with super-large unsigned 32-bit ints well
					if ( $dim['width'] > 0 && $dim['height'] > 0 ) {
						// unit is meters
						// (as opposed to 0 = undefined )
						$text['XResolution'] = $dim['width']
							. '/100';
						$text['YResolution'] = $dim['height']
							. '/100';
						$text['ResolutionUnit'] = 3;
						// 3 = dots per cm (from Exif).
					}
				}
			} elseif ( $chunk_type === "IEND" ) {
				break;
			}
		}
		fclose( $fh );

		if ( $loopCount > 1 ) {
			$duration *= $loopCount;
		}

		if ( isset( $text['DateTimeDigitized'] ) ) {
			// Convert date format from rfc2822 to exif.
			foreach ( $text['DateTimeDigitized'] as $name => &$value ) {
				if ( $name === '_type' ) {
					continue;
				}

				// @todo FIXME: Currently timezones are ignored.
				// possibly should be wfTimestamp's
				// responsibility. (at least for numeric TZ)
				$formatted = wfTimestamp( TS_EXIF, $value );
				if ( $formatted ) {
					// Only change if we could convert the
					// date.
					// The png standard says it should be
					// in rfc2822 format, but not required.
					// In general for the exif stuff we
					// prettify the date if we can, but we
					// display as-is if we cannot or if
					// it is invalid.
					// So do the same here.

					$value = $formatted;
				}
			}
		}

		return [
			'width' => $width,
			'height' => $height,
			'frameCount' => $frameCount,
			'loopCount' => $loopCount,
			'duration' => $duration,
			'text' => $text,
			'bitDepth' => $bitDepth,
			'colorType' => $colorType,
		];
	}

	/**
	 * Read a chunk, checking to make sure its not too big.
	 *
	 * @param resource $fh The file handle
	 * @param int $size Size in bytes.
	 * @throws Exception If too big
	 * @return string The chunk.
	 */
	private static function read( $fh, $size ) {
		if ( $size === 0 ) {
			return '';
		}

		$result = fread( $fh, $size );
		if ( $result === false ) {
			throw new InvalidArgumentException( __METHOD__ . ': read error' );
		}
		if ( strlen( $result ) < $size ) {
			throw new InvalidArgumentException( __METHOD__ . ': unexpected end of file' );
		}
		return $result;
	}
}
