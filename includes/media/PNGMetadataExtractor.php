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

	const VERSION = 1;
	const MAX_CHUNK_SIZE = 3145728; // 3 megabytes

	static function getMetadata( $filename ) {
		self::$pngSig = pack( "C8", 137, 80, 78, 71, 13, 10, 26, 10 );
		self::$crcSize = 4;
		/* based on list at http://owl.phy.queensu.ca/~phil/exiftool/TagNames/PNG.html#TextualData
		 * and http://www.w3.org/TR/PNG/#11keywords
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
		$bitDepth = 0;
		$colorType = 'unknown';

		if ( !$filename ) {
			throw new Exception( __METHOD__ . ": No file name specified" );
		} elseif ( !file_exists( $filename ) || is_dir( $filename ) ) {
			throw new Exception( __METHOD__ . ": File $filename does not exist" );
		}

		$fh = fopen( $filename, 'rb' );

		if ( !$fh ) {
			throw new Exception( __METHOD__ . ": Unable to open file $filename" );
		}

		// Check for the PNG header
		$buf = fread( $fh, 8 );
		if ( $buf != self::$pngSig ) {
			throw new Exception( __METHOD__ . ": Not a valid PNG file; header: $buf" );
		}

		// Read chunks
		while ( !feof( $fh ) ) {
			$buf = fread( $fh, 4 );
			if ( !$buf || strlen( $buf ) < 4 ) {
				throw new Exception( __METHOD__ . ": Read error" );
			}
			$chunk_size = unpack( "N", $buf )[1];

			if ( $chunk_size < 0 ) {
				throw new Exception( __METHOD__ . ": Chunk size too big for unpack" );
			}

			$chunk_type = fread( $fh, 4 );
			if ( !$chunk_type || strlen( $chunk_type ) < 4 ) {
				throw new Exception( __METHOD__ . ": Read error" );
			}

			if ( $chunk_type == "IHDR" ) {
				$buf = self::read( $fh, $chunk_size );
				if ( !$buf || strlen( $buf ) < $chunk_size ) {
					throw new Exception( __METHOD__ . ": Read error" );
				}
				$bitDepth = ord( substr( $buf, 8, 1 ) );
				// Detect the color type in British English as per the spec
				// http://www.w3.org/TR/PNG/#11IHDR
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
			} elseif ( $chunk_type == "acTL" ) {
				$buf = fread( $fh, $chunk_size );
				if ( !$buf || strlen( $buf ) < $chunk_size || $chunk_size < 4 ) {
					throw new Exception( __METHOD__ . ": Read error" );
				}

				$actl = unpack( "Nframes/Nplays", $buf );
				$frameCount = $actl['frames'];
				$loopCount = $actl['plays'];
			} elseif ( $chunk_type == "fcTL" ) {
				$buf = self::read( $fh, $chunk_size );
				if ( !$buf || strlen( $buf ) < $chunk_size ) {
					throw new Exception( __METHOD__ . ": Read error" );
				}
				$buf = substr( $buf, 20 );
				if ( strlen( $buf ) < 4 ) {
					throw new Exception( __METHOD__ . ": Read error" );
				}

				$fctldur = unpack( "ndelay_num/ndelay_den", $buf );
				if ( $fctldur['delay_den'] == 0 ) {
					$fctldur['delay_den'] = 100;
				}
				if ( $fctldur['delay_num'] ) {
					$duration += $fctldur['delay_num'] / $fctldur['delay_den'];
				}
			} elseif ( $chunk_type == "iTXt" ) {
				// Extracts iTXt chunks, uncompressing if necessary.
				$buf = self::read( $fh, $chunk_size );
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
						fseek( $fh, self::$crcSize, SEEK_CUR );
						continue;
					}

					$items[3] = strtolower( $items[3] );
					if ( $items[3] == '' ) {
						// if no lang specified use x-default like in xmp.
						$items[3] = 'x-default';
					}

					// if compressed
					if ( $items[2] == "\x01" ) {
						if ( function_exists( 'gzuncompress' ) && $items[4] === "\x00" ) {
							MediaWiki\suppressWarnings();
							$items[5] = gzuncompress( $items[5] );
							MediaWiki\restoreWarnings();

							if ( $items[5] === false ) {
								// decompression failed
								wfDebug( __METHOD__ . ' Error decompressing iTxt chunk - ' . $items[1] . "\n" );
								fseek( $fh, self::$crcSize, SEEK_CUR );
								continue;
							}
						} else {
							wfDebug( __METHOD__ . ' Skipping compressed png iTXt chunk due to lack of zlib,'
								. " or potentially invalid compression method\n" );
							fseek( $fh, self::$crcSize, SEEK_CUR );
							continue;
						}
					}
					$finalKeyword = self::$textChunks[$items[1]];
					$text[$finalKeyword][$items[3]] = $items[5];
					$text[$finalKeyword]['_type'] = 'lang';
				} else {
					// Error reading iTXt chunk
					throw new Exception( __METHOD__ . ": Read error on iTXt chunk" );
				}
			} elseif ( $chunk_type == 'tEXt' ) {
				$buf = self::read( $fh, $chunk_size );

				// In case there is no \x00 which will make explode fail.
				if ( strpos( $buf, "\x00" ) === false ) {
					throw new Exception( __METHOD__ . ": Read error on tEXt chunk" );
				}

				list( $keyword, $content ) = explode( "\x00", $buf, 2 );
				if ( $keyword === '' || $content === '' ) {
					throw new Exception( __METHOD__ . ": Read error on tEXt chunk" );
				}

				// Theoretically should be case-sensitive, but in practise...
				$keyword = strtolower( $keyword );
				if ( !isset( self::$textChunks[$keyword] ) ) {
					// Don't recognize chunk, so skip.
					fseek( $fh, self::$crcSize, SEEK_CUR );
					continue;
				}
				MediaWiki\suppressWarnings();
				$content = iconv( 'ISO-8859-1', 'UTF-8', $content );
				MediaWiki\restoreWarnings();

				if ( $content === false ) {
					throw new Exception( __METHOD__ . ": Read error (error with iconv)" );
				}

				$finalKeyword = self::$textChunks[$keyword];
				$text[$finalKeyword]['x-default'] = $content;
				$text[$finalKeyword]['_type'] = 'lang';
			} elseif ( $chunk_type == 'zTXt' ) {
				if ( function_exists( 'gzuncompress' ) ) {
					$buf = self::read( $fh, $chunk_size );

					// In case there is no \x00 which will make explode fail.
					if ( strpos( $buf, "\x00" ) === false ) {
						throw new Exception( __METHOD__ . ": Read error on zTXt chunk" );
					}

					list( $keyword, $postKeyword ) = explode( "\x00", $buf, 2 );
					if ( $keyword === '' || $postKeyword === '' ) {
						throw new Exception( __METHOD__ . ": Read error on zTXt chunk" );
					}
					// Theoretically should be case-sensitive, but in practise...
					$keyword = strtolower( $keyword );

					if ( !isset( self::$textChunks[$keyword] ) ) {
						// Don't recognize chunk, so skip.
						fseek( $fh, self::$crcSize, SEEK_CUR );
						continue;
					}
					$compression = substr( $postKeyword, 0, 1 );
					$content = substr( $postKeyword, 1 );
					if ( $compression !== "\x00" ) {
						wfDebug( __METHOD__ . " Unrecognized compression method in zTXt ($keyword). Skipping.\n" );
						fseek( $fh, self::$crcSize, SEEK_CUR );
						continue;
					}

					MediaWiki\suppressWarnings();
					$content = gzuncompress( $content );
					MediaWiki\restoreWarnings();

					if ( $content === false ) {
						// decompression failed
						wfDebug( __METHOD__ . ' Error decompressing zTXt chunk - ' . $keyword . "\n" );
						fseek( $fh, self::$crcSize, SEEK_CUR );
						continue;
					}

					MediaWiki\suppressWarnings();
					$content = iconv( 'ISO-8859-1', 'UTF-8', $content );
					MediaWiki\restoreWarnings();

					if ( $content === false ) {
						throw new Exception( __METHOD__ . ": Read error (error with iconv)" );
					}

					$finalKeyword = self::$textChunks[$keyword];
					$text[$finalKeyword]['x-default'] = $content;
					$text[$finalKeyword]['_type'] = 'lang';
				} else {
					wfDebug( __METHOD__ . " Cannot decompress zTXt chunk due to lack of zlib. Skipping.\n" );
					fseek( $fh, $chunk_size, SEEK_CUR );
				}
			} elseif ( $chunk_type == 'tIME' ) {
				// last mod timestamp.
				if ( $chunk_size !== 7 ) {
					throw new Exception( __METHOD__ . ": tIME wrong size" );
				}
				$buf = self::read( $fh, $chunk_size );
				if ( !$buf || strlen( $buf ) < $chunk_size ) {
					throw new Exception( __METHOD__ . ": Read error" );
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
			} elseif ( $chunk_type == 'pHYs' ) {
				// how big pixels are (dots per meter).
				if ( $chunk_size !== 9 ) {
					throw new Exception( __METHOD__ . ": pHYs wrong size" );
				}

				$buf = self::read( $fh, $chunk_size );
				if ( !$buf || strlen( $buf ) < $chunk_size ) {
					throw new Exception( __METHOD__ . ": Read error" );
				}

				$dim = unpack( "Nwidth/Nheight/Cunit", $buf );
				if ( $dim['unit'] == 1 ) {
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
			} elseif ( $chunk_type == "IEND" ) {
				break;
			} else {
				fseek( $fh, $chunk_size, SEEK_CUR );
			}
			fseek( $fh, self::$crcSize, SEEK_CUR );
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
		if ( $size > self::MAX_CHUNK_SIZE ) {
			throw new Exception( __METHOD__ . ': Chunk size of ' . $size .
				' too big. Max size is: ' . self::MAX_CHUNK_SIZE );
		}

		return fread( $fh, $size );
	}
}
