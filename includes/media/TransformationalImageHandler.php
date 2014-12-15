<?php
/**
 * Base class for handlers which require transforming images in a
 * similar way as BitmapHandler does.
 *
 * This was split from BitmapHandler on the basis that some extensions
 * might want to work in a similar way to BitmapHandler, but for
 * different formats.
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
 * Handler for images that need to be transformed
 *
 * @since 1.24
 * @ingroup Media
 */
abstract class TransformationalImageHandler extends ImageHandler {
	/**
	 * @param File $image
	 * @param array $params Transform parameters. Entries with the keys 'width'
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
	 * @param array $params Parameters as returned by normaliseParams
	 * @param int $rotation The rotation angle that will be applied
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
	 * Create a thumbnail.
	 *
	 * This sets up various parameters, and then calls a helper method
	 * based on $this->getScalerType in order to scale the image.
	 *
	 * @param File $image
	 * @param string $dstPath
	 * @param string $dstUrl
	 * @param array $params
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
			# Comment as will be added to the Exif of the thumbnail
			'comment' => isset( $params['descriptionUrl'] )
				? "File source: {$params['descriptionUrl']}"
				: '',
			# Properties of the original image
			'srcWidth' => $image->getWidth(),
			'srcHeight' => $image->getHeight(),
			'mimeType' => $image->getMimeType(),
			'dstPath' => $dstPath,
			'dstUrl' => $dstUrl,
		);

		if ( isset( $params['quality'] ) && $params['quality'] === 'low' ) {
			$scalerParams['quality'] = 30;
		}

		// For subclasses that might be paged.
		if ( $image->isMultipage() && isset( $params['page'] ) ) {
			$scalerParams['page'] = intval( $params['page'] );
		}

		# Determine scaler type
		$scaler = $this->getScalerType( $dstPath );

		if ( is_array( $scaler ) ) {
			$scalerName = get_class( $scaler[0] );
		} else {
			$scalerName = $scaler;
		}

		wfDebug( __METHOD__ . ": creating {$scalerParams['physicalDimensions']} " .
			"thumbnail at $dstPath using scaler $scalerName\n" );

		if ( !$image->mustRender() &&
			$scalerParams['physicalWidth'] == $scalerParams['srcWidth']
			&& $scalerParams['physicalHeight'] == $scalerParams['srcHeight']
			&& !isset( $scalerParams['quality'] )
		) {

			# normaliseParams (or the user) wants us to return the unscaled image
			wfDebug( __METHOD__ . ": returning unscaled image\n" );

			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		if ( $scaler == 'client' ) {
			# Client-side image scaling, use the source URL
			# Using the destination URL in a TRANSFORM_LATER request would be incorrect
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		if ( !$this->isImageAreaOkForThumbnaling( $image, $params ) ) {
			global $wgMaxImageArea;
			return new TransformTooBigImageAreaError( $params, $wgMaxImageArea );
		}

		if ( $flags & self::TRANSFORM_LATER ) {
			wfDebug( __METHOD__ . ": Transforming later per flags.\n" );
			$newParams = array(
				'width' => $scalerParams['clientWidth'],
				'height' => $scalerParams['clientHeight']
			);
			if ( isset( $params['quality'] ) ) {
				$newParams['quality'] = $params['quality'];
			}
			if ( isset( $params['page'] ) && $params['page'] ) {
				$newParams['page'] = $params['page'];
			}
			return new ThumbnailImage( $image, $dstUrl, false, $newParams );
		}

		# Try to make a target path for the thumbnail
		if ( !wfMkdirParents( dirname( $dstPath ), null, __METHOD__ ) ) {
			wfDebug( __METHOD__ . ": Unable to create thumbnail destination " .
				"directory, falling back to client scaling\n" );

			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		# Transform functions and binaries need a FS source file
		$thumbnailSource = $this->getThumbnailSource( $image, $params );

		// If the source isn't the original, disable EXIF rotation because it's already been applied
		if ( $scalerParams['srcWidth'] != $thumbnailSource['width']
			|| $scalerParams['srcHeight'] != $thumbnailSource['height'] ) {
			$scalerParams['disableRotation'] = true;
		}

		$scalerParams['srcPath'] = $thumbnailSource['path'];
		$scalerParams['srcWidth'] = $thumbnailSource['width'];
		$scalerParams['srcHeight'] = $thumbnailSource['height'];

		if ( $scalerParams['srcPath'] === false ) { // Failed to get local copy
			wfDebugLog( 'thumbnail',
				sprintf( 'Thumbnail failed on %s: could not get local copy of "%s"',
					wfHostname(), $image->getName() ) );

			return new MediaTransformError( 'thumbnail_error',
				$scalerParams['clientWidth'], $scalerParams['clientHeight'],
				wfMessage( 'filemissing' )->text()
			);
		}

		# Try a hook. Called "Bitmap" for historical reasons.
		/** @var $mto MediaTransformOutput */
		$mto = null;
		Hooks::run( 'BitmapHandlerTransform', array( $this, $image, &$scalerParams, &$mto ) );
		if ( !is_null( $mto ) ) {
			wfDebug( __METHOD__ . ": Hook to BitmapHandlerTransform created an mto\n" );
			$scaler = 'hookaborted';
		}

		// $scaler will return a MediaTransformError on failure, or false on success.
		// If the scaler is succesful, it will have created a thumbnail at the destination
		// path.
		if ( is_array( $scaler ) && is_callable( $scaler ) ) {
			// Allow subclasses to specify their own rendering methods.
			$err = call_user_func( $scaler, $image, $scalerParams );
		} else {
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
		}

		# Remove the file if a zero-byte thumbnail was created, or if there was an error
		$removed = $this->removeBadFile( $dstPath, (bool)$err );
		if ( $err ) {
			# transform returned MediaTransforError
			return $err;
		} elseif ( $removed ) {
			# Thumbnail was zero-byte and had to be removed
			return new MediaTransformError( 'thumbnail_error',
				$scalerParams['clientWidth'], $scalerParams['clientHeight'],
				wfMessage( 'unknown-error' )->text()
			);
		} elseif ( $mto ) {
			return $mto;
		} else {
			$newParams = array(
				'width' => $scalerParams['clientWidth'],
				'height' => $scalerParams['clientHeight']
			);
			if ( isset( $params['quality'] ) ) {
				$newParams['quality'] = $params['quality'];
			}
			if ( isset( $params['page'] ) && $params['page'] ) {
				$newParams['page'] = $params['page'];
			}
			return new ThumbnailImage( $image, $dstUrl, $dstPath, $newParams );
		}
	}

	/**
	 * Get the source file for the transform
	 *
	 * @param $file File
	 * @param $params Array
	 * @return Array Array with keys  width, height and path.
	 */
	protected function getThumbnailSource( $file, $params ) {
		return $file->getThumbnailSource( $params );
	}

	/**
	 * Returns what sort of scaler type should be used.
	 *
	 * Values can be one of client, im, custom, gd, imext, or an array
	 * of object, method-name to call that specific method.
	 *
	 * If specifying a custom scaler command with array( Obj, method ),
	 * the method in question should take 2 parameters, a File object,
	 * and a $scalerParams array with various options (See doTransform
	 * for what is in $scalerParams). On error it should return a
	 * MediaTransformError object. On success it should return false,
	 * and simply make sure the thumbnail file is located at
	 * $scalerParams['dstPath'].
	 *
	 * If there is a problem with the output path, it returns "client"
	 * to do client side scaling.
	 *
	 * @param string $dstPath
	 * @param bool $checkDstPath Check that $dstPath is valid
	 * @return string|Callable One of client, im, custom, gd, imext, or a Callable array.
	 */
	abstract protected function getScalerType( $dstPath, $checkDstPath = true );

	/**
	 * Get a ThumbnailImage that respresents an image that will be scaled
	 * client side
	 *
	 * @param File $image File associated with this thumbnail
	 * @param array $scalerParams Array with scaler params
	 * @return ThumbnailImage
	 *
	 * @todo FIXME: No rotation support
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
	 * This is a stub method. The real method is in BitmapHander.
	 *
	 * @param File $image File associated with this thumbnail
	 * @param array $params Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformImageMagick( $image, $params ) {
		return $this->getMediaTransformError( $params, "Unimplemented" );
	}

	/**
	 * Transform an image using the Imagick PHP extension
	 *
	 * This is a stub method. The real method is in BitmapHander.
	 *
	 * @param File $image File associated with this thumbnail
	 * @param array $params Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformImageMagickExt( $image, $params ) {
		return $this->getMediaTransformError( $params, "Unimplemented" );
	}

	/**
	 * Transform an image using a custom command
	 *
	 * This is a stub method. The real method is in BitmapHander.
	 *
	 * @param File $image File associated with this thumbnail
	 * @param array $params Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformCustom( $image, $params ) {
		return $this->getMediaTransformError( $params, "Unimplemented" );
	}

	/**
	 * Get a MediaTransformError with error 'thumbnail_error'
	 *
	 * @param array $params Parameter array as passed to the transform* functions
	 * @param string $errMsg Error message
	 * @return MediaTransformError
	 */
	public function getMediaTransformError( $params, $errMsg ) {
		return new MediaTransformError( 'thumbnail_error', $params['clientWidth'],
			$params['clientHeight'], $errMsg );
	}

	/**
	 * Transform an image using the built in GD library
	 *
	 * This is a stub method. The real method is in BitmapHander.
	 *
	 * @param File $image File associated with this thumbnail
	 * @param array $params Array with scaler params
	 *
	 * @return MediaTransformError Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformGd( $image, $params ) {
		return $this->getMediaTransformError( $params, "Unimplemented" );
	}

	/**
	 * Escape a string for ImageMagick's property input (e.g. -set -comment)
	 * See InterpretImageProperties() in magick/property.c
	 * @param string $s
	 * @return string
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
	 * @param string $path The file path
	 * @param bool|string $scene The scene specification, or false if there is none
	 * @throws MWException
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
	 * @param string $path The file path
	 * @param bool|string $scene The scene specification, or false if there is none
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
	 * @param string $path The file path
	 * @param bool|string $scene The scene specification, or false if there is none
	 * @throws MWException
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
	 * @return string Representing the IM version.
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

	/**
	 * Returns whether the current scaler supports rotation.
	 *
	 * @since 1.24 No longer static
	 * @return bool
	 */
	public function canRotate() {
		return false;
	}

	/**
	 * Should we automatically rotate an image based on exif
	 *
	 * @since 1.24 No longer static
	 * @see $wgEnableAutoRotation
	 * @return bool Whether auto rotation is enabled
	 */
	public function autoRotateEnabled() {
		return false;
	}

	/**
	 * Rotate a thumbnail.
	 *
	 * This is a stub. See BitmapHandler::rotate.
	 *
	 * @param File $file
	 * @param array $params Rotate parameters.
	 *   'rotation' clockwise rotation in degrees, allowed are multiples of 90
	 * @since 1.24 Is non-static. From 1.21 it was static
	 * @return bool
	 */
	public function rotate( $file, $params ) {
		return new MediaTransformError( 'thumbnail_error', 0, 0,
			get_class( $this ) . ' rotation not implemented' );
	}

	/**
	 * Returns whether the file needs to be rendered. Returns true if the
	 * file requires rotation and we are able to rotate it.
	 *
	 * @param File $file
	 * @return bool
	 */
	public function mustRender( $file ) {
		return $this->canRotate() && $this->getRotation( $file ) != 0;
	}

	/**
	 * Check if the file is smaller than the maximum image area for thumbnailing.
	 *
	 * Runs the 'BitmapHandlerCheckImageArea' hook.
	 *
	 * @param File $file
	 * @param array $params
	 * @return bool
	 * @since 1.25
	 */
	public function isImageAreaOkForThumbnaling( $file, &$params ) {
		global $wgMaxImageArea;

		# For historical reasons, hook starts with BitmapHandler
		$checkImageAreaHookResult = null;
		Hooks::run(
			'BitmapHandlerCheckImageArea',
			array( $file, &$params, &$checkImageAreaHookResult )
		);

		if ( !is_null( $checkImageAreaHookResult ) ) {
			// was set by hook, so return that value
			return (bool)$checkImageAreaHookResult;
		}

		$srcWidth = $file->getWidth( $params['page'] );
		$srcHeight = $file->getHeight( $params['page'] );

		if ( $srcWidth * $srcHeight > $wgMaxImageArea
			&& !( $file->getMimeType() == 'image/jpeg'
				&& $this->getScalerType( false, false ) == 'im' )
		) {
			# Only ImageMagick can efficiently downsize jpg images without loading
			# the entire file in memory
			return false;
		}
		return true;
	}
}
