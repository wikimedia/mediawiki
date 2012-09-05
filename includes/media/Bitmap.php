<?php
/**
 * Generic handler for bitmap images.
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
 * Generic handler for bitmap images
 *
 * @ingroup Media
 */
class BitmapHandler extends ImageHandler {
	/**
	 * @param $image File
	 * @param $params array Transform parameters. Entries with the keys 'width'
	 * and 'height' are the respective screen width and height, while the keys
	 * 'physicalWidth' and 'physicalHeight' indicate the thumbnail dimensions.
	 * @return bool
	 */
	function normaliseParams( $image, &$params ) {
		if ( !parent::normaliseParams( $image, $params ) ) {
			return false;
		}

		# Obtain the source, pre-rotation dimensions
		$srcWidth = $image->getWidth( $params['page'] );
		$srcHeight = $image->getHeight( $params['page'] );

		# Don't make an image bigger than the source
		if ( $params['physicalWidth'] >= $srcWidth ) {
			$params['physicalWidth'] = $srcWidth;
			$params['physicalHeight'] = $srcHeight;

			# Skip scaling limit checks if no scaling is required
			# due to requested size being bigger than source.
			if ( !$image->mustRender() ) {
				return true;
			}
		}

		# Check if the file is smaller than the maximum image area for thumbnailing
		$checkImageAreaHookResult = null;
		wfRunHooks( 'BitmapHandlerCheckImageArea', array( $image, &$params, &$checkImageAreaHookResult ) );
		if ( is_null( $checkImageAreaHookResult ) ) {
			global $wgMaxImageArea;

			if ( $srcWidth * $srcHeight > $wgMaxImageArea &&
					!( $image->getMimeType() == 'image/jpeg' &&
						self::getScalerType( false, false ) == 'im' ) ) {
				# Only ImageMagick can efficiently downsize jpg images without loading
				# the entire file in memory
				return false;
			}
		} else {
			return $checkImageAreaHookResult;
		}

		return true;
	}


	/**
	 * Extracts the width/height if the image will be scaled before rotating
	 *
	 * This will match the physical size/aspect ratio of the original image
	 * prior to application of the rotation -- so for a portrait image that's
	 * stored as raw landscape with 90-degress rotation, the resulting size
	 * will be wider than it is tall.
	 *
	 * @param $params array Parameters as returned by normaliseParams
	 * @param $rotation int The rotation angle that will be applied
	 * @return array ($width, $height) array
	 */
	public function extractPreRotationDimensions( $params, $rotation ) {
		if ( $rotation == 90 || $rotation == 270 ) {
			# We'll resize before rotation, so swap the dimensions again
			$width = $params['physicalHeight'];
			$height = $params['physicalWidth'];
		} else {
			$width = $params['physicalWidth'];
			$height = $params['physicalHeight'];
		}
		return array( $width, $height );
	}


	/**
	 * Function that returns the number of pixels to be thumbnailed.
	 * Intended for animated GIFs to multiply by the number of frames.
	 *
	 * @param File $image
	 * @return int
	 */
	function getImageArea( $image ) {
		return $image->getWidth() * $image->getHeight();
	}

	/**
	 * @param $image File
	 * @param  $dstPath
	 * @param  $dstUrl
	 * @param  $params
	 * @param int $flags
	 * @return MediaTransformError|ThumbnailImage|TransformParameterError
	 */
	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
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
			'dstPath' => $dstPath,
			'dstUrl' => $dstUrl,
		);

		# Determine scaler type
		$scaler = self::getScalerType( $dstPath );

		wfDebug( __METHOD__ . ": creating {$scalerParams['physicalDimensions']} thumbnail at $dstPath using scaler $scaler\n" );

		if ( !$image->mustRender() &&
				$scalerParams['physicalWidth'] == $scalerParams['srcWidth']
				&& $scalerParams['physicalHeight'] == $scalerParams['srcHeight'] ) {

			# normaliseParams (or the user) wants us to return the unscaled image
			wfDebug( __METHOD__ . ": returning unscaled image\n" );
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}


		if ( $scaler == 'client' ) {
			# Client-side image scaling, use the source URL
			# Using the destination URL in a TRANSFORM_LATER request would be incorrect
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		if ( $flags & self::TRANSFORM_LATER ) {
			wfDebug( __METHOD__ . ": Transforming later per flags.\n" );
			$params = array(
				'width' => $scalerParams['clientWidth'],
				'height' => $scalerParams['clientHeight']
			);
			return new ThumbnailImage( $image, $dstUrl, false, $params );
		}

		# Try to make a target path for the thumbnail
		if ( !wfMkdirParents( dirname( $dstPath ), null, __METHOD__ ) ) {
			wfDebug( __METHOD__ . ": Unable to create thumbnail destination directory, falling back to client scaling\n" );
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		# Transform functions and binaries need a FS source file
		$scalerParams['srcPath'] = $image->getLocalRefPath();

		# Try a hook
		$mto = null;
		wfRunHooks( 'BitmapHandlerTransform', array( $this, $image, &$scalerParams, &$mto ) );
		if ( !is_null( $mto ) ) {
			wfDebug( __METHOD__ . ": Hook to BitmapHandlerTransform created an mto\n" );
			$scaler = 'hookaborted';
		}

		switch ( $scaler ) {
			case 'hookaborted':
				# Handled by the hook above
				$err = $mto->isError() ? $mto : false;
				break;
			case 'im':
				$err = $this->transformImageMagick( $image, $scalerParams );
				break;
			case 'custom':
				$err = $this->transformCustom( $image, $scalerParams );
				break;
			case 'imext':
				$err = $this->transformImageMagickExt( $image, $scalerParams );
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
		} elseif ( $mto ) {
			return $mto;
		} else {
			$params = array(
				'width' => $scalerParams['clientWidth'],
				'height' => $scalerParams['clientHeight']
			);
			return new ThumbnailImage( $image, $dstUrl, $dstPath, $params );
		}
	}

	/**
	 * Returns which scaler type should be used. Creates parent directories
	 * for $dstPath and returns 'client' on error
	 *
	 * @return string client,im,custom,gd
	 */
	protected static function getScalerType( $dstPath, $checkDstPath = true ) {
		global $wgUseImageResize, $wgUseImageMagick, $wgCustomConvertCommand;

		if ( !$dstPath && $checkDstPath ) {
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
		} elseif ( class_exists( 'Imagick' ) ) {
			$scaler = 'imext';
		} else {
			$scaler = 'client';
		}
		return $scaler;
	}

	/**
	 * Get a ThumbnailImage that respresents an image that will be scaled
	 * client side
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $scalerParams array Array with scaler params
	 * @return ThumbnailImage
	 *
	 * @todo fixme: no rotation support
	 */
	protected function getClientScalingThumbnailImage( $image, $scalerParams ) {
		$params = array(
			'width' => $scalerParams['clientWidth'],
			'height' => $scalerParams['clientHeight']
		);
		return new ThumbnailImage( $image, $image->getURL(), null, $params );
	}

	/**
	 * Transform an image using ImageMagick
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $params array Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occurred, false (=no error) otherwise
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
			if ( version_compare( $this->getMagickVersion(), "6.5.6" ) >= 0 ) {
				// JPEG decoder hint to reduce memory, available since IM 6.5.6-2
				$decoderHint = "-define jpeg:size={$params['physicalDimensions']}";
			}

		} elseif ( $params['mimeType'] == 'image/png' ) {
			$quality = "-quality 95"; // zlib 9, adaptive filtering

		} elseif ( $params['mimeType'] == 'image/gif' ) {
			if ( $this->getImageArea( $image ) > $wgMaxAnimatedGifArea ) {
				// Extract initial frame only; we're so big it'll
				// be a total drag. :P
				$scene = 0;

			} elseif ( $this->isAnimatedImage( $image ) ) {
				// Coalesce is needed to scale animated GIFs properly (bug 1017).
				$animation_pre = '-coalesce';
				// We optimize the output, but -optimize is broken,
				// use optimizeTransparency instead (bug 11822)
				if ( version_compare( $this->getMagickVersion(), "6.3.5" ) >= 0 ) {
					$animation_post = '-fuzz 5% -layers optimizeTransparency';
				}
			}
		} elseif ( $params['mimeType'] == 'image/x-xcf' ) {
			$animation_post = '-layers merge';
		}

		// Use one thread only, to avoid deadlock bugs on OOM
		$env = array( 'OMP_NUM_THREADS' => 1 );
		if ( strval( $wgImageMagickTempDir ) !== '' ) {
			$env['MAGICK_TMPDIR'] = $wgImageMagickTempDir;
		}

		$rotation = $this->getRotation( $image );
		list( $width, $height ) = $this->extractPreRotationDimensions( $params, $rotation );

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
			" -thumbnail " . wfEscapeShellArg( "{$width}x{$height}!" ) .
			// Add the source url as a comment to the thumb, but don't add the flag if there's no comment
			( $params['comment'] !== ''
				? " -set comment " . wfEscapeShellArg( $this->escapeMagickProperty( $params['comment'] ) )
				: '' ) .
			" -depth 8 $sharpen " .
			" -rotate -$rotation " .
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
	 * Transform an image using the Imagick PHP extension
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $params array Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformImageMagickExt( $image, $params ) {
		global $wgSharpenReductionThreshold, $wgSharpenParameter, $wgMaxAnimatedGifArea;

		try {
			$im = new Imagick();
			$im->readImage( $params['srcPath'] );

			if ( $params['mimeType'] == 'image/jpeg' ) {
				// Sharpening, see bug 6193
				if ( ( $params['physicalWidth'] + $params['physicalHeight'] )
						/ ( $params['srcWidth'] + $params['srcHeight'] )
						< $wgSharpenReductionThreshold ) {
					// Hack, since $wgSharpenParamater is written specifically for the command line convert
					list( $radius, $sigma ) = explode( 'x', $wgSharpenParameter );
					$im->sharpenImage( $radius, $sigma );
				}
				$im->setCompressionQuality( 80 );
			} elseif( $params['mimeType'] == 'image/png' ) {
				$im->setCompressionQuality( 95 );
			} elseif ( $params['mimeType'] == 'image/gif' ) {
				if ( $this->getImageArea( $image ) > $wgMaxAnimatedGifArea ) {
					// Extract initial frame only; we're so big it'll
					// be a total drag. :P
					$im->setImageScene( 0 );
				} elseif ( $this->isAnimatedImage( $image ) ) {
					// Coalesce is needed to scale animated GIFs properly (bug 1017).
					$im = $im->coalesceImages();
				}
			}

			$rotation = $this->getRotation( $image );
			list( $width, $height ) = $this->extractPreRotationDimensions( $params, $rotation );

			$im->setImageBackgroundColor( new ImagickPixel( 'white' ) );

			// Call Imagick::thumbnailImage on each frame
			foreach ( $im as $i => $frame ) {
				if ( !$frame->thumbnailImage( $width, $height, /* fit */ false ) ) {
					return $this->getMediaTransformError( $params, "Error scaling frame $i" );
				}
			}
			$im->setImageDepth( 8 );

			if ( $rotation ) {
				if ( !$im->rotateImage( new ImagickPixel( 'white' ), 360 - $rotation ) ) {
					return $this->getMediaTransformError( $params, "Error rotating $rotation degrees" );
				}
			}

			if ( $this->isAnimatedImage( $image ) ) {
				wfDebug( __METHOD__ . ": Writing animated thumbnail\n" );
				// This is broken somehow... can't find out how to fix it
				$result = $im->writeImages( $params['dstPath'], true );
			} else {
				$result = $im->writeImage( $params['dstPath'] );
			}
			if ( !$result ) {
				return $this->getMediaTransformError( $params,
					"Unable to write thumbnail to {$params['dstPath']}" );
			}

		} catch ( ImagickException $e ) {
			return $this->getMediaTransformError( $params, $e->getMessage() );
		}

		return false;

	}

	/**
	 * Transform an image using a custom command
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $params array Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occurred, false (=no error) otherwise
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
	 * Log an error that occurred in an external process
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
	 * @param $params array Parameter array as passed to the transform* functions
	 * @param $errMsg string Error message
	 * @return MediaTransformError
	 */
	public function getMediaTransformError( $params, $errMsg ) {
		return new MediaTransformError( 'thumbnail_error', $params['clientWidth'],
					$params['clientHeight'], $errMsg );
	}

	/**
	 * Transform an image using the built in GD library
	 *
	 * @param $image File File associated with this thumbnail
	 * @param $params array Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occurred, false (=no error) otherwise
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
			$errMsg = wfMessage( 'thumbnail_image-type' )->text();
			return $this->getMediaTransformError( $params, $errMsg );
		}
		list( $loader, $colorStyle, $saveType ) = $typemap[$params['mimeType']];

		if ( !function_exists( $loader ) ) {
			$err = "Incomplete GD library configuration: missing function $loader";
			wfDebug( "$err\n" );
			$errMsg = wfMessage( 'thumbnail_gd-library', $loader )->text();
			return $this->getMediaTransformError( $params, $errMsg );
		}

		if ( !file_exists( $params['srcPath'] ) ) {
			$err = "File seems to be missing: {$params['srcPath']}";
			wfDebug( "$err\n" );
			$errMsg = wfMessage( 'thumbnail_image-missing', $params['srcPath'] )->text();
			return $this->getMediaTransformError( $params, $errMsg );
		}

		$src_image = call_user_func( $loader, $params['srcPath'] );

		$rotation = function_exists( 'imagerotate' ) ? $this->getRotation( $image ) : 0;
		list( $width, $height ) = $this->extractPreRotationDimensions( $params, $rotation );
		$dst_image = imagecreatetruecolor( $width, $height );

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
				$width, $height,
				imagesx( $src_image ), imagesy( $src_image ) );
		} else {
			imagecopyresampled( $dst_image, $src_image,
				0, 0, 0, 0,
				$width, $height,
				imagesx( $src_image ), imagesy( $src_image ) );
		}

		if ( $rotation % 360 != 0 && $rotation % 90 == 0 ) {
			$rot_image = imagerotate( $dst_image, $rotation, 0 );
			imagedestroy( $dst_image );
			$dst_image = $rot_image;
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
	 * @return mixed|string
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
	 * @return string
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
	 * @return string
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
	 * @return string
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

	/**
	 * On supporting image formats, try to read out the low-level orientation
	 * of the file and return the angle that the file needs to be rotated to
	 * be viewed.
	 *
	 * This information is only useful when manipulating the original file;
	 * the width and height we normally work with is logical, and will match
	 * any produced output views.
	 *
	 * The base BitmapHandler doesn't understand any metadata formats, so this
	 * is left up to child classes to implement.
	 *
	 * @param $file File
	 * @return int 0, 90, 180 or 270
	 */
	public function getRotation( $file ) {
		return 0;
	}

	/**
	 * Returns whether the current scaler supports rotation (im and gd do)
	 *
	 * @return bool
	 */
	public static function canRotate() {
		$scaler = self::getScalerType( null, false );
		switch ( $scaler ) {
			case 'im':
				# ImageMagick supports autorotation
				return true;
			case 'imext':
				# Imagick::rotateImage
				return true;
			case 'gd':
				# GD's imagerotate function is used to rotate images, but not
				# all precompiled PHP versions have that function
				return function_exists( 'imagerotate' );
			default:
				# Other scalers don't support rotation
				return false;
		}
	}

	/**
	 * Rerurns whether the file needs to be rendered. Returns true if the
	 * file requires rotation and we are able to rotate it.
	 *
	 * @param $file File
	 * @return bool
	 */
	public function mustRender( $file ) {
		return self::canRotate() && $this->getRotation( $file ) != 0;
	}
}
