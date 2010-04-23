<?php
/**
 * @file
 * @ingroup Media
 */

/**
 * @ingroup Media
 */
class BitmapHandler extends ImageHandler {
	function normaliseParams( $image, &$params ) {
		global $wgMaxImageArea;
		if ( !parent::normaliseParams( $image, $params ) ) {
			return false;
		}

		$mimeType = $image->getMimeType();
		$srcWidth = $image->getWidth( $params['page'] );
		$srcHeight = $image->getHeight( $params['page'] );

		# Don't make an image bigger than the source
		$params['physicalWidth'] = $params['width'];
		$params['physicalHeight'] = $params['height'];

		if ( $params['physicalWidth'] >= $srcWidth ) {
			$params['physicalWidth'] = $srcWidth;
			$params['physicalHeight'] = $srcHeight;
			# Skip scaling limit checks if no scaling is required
			if( !$image->mustRender() )
				return true;
		}

		# Don't thumbnail an image so big that it will fill hard drives and send servers into swap
		# JPEG has the handy property of allowing thumbnailing without full decompression, so we make
		# an exception for it.
		if ( $mimeType !== 'image/jpeg' &&
			$srcWidth * $srcHeight > $wgMaxImageArea )
		{
			return false;
		}

		return true;
	}
	
	
	// Function that returns the number of pixels to be thumbnailed.
	// Intended for animated GIFs to multiply by the number of frames.
	function getImageArea( $image, $width, $height ) {
		return $width * $height;
	}

	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		global $wgUseImageMagick, $wgImageMagickConvertCommand, $wgImageMagickTempDir;
		global $wgCustomConvertCommand, $wgUseImageResize;
		global $wgSharpenParameter, $wgSharpenReductionThreshold;
		global $wgMaxAnimatedGifArea;

		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		$physicalWidth = $params['physicalWidth'];
		$physicalHeight = $params['physicalHeight'];
		$clientWidth = $params['width'];
		$clientHeight = $params['height'];
		$comment = isset( $params['descriptionUrl'] ) ? "File source: ". $params['descriptionUrl'] : '';
		$srcWidth = $image->getWidth();
		$srcHeight = $image->getHeight();
		$mimeType = $image->getMimeType();
		$srcPath = $image->getPath();
		$retval = 0;
		wfDebug( __METHOD__.": creating {$physicalWidth}x{$physicalHeight} thumbnail at $dstPath\n" );

		if ( !$image->mustRender() && $physicalWidth == $srcWidth && $physicalHeight == $srcHeight ) {
			# normaliseParams (or the user) wants us to return the unscaled image
			wfDebug( __METHOD__.": returning unscaled image\n" );
			return new ThumbnailImage( $image, $image->getURL(), $clientWidth, $clientHeight, $srcPath );
		}

		if ( !$dstPath ) {
			// No output path available, client side scaling only
			$scaler = 'client';
		} elseif( !$wgUseImageResize ) {
			$scaler = 'client';
		} elseif ( $wgUseImageMagick ) {
			$scaler = 'im';
		} elseif ( $wgCustomConvertCommand ) {
			$scaler = 'custom';
		} elseif ( function_exists( 'imagecreatetruecolor' ) ) {
			$scaler = 'gd';
		} else {
			$scaler = 'client';
		}
		wfDebug( __METHOD__.": scaler $scaler\n" );

		if ( $scaler == 'client' ) {
			# Client-side image scaling, use the source URL
			# Using the destination URL in a TRANSFORM_LATER request would be incorrect
			return new ThumbnailImage( $image, $image->getURL(), $clientWidth, $clientHeight, $srcPath );
		}

		if ( $flags & self::TRANSFORM_LATER ) {
			wfDebug( __METHOD__.": Transforming later per flags.\n" );
			return new ThumbnailImage( $image, $dstUrl, $clientWidth, $clientHeight, $dstPath );
		}

		if ( !wfMkdirParents( dirname( $dstPath ) ) ) {
			wfDebug( __METHOD__.": Unable to create thumbnail destination directory, falling back to client scaling\n" );
			return new ThumbnailImage( $image, $image->getURL(), $clientWidth, $clientHeight, $srcPath );
		}

		if ( $scaler == 'im' ) {
			# use ImageMagick

			$quality = '';
			$sharpen = '';
			$scene = false;
			$animation = '';
			if ( $mimeType == 'image/jpeg' ) {
				$quality = "-quality 80"; // 80%
				# Sharpening, see bug 6193
				if ( ( $physicalWidth + $physicalHeight ) / ( $srcWidth + $srcHeight ) < $wgSharpenReductionThreshold ) {
					$sharpen = "-sharpen " . wfEscapeShellArg( $wgSharpenParameter );
				}
			} elseif ( $mimeType == 'image/png' ) {
				$quality = "-quality 95"; // zlib 9, adaptive filtering
			} elseif( $mimeType == 'image/gif' ) {
				if( $this->getImageArea( $image, $srcWidth, $srcHeight ) > $wgMaxAnimatedGifArea ) {
					// Extract initial frame only; we're so big it'll
					// be a total drag. :P
					$scene = 0;
				} else {
					// Coalesce is needed to scale animated GIFs properly (bug 1017).
					$animation = ' -coalesce ';
				}
			}

			if ( strval( $wgImageMagickTempDir ) !== '' ) {
				$tempEnv = 'MAGICK_TMPDIR=' . wfEscapeShellArg( $wgImageMagickTempDir ) . ' ';
			} else {
				$tempEnv = '';
			}

			# Specify white background color, will be used for transparent images
			# in Internet Explorer/Windows instead of default black.

			# Note, we specify "-size {$physicalWidth}" and NOT "-size {$physicalWidth}x{$physicalHeight}".
			# It seems that ImageMagick has a bug wherein it produces thumbnails of
			# the wrong size in the second case.

			$cmd  = 
				$tempEnv .
				wfEscapeShellArg( $wgImageMagickConvertCommand ) .
				" {$quality} -background white -size {$physicalWidth} ".
				wfEscapeShellArg( $this->escapeMagickInput( $srcPath, $scene ) ) .
				$animation .
				// For the -resize option a "!" is needed to force exact size,
				// or ImageMagick may decide your ratio is wrong and slice off
				// a pixel.
				" -thumbnail " . wfEscapeShellArg( "{$physicalWidth}x{$physicalHeight}!" ) .
				// Add the source url as a comment to the thumb.	
				" -set comment " . wfEscapeShellArg( $this->escapeMagickProperty( $comment ) ) .
				" -depth 8 $sharpen " .
				wfEscapeShellArg( $this->escapeMagickOutput( $dstPath ) ) . " 2>&1";
			wfDebug( __METHOD__.": running ImageMagick: $cmd\n" );
			wfProfileIn( 'convert' );
			$err = wfShellExec( $cmd, $retval );
			wfProfileOut( 'convert' );
		} elseif( $scaler == 'custom' ) {
			# Use a custom convert command
			# Variables: %s %d %w %h
			$src = wfEscapeShellArg( $srcPath );
			$dst = wfEscapeShellArg( $dstPath );
			$cmd = $wgCustomConvertCommand;
			$cmd = str_replace( '%s', $src, str_replace( '%d', $dst, $cmd ) ); # Filenames
			$cmd = str_replace( '%h', $physicalHeight, str_replace( '%w', $physicalWidth, $cmd ) ); # Size
			wfDebug( __METHOD__.": Running custom convert command $cmd\n" );
			wfProfileIn( 'convert' );
			$err = wfShellExec( $cmd, $retval );
			wfProfileOut( 'convert' );
		} else /* $scaler == 'gd' */ {
			# Use PHP's builtin GD library functions.
			#
			# First find out what kind of file this is, and select the correct
			# input routine for this.

			$typemap = array(
				'image/gif'          => array( 'imagecreatefromgif',  'palette',   'imagegif'  ),
				'image/jpeg'         => array( 'imagecreatefromjpeg', 'truecolor', array( __CLASS__, 'imageJpegWrapper' ) ),
				'image/png'          => array( 'imagecreatefrompng',  'bits',      'imagepng'  ),
				'image/vnd.wap.wbmp' => array( 'imagecreatefromwbmp', 'palette',   'imagewbmp'  ),
				'image/xbm'          => array( 'imagecreatefromxbm',  'palette',   'imagexbm'  ),
			);
			if( !isset( $typemap[$mimeType] ) ) {
				$err = 'Image type not supported';
				wfDebug( "$err\n" );
				$errMsg = wfMsg ( 'thumbnail_image-type' );
				return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight, $errMsg );
			}
			list( $loader, $colorStyle, $saveType ) = $typemap[$mimeType];

			if( !function_exists( $loader ) ) {
				$err = "Incomplete GD library configuration: missing function $loader";
				wfDebug( "$err\n" );
				$errMsg = wfMsg ( 'thumbnail_gd-library', $loader );
				return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight, $errMsg );
			}

			if ( !file_exists( $srcPath ) ) {
				$err = "File seems to be missing: $srcPath";
				wfDebug( "$err\n" );
				$errMsg = wfMsg ( 'thumbnail_image-missing', $srcPath );
				return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight, $errMsg );
			}

			$src_image = call_user_func( $loader, $srcPath );
			$dst_image = imagecreatetruecolor( $physicalWidth, $physicalHeight );

			// Initialise the destination image to transparent instead of
			// the default solid black, to support PNG and GIF transparency nicely
			$background = imagecolorallocate( $dst_image, 0, 0, 0 );
			imagecolortransparent( $dst_image, $background );
			imagealphablending( $dst_image, false );

			if( $colorStyle == 'palette' ) {
				// Don't resample for paletted GIF images.
				// It may just uglify them, and completely breaks transparency.
				imagecopyresized( $dst_image, $src_image,
					0,0,0,0,
					$physicalWidth, $physicalHeight, imagesx( $src_image ), imagesy( $src_image ) );
			} else {
				imagecopyresampled( $dst_image, $src_image,
					0,0,0,0,
					$physicalWidth, $physicalHeight, imagesx( $src_image ), imagesy( $src_image ) );
			}

			imagesavealpha( $dst_image, true );

			call_user_func( $saveType, $dst_image, $dstPath );
			imagedestroy( $dst_image );
			imagedestroy( $src_image );
			$retval = 0;
		}

		$removed = $this->removeBadFile( $dstPath, $retval );
		if ( $retval != 0 || $removed ) {
			wfDebugLog( 'thumbnail',
				sprintf( 'thumbnail failed on %s: error %d "%s" from "%s"',
					wfHostname(), $retval, trim($err), $cmd ) );
			return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight, $err );
		} else {
			return new ThumbnailImage( $image, $dstUrl, $clientWidth, $clientHeight, $dstPath );
		}
	}

	/**
	 * Escape a string for ImageMagick's property input (e.g. -set -comment)
	 * See InterpretImageProperties() in magick/property.c
	 */
	function escapeMagickProperty( $s ) {
		// Double the backslashes
		$s = str_replace( '\\', '\\\\', $s );
		// Double the percents
		$s = str_replace( '%', '%%', $s );
		// Escape initial - or @
		if ( strlen( $s ) > 0 && ( $s[0] === '-' || $s[0] === '@' ) ) {
			$s = '\\' . $s;
		}
		return $s;
	}

	/**
	 * Escape a string for ImageMagick's input filenames. See ExpandFilenames() 
	 * and GetPathComponent() in magick/utility.c.
	 *
	 * This won't work with an initial ~ or @, so input files should be prefixed
	 * with the directory name. 
	 *
	 * Glob character unescaping is broken in ImageMagick before 6.6.1-5, but
	 * it's broken in a way that doesn't involve trying to convert every file 
	 * in a directory, so we're better off escaping and waiting for the bugfix
	 * to filter down to users.
	 *
	 * @param $path string The file path
	 * @param $scene string The scene specification, or false if there is none
	 */
	function escapeMagickInput( $path, $scene = false ) {
		# Die on initial metacharacters (caller should prepend path)
		$firstChar = substr( $path, 0, 1 );
		if ( $firstChar === '~' || $firstChar === '@' ) {
			throw new MWException( __METHOD__.': cannot escape this path name' );
		}

		# Escape glob chars
		$path = preg_replace( '/[*?\[\]{}]/', '\\\\\0', $path );

		return $this->escapeMagickPath( $path, $scene );
	}

	/**
	 * Escape a string for ImageMagick's output filename. See 
	 * InterpretImageFilename() in magick/image.c.
	 */
	function escapeMagickOutput( $path, $scene = false ) {
		$path = str_replace( '%', '%%', $path );
		return $this->escapeMagickPath( $path, $scene );
	}

	/**
	 * Armour a string against ImageMagick's GetPathComponent(). This is a 
	 * helper function for escapeMagickInput() and escapeMagickOutput().
	 *
	 * @param $path string The file path
	 * @param $scene string The scene specification, or false if there is none
	 */
	protected function escapeMagickPath( $path, $scene = false ) {
		# Die on format specifiers (other than drive letters). The regex is
		# meant to match all the formats you get from "convert -list format"
		if ( preg_match( '/^([a-zA-Z0-9-]+):/', $path, $m ) ) {
			if ( wfIsWindows() && is_dir( $m[0] ) ) {
				// OK, it's a drive letter
				// ImageMagick has a similar exception, see IsMagickConflict()
			} else {
				throw new MWException( __METHOD__.': unexpected colon character in path name' );
			}
		}

		# If there are square brackets, add a do-nothing scene specification 
		# to force a literal interpretation
		if ( $scene === false ) {
			if ( strpos( $path, '[' ) !== false ) {
				$path .= '[0--1]';
			}
		} else {
			$path .= "[$scene]";
		}
		return $path;
	}

	static function imageJpegWrapper( $dst_image, $thumbPath ) {
		imageinterlace( $dst_image );
		imagejpeg( $dst_image, $thumbPath, 95 );
	}


	function getMetadata( $image, $filename ) {
		global $wgShowEXIF;
		if( $wgShowEXIF && file_exists( $filename ) ) {
			$exif = new Exif( $filename );
			$data = $exif->getFilteredData();
			if ( $data ) {
				$data['MEDIAWIKI_EXIF_VERSION'] = Exif::version();
				return serialize( $data );
			} else {
				return '0';
			}
		} else {
			return '';
		}
	}

	function getMetadataType( $image ) {
		return 'exif';
	}

	function isMetadataValid( $image, $metadata ) {
		global $wgShowEXIF;
		if ( !$wgShowEXIF ) {
			# Metadata disabled and so an empty field is expected
			return true;
		}
		if ( $metadata === '0' ) {
			# Special value indicating that there is no EXIF data in the file
			return true;
		}
		$exif = @unserialize( $metadata );
		if ( !isset( $exif['MEDIAWIKI_EXIF_VERSION'] ) ||
			$exif['MEDIAWIKI_EXIF_VERSION'] != Exif::version() )
		{
			# Wrong version
			wfDebug( __METHOD__.": wrong version\n" );
			return false;
		}
		return true;
	}

	/**
	 * Get a list of EXIF metadata items which should be displayed when
	 * the metadata table is collapsed.
	 *
	 * @return array of strings
	 * @access private
	 */
	function visibleMetadataFields() {
		$fields = array();
		$lines = explode( "\n", wfMsgForContent( 'metadata-fields' ) );
		foreach( $lines as $line ) {
			$matches = array();
			if( preg_match( '/^\\*\s*(.*?)\s*$/', $line, $matches ) ) {
				$fields[] = $matches[1];
			}
		}
		$fields = array_map( 'strtolower', $fields );
		return $fields;
	}

	function formatMetadata( $image ) {
		$result = array(
			'visible' => array(),
			'collapsed' => array()
		);
		$metadata = $image->getMetadata();
		if ( !$metadata ) {
			return false;
		}
		$exif = unserialize( $metadata );
		if ( !$exif ) {
			return false;
		}
		unset( $exif['MEDIAWIKI_EXIF_VERSION'] );
		$format = new FormatExif( $exif );

		$formatted = $format->getFormattedData();
		// Sort fields into visible and collapsed
		$visibleFields = $this->visibleMetadataFields();
		foreach ( $formatted as $name => $value ) {
			$tag = strtolower( $name );
			self::addMeta( $result,
				in_array( $tag, $visibleFields ) ? 'visible' : 'collapsed',
				'exif',
				$tag,
				$value
			);
		}
		return $result;
	}
}
