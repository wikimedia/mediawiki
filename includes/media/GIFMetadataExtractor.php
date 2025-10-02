<?php
/**
 * GIF frame counter.
 *
 * Originally written in Perl by Steve Sanbeg.
 * Ported to PHP by Andrew Garrett
 * Deliberately not using MWExceptions to avoid external dependencies, encouraging
 * redistribution.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

use Wikimedia\AtEase\AtEase;

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

	public const VERSION = 1;

	// Each sub-block is less than or equal to 255 bytes.
	// Most of the time its 255 bytes, except for in XMP
	// blocks, where it's usually between 32-127 bytes each.
	private const MAX_SUBBLOCKS = 262144; // 5 MiB divided by 20.

	/**
	 * @throws Exception
	 * @param string $filename
	 * @return array
	 */
	public static function getMetadata( $filename ) {
		self::$gifFrameSep = pack( "C", ord( "," ) ); // 2C
		self::$gifExtensionSep = pack( "C", ord( "!" ) ); // 21
		self::$gifTerm = pack( "C", ord( ";" ) ); // 3B

		$frameCount = 0;
		$duration = 0.0;
		$isLooped = false;
		$xmp = "";
		$comment = [];

		if ( !$filename ) {
			throw new InvalidArgumentException( 'No file name specified' );
		}
		if ( !file_exists( $filename ) || is_dir( $filename ) ) {
			throw new InvalidArgumentException( "File $filename does not exist" );
		}

		$fh = fopen( $filename, 'rb' );

		if ( !$fh ) {
			throw new InvalidArgumentException( "Unable to open file $filename" );
		}

		// Check for the GIF header
		$buf = fread( $fh, 6 );
		if ( !( $buf === 'GIF87a' || $buf === 'GIF89a' ) ) {
			throw new InvalidArgumentException( "Not a valid GIF file; header: $buf" );
		}

		// Read width and height.
		$buf = fread( $fh, 2 );
		if ( strlen( $buf ) < 2 ) {
			throw new InvalidArgumentException( "Not a valid GIF file; Unable to read width." );
		}
		$width = unpack( 'v', $buf )[1];
		$buf = fread( $fh, 2 );
		if ( strlen( $buf ) < 2 ) {
			throw new InvalidArgumentException( "Not a valid GIF file; Unable to read height." );
		}
		$height = unpack( 'v', $buf )[1];

		// Read BPP
		$buf = fread( $fh, 1 );
		[ $bpp, $have_map ] = self::decodeBPP( $buf );

		// Skip over background and aspect ratio
		// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
		fread( $fh, 2 );

		// Skip over the GCT
		if ( $have_map ) {
			self::readGCT( $fh, $bpp );
		}

		while ( !feof( $fh ) ) {
			$buf = fread( $fh, 1 );

			if ( $buf === self::$gifFrameSep ) {
				// Found a frame
				$frameCount++;

				# # Skip bounding box
				// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
				fread( $fh, 8 );

				# # Read BPP
				$buf = fread( $fh, 1 );
				[ $bpp, $have_map ] = self::decodeBPP( $buf );

				# # Read GCT
				if ( $have_map ) {
					self::readGCT( $fh, $bpp );
				}
				// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
				fread( $fh, 1 );
				self::skipBlock( $fh );
			} elseif ( $buf === self::$gifExtensionSep ) {
				$buf = fread( $fh, 1 );
				if ( strlen( $buf ) < 1 ) {
					throw new InvalidArgumentException(
						"Not a valid GIF file; Unable to read graphics control extension."
					);
				}
				$extension_code = unpack( 'C', $buf )[1];

				if ( $extension_code === 0xF9 ) {
					// Graphics Control Extension.
					// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
					fread( $fh, 1 ); // Block size

					// @phan-suppress-next-next-line PhanPluginUseReturnValueInternalKnown
					// @phan-suppress-next-line PhanPluginDuplicateAdjacentStatement
					fread( $fh, 1 ); // Transparency, disposal method, user input

					$buf = fread( $fh, 2 ); // Delay, in hundredths of seconds.
					if ( strlen( $buf ) < 2 ) {
						throw new InvalidArgumentException( "Not a valid GIF file; Unable to read delay" );
					}
					$delay = unpack( 'v', $buf )[1];
					$duration += $delay * 0.01;

					// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
					fread( $fh, 1 ); // Transparent colour index

					$term = fread( $fh, 1 ); // Should be a terminator
					if ( strlen( $term ) < 1 ) {
						throw new InvalidArgumentException( "Not a valid GIF file; Unable to read terminator byte" );
					}
					$term = unpack( 'C', $term )[1];
					if ( $term != 0 ) {
						throw new InvalidArgumentException( "Malformed Graphics Control Extension block" );
					}
				} elseif ( $extension_code === 0xFE ) {
					// Comment block(s).
					$data = self::readBlock( $fh );
					if ( $data === "" ) {
						throw new InvalidArgumentException( 'Read error, zero-length comment block' );
					}

					// The standard says this should be ASCII, however its unclear if
					// thats true in practise. Check to see if its valid utf-8, if so
					// assume its that, otherwise assume its windows-1252 (iso-8859-1)
					$dataCopy = $data;
					// quickIsNFCVerify has the side effect of replacing any invalid characters
					UtfNormal\Validator::quickIsNFCVerify( $dataCopy );

					if ( $dataCopy !== $data ) {
						AtEase::suppressWarnings();
						$data = iconv( 'windows-1252', 'UTF-8', $data );
						AtEase::restoreWarnings();
					}

					$commentCount = count( $comment );
					if ( $commentCount === 0
						// @phan-suppress-next-line PhanTypeInvalidDimOffset
						|| $comment[$commentCount - 1] !== $data
					) {
						// Some applications repeat the same comment on each
						// frame of an animated GIF image, so if this comment
						// is identical to the last, only extract once.
						$comment[] = $data;
					}
				} elseif ( $extension_code === 0xFF ) {
					// Application extension (Netscape info about the animated gif)
					// or XMP (or theoretically any other type of extension block)
					$blockLength = fread( $fh, 1 );
					if ( strlen( $blockLength ) < 1 ) {
						throw new InvalidArgumentException( "Not a valid GIF file; Unable to read block length" );
					}
					$blockLength = unpack( 'C', $blockLength )[1];
					$data = fread( $fh, $blockLength );

					if ( $blockLength !== 11 ) {
						wfDebug( __METHOD__ . " GIF application block with wrong length" );
						fseek( $fh, -( $blockLength + 1 ), SEEK_CUR );
						self::skipBlock( $fh );
						continue;
					}

					// NETSCAPE2.0 (application name for animated gif)
					if ( $data === 'NETSCAPE2.0' ) {
						$data = fread( $fh, 2 ); // Block length and introduction, should be 03 01

						if ( $data !== "\x03\x01" ) {
							throw new InvalidArgumentException( "Expected \x03\x01, got $data" );
						}

						// Unsigned little-endian integer, loop count or zero for "forever"
						$loopData = fread( $fh, 2 );
						if ( strlen( $loopData ) < 2 ) {
							throw new InvalidArgumentException( "Not a valid GIF file; Unable to read loop count" );
						}
						$loopCount = unpack( 'v', $loopData )[1];

						if ( $loopCount !== 1 ) {
							$isLooped = true;
						}

						// Read out terminator byte
						// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
						fread( $fh, 1 );
					} elseif ( $data === 'XMP DataXMP' ) {
						// application name for XMP data.
						// see pg 18 of XMP spec part 3.

						$xmp = self::readBlock( $fh, true );

						if ( substr( $xmp, -257, 3 ) !== "\x01\xFF\xFE"
							|| substr( $xmp, -4 ) !== "\x03\x02\x01\x00"
						) {
							throw new InvalidArgumentException( "XMP does not have magic trailer!" );
						}

						// strip out trailer.
						$xmp = substr( $xmp, 0, -257 );
					} else {
						// unrecognized extension block
						fseek( $fh, -( $blockLength + 1 ), SEEK_CUR );
						self::skipBlock( $fh );
					}
				} else {
					self::skipBlock( $fh );
				}
			} elseif ( $buf === self::$gifTerm ) {
				break;
			} else {
				if ( strlen( $buf ) < 1 ) {
					throw new InvalidArgumentException( "Not a valid GIF file; Unable to read unknown byte." );
				}
				$byte = unpack( 'C', $buf )[1];
				throw new InvalidArgumentException( "At position: " . ftell( $fh ) . ", Unknown byte " . $byte );
			}
		}

		return [
			'frameCount' => $frameCount,
			'looped' => $isLooped,
			'duration' => $duration,
			'xmp' => $xmp,
			'comment' => $comment,
			'width' => $width,
			'height' => $height,
			'bits' => $bpp,
		];
	}

	/**
	 * @param resource $fh
	 * @param int $bpp
	 * @return void
	 */
	private static function readGCT( $fh, $bpp ) {
		$max = 2 ** $bpp;
		for ( $i = 1; $i <= $max; ++$i ) {
			// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
			fread( $fh, 3 );
		}
	}

	/**
	 * @param string $data
	 * @throws Exception
	 * @return array [ int bits per channel, bool have GCT ]
	 */
	private static function decodeBPP( $data ) {
		if ( strlen( $data ) < 1 ) {
			throw new InvalidArgumentException( "Not a valid GIF file; Unable to read bits per channel." );
		}
		$buf = unpack( 'C', $data )[1];
		$bpp = ( $buf & 7 ) + 1;
		$buf >>= 7;

		$have_map = $buf & 1;

		return [ $bpp, $have_map ];
	}

	/**
	 * @param resource $fh
	 * @throws Exception
	 */
	private static function skipBlock( $fh ) {
		while ( !feof( $fh ) ) {
			$buf = fread( $fh, 1 );
			if ( strlen( $buf ) < 1 ) {
				throw new InvalidArgumentException( "Not a valid GIF file; Unable to read block length." );
			}
			$block_len = unpack( 'C', $buf )[1];
			if ( $block_len == 0 ) {
				return;
			}
			// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
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
	private static function readBlock( $fh, $includeLengths = false ) {
		$data = '';
		$subLength = fread( $fh, 1 );
		$blocks = 0;

		while ( $subLength !== "\0" ) {
			$blocks++;
			if ( $blocks > self::MAX_SUBBLOCKS ) {
				throw new InvalidArgumentException( "MAX_SUBBLOCKS exceeded (over $blocks sub-blocks)" );
			}
			if ( feof( $fh ) ) {
				throw new InvalidArgumentException( "Read error: Unexpected EOF." );
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
