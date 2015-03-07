<?php
/**
 * GIF frame counter.
 *
 * Originally written in Perl by Steve Sanbeg.
 * Ported to PHP by Andrew Garrett
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
 * GIF frame counter.
 *
 * @ingroup Media
 */
class GIFMetadataExtractor {
	/** @var string */
	private static $gifFrameSep;

	/** @var string */
	private static $gifExtensionSep;

	/** @var string */
	private static $gifTerm;

	const VERSION = 1;

	// Each sub-block is less than or equal to 255 bytes.
	// Most of the time its 255 bytes, except for in XMP
	// blocks, where it's usually between 32-127 bytes each.
	const MAX_SUBBLOCKS = 262144; // 5mb divided by 20.

	/**
	 * @throws Exception
	 * @param string $filename
	 * @return array
	 */
	static function getMetadata( $filename ) {
		self::$gifFrameSep = pack( "C", ord( "," ) );
		self::$gifExtensionSep = pack( "C", ord( "!" ) );
		self::$gifTerm = pack( "C", ord( ";" ) );

		$frameCount = 0;
		$duration = 0.0;
		$isLooped = false;
		$xmp = "";
		$comment = array();

		if ( !$filename ) {
			throw new Exception( "No file name specified" );
		} elseif ( !file_exists( $filename ) || is_dir( $filename ) ) {
			throw new Exception( "File $filename does not exist" );
		}

		$fh = fopen( $filename, 'rb' );

		if ( !$fh ) {
			throw new Exception( "Unable to open file $filename" );
		}

		// Check for the GIF header
		$buf = fread( $fh, 6 );
		if ( !( $buf == 'GIF87a' || $buf == 'GIF89a' ) ) {
			throw new Exception( "Not a valid GIF file; header: $buf" );
		}

		// Skip over width and height.
		fread( $fh, 4 );

		// Read BPP
		$buf = fread( $fh, 1 );
		$bpp = self::decodeBPP( $buf );

		// Skip over background and aspect ratio
		fread( $fh, 2 );

		// Skip over the GCT
		self::readGCT( $fh, $bpp );

		while ( !feof( $fh ) ) {
			$buf = fread( $fh, 1 );

			if ( $buf == self::$gifFrameSep ) {
				// Found a frame
				$frameCount++;

				## Skip bounding box
				fread( $fh, 8 );

				## Read BPP
				$buf = fread( $fh, 1 );
				$bpp = self::decodeBPP( $buf );

				## Read GCT
				self::readGCT( $fh, $bpp );
				fread( $fh, 1 );
				self::skipBlock( $fh );
			} elseif ( $buf == self::$gifExtensionSep ) {
				$buf = fread( $fh, 1 );
				if ( strlen( $buf ) < 1 ) {
					throw new Exception( "Ran out of input" );
				}
				$extension_code = unpack( 'C', $buf );
				$extension_code = $extension_code[1];

				if ( $extension_code == 0xF9 ) {
					// Graphics Control Extension.
					fread( $fh, 1 ); // Block size

					fread( $fh, 1 ); // Transparency, disposal method, user input

					$buf = fread( $fh, 2 ); // Delay, in hundredths of seconds.
					if ( strlen( $buf ) < 2 ) {
						throw new Exception( "Ran out of input" );
					}
					$delay = unpack( 'v', $buf );
					$delay = $delay[1];
					$duration += $delay * 0.01;

					fread( $fh, 1 ); // Transparent colour index

					$term = fread( $fh, 1 ); // Should be a terminator
					if ( strlen( $term ) < 1 ) {
						throw new Exception( "Ran out of input" );
					}
					$term = unpack( 'C', $term );
					$term = $term[1];
					if ( $term != 0 ) {
						throw new Exception( "Malformed Graphics Control Extension block" );
					}
				} elseif ( $extension_code == 0xFE ) {
					// Comment block(s).
					$data = self::readBlock( $fh );
					if ( $data === "" ) {
						throw new Exception( 'Read error, zero-length comment block' );
					}

					// The standard says this should be ASCII, however its unclear if
					// thats true in practise. Check to see if its valid utf-8, if so
					// assume its that, otherwise assume its windows-1252 (iso-8859-1)
					$dataCopy = $data;
					// quickIsNFCVerify has the side effect of replacing any invalid characters
					UtfNormal\Validator::quickIsNFCVerify( $dataCopy );

					if ( $dataCopy !== $data ) {
						wfSuppressWarnings();
						$data = iconv( 'windows-1252', 'UTF-8', $data );
						wfRestoreWarnings();
					}

					$commentCount = count( $comment );
					if ( $commentCount === 0
						|| $comment[$commentCount - 1] !== $data
					) {
						// Some applications repeat the same comment on each
						// frame of an animated GIF image, so if this comment
						// is identical to the last, only extract once.
						$comment[] = $data;
					}
				} elseif ( $extension_code == 0xFF ) {
					// Application extension (Netscape info about the animated gif)
					// or XMP (or theoretically any other type of extension block)
					$blockLength = fread( $fh, 1 );
					if ( strlen( $blockLength ) < 1 ) {
						throw new Exception( "Ran out of input" );
					}
					$blockLength = unpack( 'C', $blockLength );
					$blockLength = $blockLength[1];
					$data = fread( $fh, $blockLength );

					if ( $blockLength != 11 ) {
						wfDebug( __METHOD__ . " GIF application block with wrong length\n" );
						fseek( $fh, -( $blockLength + 1 ), SEEK_CUR );
						self::skipBlock( $fh );
						continue;
					}

					// NETSCAPE2.0 (application name for animated gif)
					if ( $data == 'NETSCAPE2.0' ) {
						$data = fread( $fh, 2 ); // Block length and introduction, should be 03 01

						if ( $data != "\x03\x01" ) {
							throw new Exception( "Expected \x03\x01, got $data" );
						}

						// Unsigned little-endian integer, loop count or zero for "forever"
						$loopData = fread( $fh, 2 );
						if ( strlen( $loopData ) < 2 ) {
							throw new Exception( "Ran out of input" );
						}
						$loopData = unpack( 'v', $loopData );
						$loopCount = $loopData[1];

						if ( $loopCount != 1 ) {
							$isLooped = true;
						}

						// Read out terminator byte
						fread( $fh, 1 );
					} elseif ( $data == 'XMP DataXMP' ) {
						// application name for XMP data.
						// see pg 18 of XMP spec part 3.

						$xmp = self::readBlock( $fh, true );

						if ( substr( $xmp, -257, 3 ) !== "\x01\xFF\xFE"
							|| substr( $xmp, -4 ) !== "\x03\x02\x01\x00"
						) {
							// this is just a sanity check.
							throw new Exception( "XMP does not have magic trailer!" );
						}

						// strip out trailer.
						$xmp = substr( $xmp, 0, -257 );
					} else {
						// unrecognized extension block
						fseek( $fh, -( $blockLength + 1 ), SEEK_CUR );
						self::skipBlock( $fh );
						continue;
					}
				} else {
					self::skipBlock( $fh );
				}
			} elseif ( $buf == self::$gifTerm ) {
				break;
			} else {
				if ( strlen( $buf ) < 1 ) {
					throw new Exception( "Ran out of input" );
				}
				$byte = unpack( 'C', $buf );
				$byte = $byte[1];
				throw new Exception( "At position: " . ftell( $fh ) . ", Unknown byte " . $byte );
			}
		}

		return array(
			'frameCount' => $frameCount,
			'looped' => $isLooped,
			'duration' => $duration,
			'xmp' => $xmp,
			'comment' => $comment,
		);
	}

	/**
	 * @param resource $fh
	 * @param int $bpp
	 * @return void
	 */
	static function readGCT( $fh, $bpp ) {
		if ( $bpp > 0 ) {
			$max = pow( 2, $bpp );
			for ( $i = 1; $i <= $max; ++$i ) {
				fread( $fh, 3 );
			}
		}
	}

	/**
	 * @param string $data
	 * @throws Exception
	 * @return int
	 */
	static function decodeBPP( $data ) {
		if ( strlen( $data ) < 1 ) {
			throw new Exception( "Ran out of input" );
		}
		$buf = unpack( 'C', $data );
		$buf = $buf[1];
		$bpp = ( $buf & 7 ) + 1;
		$buf >>= 7;

		$have_map = $buf & 1;

		return $have_map ? $bpp : 0;
	}

	/**
	 * @param resource $fh
	 * @throws Exception
	 */
	static function skipBlock( $fh ) {
		while ( !feof( $fh ) ) {
			$buf = fread( $fh, 1 );
			if ( strlen( $buf ) < 1 ) {
				throw new Exception( "Ran out of input" );
			}
			$block_len = unpack( 'C', $buf );
			$block_len = $block_len[1];
			if ( $block_len == 0 ) {
				return;
			}
			fread( $fh, $block_len );
		}
	}

	/**
	 * Read a block. In the GIF format, a block is made up of
	 * several sub-blocks. Each sub block starts with one byte
	 * saying how long the sub-block is, followed by the sub-block.
	 * The entire block is terminated by a sub-block of length
	 * 0.
	 * @param resource $fh File handle
	 * @param bool $includeLengths Include the length bytes of the
	 *  sub-blocks in the returned value. Normally this is false,
	 *  except XMP is weird and does a hack where you need to keep
	 *  these length bytes.
	 * @throws Exception
	 * @return string The data.
	 */
	static function readBlock( $fh, $includeLengths = false ) {
		$data = '';
		$subLength = fread( $fh, 1 );
		$blocks = 0;

		while ( $subLength !== "\0" ) {
			$blocks++;
			if ( $blocks > self::MAX_SUBBLOCKS ) {
				throw new Exception( "MAX_SUBBLOCKS exceeded (over $blocks sub-blocks)" );
			}
			if ( feof( $fh ) ) {
				throw new Exception( "Read error: Unexpected EOF." );
			}
			if ( $includeLengths ) {
				$data .= $subLength;
			}

			$data .= fread( $fh, ord( $subLength ) );
			$subLength = fread( $fh, 1 );
		}

		return $data;
	}
}
