<?php
/**
 * Generic handler for bitmap images
 *
 * @file
 * @ingroup Media
 */

/**
 * Generic handler for bitmap images
 *
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
			if ( !$image->mustRender() )
				return true;
		}

		# Don't thumbnail an image so big that it will fill hard drives and send servers into swap
		# JPEG has the handy property of allowing thumbnailing without full decompression, so we make
		# an exception for it.
		# FIXME: This actually only applies to ImageMagick
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
		global $wgUseImageMagick;
		global $wgCustomConvertCommand, $wgUseImageResize;

		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		# Create a parameter array to pass to the scaler
		$scalerParams = array(
			# The size to which the image will be resized
			'physicalWidth' => $params['physicalWidth'],
			'physicalHeight' => $params['physicalHeight'],
			'physicalDimensions' => "{$params['physicalWidth']}x{$params['physicalHeight']}",
			# The size of the image on the page
			'clientWidth' => $params['width'],
			'clientHeight' => $params['height'],
			# Comment as will be added to the EXIF of the thumbnail
			'comment' => isset( $params['descriptionUrl'] ) ?
				"File source: {$params['descriptionUrl']}" : '',
			# Properties of the original image
			'srcWidth' => $image->getWidth(),
			'srcHeight' => $image->getHeight(),
			'mimeType' => $image->getMimeType(),
			'srcPath' => $image->getPath(),
			'dstPath' => $dstPath,
		);

		wfDebug( __METHOD__ . ": creating {$scalerParams['physicalDimensions']} thumbnail at $dstPath\n" );

		if ( !$image->mustRender() &&
				$scalerParams['physicalWidth'] == $scalerParams['srcWidth']
				&& $scalerParams['physicalHeight'] == $scalerParams['srcHeight'] ) {

			# normaliseParams (or the user) wants us to return the unscaled image
			wfDebug( __METHOD__ . ": returning unscaled image\n" );
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		# Determine scaler type
		if ( !$dstPath ) {
			# No output path available, client side scaling only
			$scaler = 'client';
		} elseif ( !$wgUseImageResize ) {
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
		wfDebug( __METHOD__ . ": scaler $scaler\n" );

		if ( $scaler == 'client' ) {
			# Client-side image scaling, use the source URL
			# Using the destination URL in a TRANSFORM_LATER request would be incorrect
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		if ( $flags & self::TRANSFORM_LATER ) {
			wfDebug( __METHOD__ . ": Transforming later per flags.\n" );
			return new ThumbnailImage( $image, $dstUrl, $scalerParams['clientWidth'],
				$scalerParams['clientHeight'], $dstPath );
		}

		# Try to make a target path for the thumbnail
		if ( !wfMkdirParents( dirname( $dstPath ) ) ) {
			wfDebug( __METHOD__ . ": Unable to create thumbnail destination directory, falling back to client scaling\n" );
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		switch ( $scaler ) {
			case 'im':
				$err = $this->transformImageMagick( $image, $scalerParams );
				break;
			case 'custom':
				$err = $this->transformCustom( $image, $scalerParams );
				break;
			case 'gd':
			default:
				$err = $this->transformGd( $image, $scalerParams );
				break;
		}

		# Remove the file if a zero-byte thumbnail was created, or if there was an error
		$removed = $this->removeBadFile( $dstPath, (bool)$err );
		if ( $err ) {
			# transform returned MediaTransforError
			return $err;
		} elseif ( $removed ) {
			# Thumbnail was zero-byte and had to be removed
			return new MediaTransformError( 'thumbnail_error',
				$scalerParams['clientWidth'], $scalerParams['clientHeight'] );
		} else {
			return new ThumbnailImage( $image, $dstUrl, $scalerParams['clientWidth'],
				$scalerParams['clientHeight'], $dstPath );
		}
	}

	/**
	 * Get a ThumbnailImage that respresents an image that will be scaled
	 * client side
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $params array Array with scaler params
	 * @return ThumbnailImage
	 */
	protected function getClientScalingThumbnailImage( $image, $params ) {
		return new ThumbnailImage( $image, $image->getURL(),
				$params['clientWidth'], $params['clientHeight'], $params['srcPath'] );
	}

	/**
	 * Transform an image using ImageMagick
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $params array Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occured, false (=no error) otherwise
	 */
	protected function transformImageMagick( $image, $params ) {
		# use ImageMagick
		global $wgSharpenReductionThreshold, $wgSharpenParameter,
			$wgMaxAnimatedGifArea,
			$wgImageMagickTempDir, $wgImageMagickConvertCommand;

		$quality = '';
		$sharpen = '';
		$scene = false;
		$animation_pre = '';
		$animation_post = '';
		$decoderHint = '';
		if ( $params['mimeType'] == 'image/jpeg' ) {
			$quality = "-quality 80"; // 80%
			# Sharpening, see bug 6193
			if ( ( $params['physicalWidth'] + $params['physicalHeight'] )
					/ ( $params['srcWidth'] + $params['srcHeight'] )
					< $wgSharpenReductionThreshold ) {
				$sharpen = "-sharpen " . wfEscapeShellArg( $wgSharpenParameter );
			}
			// JPEG decoder hint to reduce memory, available since IM 6.5.6-2
			$decoderHint = "-define jpeg:size={$params['physicalDimensions']}";

		} elseif ( $params['mimeType'] == 'image/png' ) {
			$quality = "-quality 95"; // zlib 9, adaptive filtering

		} elseif ( $params['mimeType'] == 'image/gif' ) {
			if ( $this->getImageArea( $image, $params['srcWidth'],
					$params['srcHeight'] ) > $wgMaxAnimatedGifArea ) {
				// Extract initial frame only; we're so big it'll
				// be a total drag. :P
				$scene = 0;

			} elseif ( $this->isAnimatedImage( $image ) ) {
				// Coalesce is needed to scale animated GIFs properly (bug 1017).
				$animation_pre = '-coalesce';
				// We optimize the output, but -optimize is broken,
				// use optimizeTransparency instead (bug 11822)
				if ( version_compare( $this->getMagickVersion(), "6.3.5" ) >= 0 ) {
					$animation_post = '-fuzz 5% -layers optimizeTransparency +map';
				}
			}
		}

		// Use one thread only, to avoid deadlock bugs on OOM
		$env = array( 'OMP_NUM_THREADS' => 1 );
		if ( strval( $wgImageMagickTempDir ) !== '' ) {
			$env['MAGICK_TMPDIR'] = $wgImageMagickTempDir;
		}

		$cmd  =
			wfEscapeShellArg( $wgImageMagickConvertCommand ) .
			// Specify white background color, will be used for transparent images
			// in Internet Explorer/Windows instead of default black.
			" {$quality} -background white" .
			" {$decoderHint} " .
			wfEscapeShellArg( $this->escapeMagickInput( $params['srcPath'], $scene ) ) .
			" {$animation_pre}" .
			// For the -thumbnail option a "!" is needed to force exact size,
			// or ImageMagick may decide your ratio is wrong and slice off
			// a pixel.
			" -thumbnail " . wfEscapeShellArg( "{$params['physicalDimensions']}!" ) .
			// Add the source url as a comment to the thumb.
			" -set comment " . wfEscapeShellArg( $this->escapeMagickProperty( $params['comment'] ) ) .
			" -depth 8 $sharpen" .
			" {$animation_post} " .
			wfEscapeShellArg( $this->escapeMagickOutput( $params['dstPath'] ) ) . " 2>&1";

		wfDebug( __METHOD__ . ": running ImageMagick: $cmd\n" );
		wfProfileIn( 'convert' );
		$retval = 0;
		$err = wfShellExec( $cmd, $retval, $env );
		wfProfileOut( 'convert' );

		if ( $retval !== 0 ) {
			$this->logErrorForExternalProcess( $retval, $err, $cmd );
			return $this->getMediaTransformError( $params, $err );
		}

		return false; # No error
	}

	/**
	 * Transform an image using a custom command
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $params array Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occured, false (=no error) otherwise
	 */
	protected function transformCustom( $image, $params ) {
		# Use a custom convert command
		global $wgCustomConvertCommand;

		# Variables: %s %d %w %h
		$src = wfEscapeShellArg( $params['srcPath'] );
		$dst = wfEscapeShellArg( $params['dstPath'] );
		$cmd = $wgCustomConvertCommand;
		$cmd = str_replace( '%s', $src, str_replace( '%d', $dst, $cmd ) ); # Filenames
		$cmd = str_replace( '%h', $params['physicalHeight'],
			str_replace( '%w', $params['physicalWidth'], $cmd ) ); # Size
		wfDebug( __METHOD__ . ": Running custom convert command $cmd\n" );
		wfProfileIn( 'convert' );
		$retval = 0;
		$err = wfShellExec( $cmd, $retval );
		wfProfileOut( 'convert' );

		if ( $retval !== 0 ) {
			$this->logErrorForExternalProcess( $retval, $err, $cmd );
			return $this->getMediaTransformError( $params, $err );
		}
		return false; # No error
	}

	/**
	 * Log an error that occured in an external process
	 *
	 * @param $retval int
	 * @param $err int
	 * @param $cmd string
	 */
	protected function logErrorForExternalProcess( $retval, $err, $cmd ) {
		wfDebugLog( 'thumbnail',
			sprintf( 'thumbnail failed on %s: error %d "%s" from "%s"',
					wfHostname(), $retval, trim( $err ), $cmd ) );
	}
	/**
	 * Get a MediaTransformError with error 'thumbnail_error'
	 * 
	 * @param $params array Paramter array as passed to the transform* functions
	 * @param $errMsg string Error message
	 * @return MediaTransformError
	 */
	protected function getMediaTransformError( $params, $errMsg ) {
		return new MediaTransformError( 'thumbnail_error', $params['clientWidth'],
					$params['clientHeight'], $errMsg );
	}

	/**
	 * Transform an image using the built in GD library
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $params array Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occured, false (=no error) otherwise
	 */
	protected function transformGd( $image, $params ) {
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
		if ( !isset( $typemap[$params['mimeType']] ) ) {
			$err = 'Image type not supported';
			wfDebug( "$err\n" );
			$errMsg = wfMsg ( 'thumbnail_image-type' );
			return $this->getMediaTransformError( $params, $errMsg );
		}
		list( $loader, $colorStyle, $saveType ) = $typemap[$params['mimeType']];

		if ( !function_exists( $loader ) ) {
			$err = "Incomplete GD library configuration: missing function $loader";
			wfDebug( "$err\n" );
			$errMsg = wfMsg ( 'thumbnail_gd-library', $loader );
			return $this->getMediaTransformError( $params, $errMsg );
		}

		if ( !file_exists( $params['srcPath'] ) ) {
			$err = "File seems to be missing: {$params['srcPath']}";
			wfDebug( "$err\n" );
			$errMsg = wfMsg ( 'thumbnail_image-missing', $params['srcPath'] );
			return $this->getMediaTransformError( $params, $errMsg );
		}

		$src_image = call_user_func( $loader, $params['srcPath'] );
		$dst_image = imagecreatetruecolor( $params['physicalWidth'],
			$params['physicalHeight'] );

		// Initialise the destination image to transparent instead of
		// the default solid black, to support PNG and GIF transparency nicely
		$background = imagecolorallocate( $dst_image, 0, 0, 0 );
		imagecolortransparent( $dst_image, $background );
		imagealphablending( $dst_image, false );

		if ( $colorStyle == 'palette' ) {
			// Don't resample for paletted GIF images.
			// It may just uglify them, and completely breaks transparency.
			imagecopyresized( $dst_image, $src_image,
				0, 0, 0, 0,
				$params['physicalWidth'], $params['physicalHeight'],
				imagesx( $src_image ), imagesy( $src_image ) );
		} else {
			imagecopyresampled( $dst_image, $src_image,
				0, 0, 0, 0,
				$params['physicalWidth'], $params['physicalHeight'],
				imagesx( $src_image ), imagesy( $src_image ) );
		}

		imagesavealpha( $dst_image, true );

		call_user_func( $saveType, $dst_image, $params['dstPath'] );
		imagedestroy( $dst_image );
		imagedestroy( $src_image );

		return false; # No error
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
			throw new MWException( __METHOD__ . ': cannot escape this path name' );
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
				throw new MWException( __METHOD__ . ': unexpected colon character in path name' );
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

	/**
	 * Retrieve the version of the installed ImageMagick
	 * You can use PHPs version_compare() to use this value
	 * Value is cached for one hour.
	 * @return String representing the IM version.
	 */
	protected function getMagickVersion() {
		global $wgMemc;

		$cache = $wgMemc->get( "imagemagick-version" );
		if ( !$cache ) {
			global $wgImageMagickConvertCommand;
			$cmd = wfEscapeShellArg( $wgImageMagickConvertCommand ) . ' -version';
			wfDebug( __METHOD__ . ": Running convert -version\n" );
			$retval = '';
			$return = wfShellExec( $cmd, $retval );
			$x = preg_match( '/Version: ImageMagick ([0-9]*\.[0-9]*\.[0-9]*)/', $return, $matches );
			if ( $x != 1 ) {
				wfDebug( __METHOD__ . ": ImageMagick version check failed\n" );
				return null;
			}
			$wgMemc->set( "imagemagick-version", $matches[1], 3600 );
			return $matches[1];
		}
		return $cache;
	}

	static function imageJpegWrapper( $dst_image, $thumbPath ) {
		imageinterlace( $dst_image );
		imagejpeg( $dst_image, $thumbPath, 95 );
	}


	function getMetadata( $image, $filename ) {
		global $wgShowEXIF;
		if ( $wgShowEXIF && file_exists( $filename ) ) {
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
		wfSuppressWarnings();
		$exif = unserialize( $metadata );
		wfRestoreWarnings();
		if ( !isset( $exif['MEDIAWIKI_EXIF_VERSION'] ) ||
			$exif['MEDIAWIKI_EXIF_VERSION'] != Exif::version() )
		{
			# Wrong version
			wfDebug( __METHOD__ . ": wrong version\n" );
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
		foreach ( $lines as $line ) {
			$matches = array();
			if ( preg_match( '/^\\*\s*(.*?)\s*$/', $line, $matches ) ) {
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
