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

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;

/**
 * Generic handler for bitmap images
 *
 * @stable to extend
 * @ingroup Media
 */
class BitmapHandler extends TransformationalImageHandler {

	/**
	 * Returns which scaler type should be used. Creates parent directories
	 * for $dstPath and returns 'client' on error
	 * @stable to override
	 *
	 * @param string|null $dstPath
	 * @param bool $checkDstPath
	 * @return string|callable One of client, im, custom, gd, imext or an array( object, method )
	 */
	protected function getScalerType( $dstPath, $checkDstPath = true ) {
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$useImageResize = $mainConfig->get( MainConfigNames::UseImageResize );
		$useImageMagick = $mainConfig->get( MainConfigNames::UseImageMagick );
		$customConvertCommand = $mainConfig->get( MainConfigNames::CustomConvertCommand );
		if ( !$dstPath && $checkDstPath ) {
			# No output path available, client side scaling only
			$scaler = 'client';
		} elseif ( !$useImageResize ) {
			$scaler = 'client';
		} elseif ( $useImageMagick ) {
			$scaler = 'im';
		} elseif ( $customConvertCommand ) {
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
	 * @inheritDoc
	 * @stable to override
	 */
	public function makeParamString( $params ) {
		$res = parent::makeParamString( $params );
		if ( isset( $params['interlace'] ) && $params['interlace'] ) {
			return "interlaced-{$res}";
		} else {
			return $res;
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function parseParamString( $str ) {
		$remainder = preg_replace( '/^interlaced-/', '', $str );
		$params = parent::parseParamString( $remainder );
		if ( $params === false ) {
			return false;
		}
		$params['interlace'] = $str !== $remainder;
		return $params;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function validateParam( $name, $value ) {
		if ( $name === 'interlace' ) {
			return $value === false || $value === true;
		} else {
			return parent::validateParam( $name, $value );
		}
	}

	/**
	 * @stable to override
	 * @param File $image
	 * @param array &$params
	 * @return bool
	 */
	public function normaliseParams( $image, &$params ) {
		$maxInterlacingAreas = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::MaxInterlacingAreas );
		if ( !parent::normaliseParams( $image, $params ) ) {
			return false;
		}
		$mimeType = $image->getMimeType();
		$interlace = isset( $params['interlace'] ) && $params['interlace']
			&& isset( $maxInterlacingAreas[$mimeType] )
			&& $this->getImageArea( $image ) <= $maxInterlacingAreas[$mimeType];
		$params['interlace'] = $interlace;
		return true;
	}

	/**
	 * Get ImageMagick subsampling factors for the target JPEG pixel format.
	 *
	 * @param string $pixelFormat one of 'yuv444', 'yuv422', 'yuv420'
	 * @return string[] List of sampling factors
	 */
	protected function imageMagickSubsampling( $pixelFormat ) {
		switch ( $pixelFormat ) {
			case 'yuv444':
				return [ '1x1', '1x1', '1x1' ];
			case 'yuv422':
				return [ '2x1', '1x1', '1x1' ];
			case 'yuv420':
				return [ '2x2', '1x1', '1x1' ];
			default:
				throw new MWException( 'Invalid pixel format for JPEG output' );
		}
	}

	/**
	 * Transform an image using ImageMagick
	 * @stable to override
	 *
	 * @param File $image File associated with this thumbnail
	 * @param array $params Array with scaler params
	 *
	 * @return MediaTransformError|false Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformImageMagick( $image, $params ) {
		# use ImageMagick
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$sharpenReductionThreshold = $mainConfig->get( MainConfigNames::SharpenReductionThreshold );
		$sharpenParameter = $mainConfig->get( MainConfigNames::SharpenParameter );
		$maxAnimatedGifArea = $mainConfig->get( MainConfigNames::MaxAnimatedGifArea );
		$imageMagickTempDir = $mainConfig->get( MainConfigNames::ImageMagickTempDir );
		$imageMagickConvertCommand = $mainConfig->get( MainConfigNames::ImageMagickConvertCommand );
		$jpegPixelFormat = $mainConfig->get( MainConfigNames::JpegPixelFormat );
		$jpegQuality = $mainConfig->get( MainConfigNames::JpegQuality );
		$quality = [];
		$sharpen = [];
		$scene = false;
		$animation_pre = [];
		$animation_post = [];
		$decoderHint = [];
		$subsampling = [];

		if ( $params['mimeType'] == 'image/jpeg' ) {
			$qualityVal = isset( $params['quality'] ) ? (string)$params['quality'] : null;
			$quality = [ '-quality', $qualityVal ?: (string)$jpegQuality ]; // 80% by default
			if ( $params['interlace'] ) {
				$animation_post = [ '-interlace', 'JPEG' ];
			}
			# Sharpening, see T8193
			if ( ( $params['physicalWidth'] + $params['physicalHeight'] )
				/ ( $params['srcWidth'] + $params['srcHeight'] )
				< $sharpenReductionThreshold
			) {
				$sharpen = [ '-sharpen', $sharpenParameter ];
			}

			// JPEG decoder hint to reduce memory, available since IM 6.5.6-2
			$decoderHint = [ '-define', "jpeg:size={$params['physicalDimensions']}" ];

			if ( $jpegPixelFormat ) {
				$factors = $this->imageMagickSubsampling( $jpegPixelFormat );
				$subsampling = [ '-sampling-factor', implode( ',', $factors ) ];
			}
		} elseif ( $params['mimeType'] == 'image/png' ) {
			$quality = [ '-quality', '95' ]; // zlib 9, adaptive filtering
			if ( $params['interlace'] ) {
				$animation_post = [ '-interlace', 'PNG' ];
			}
		} elseif ( $params['mimeType'] == 'image/webp' ) {
			$quality = [ '-quality', '95' ]; // zlib 9, adaptive filtering
		} elseif ( $params['mimeType'] == 'image/gif' ) {
			if ( $this->getImageArea( $image ) > $maxAnimatedGifArea ) {
				// Extract initial frame only; we're so big it'll
				// be a total drag. :P
				$scene = 0;
			} elseif ( $this->isAnimatedImage( $image ) ) {
				// Coalesce is needed to scale animated GIFs properly (T3017).
				$animation_pre = [ '-coalesce' ];

				// We optimize the output, but -optimize is broken,
				// use optimizeTransparency instead (T13822). Version >= IM 6.3.5
				$animation_post = [ '-fuzz', '5%', '-layers', 'optimizeTransparency' ];
			}
			if ( $params['interlace'] && !$this->isAnimatedImage( $image ) ) {
				// Version >= IM 6.3.4
				// interlacing animated GIFs is a bad idea
				$animation_post[] = '-interlace';
				$animation_post[] = 'GIF';
			}
		} elseif ( $params['mimeType'] == 'image/x-xcf' ) {
			// Before merging layers, we need to set the background
			// to be transparent to preserve alpha, as -layers merge
			// merges all layers on to a canvas filled with the
			// background colour. After merging we reset the background
			// to be white for the default background colour setting
			// in the PNG image (which is used in old IE)
			$animation_pre = [
				'-background', 'transparent',
				'-layers', 'merge',
				'-background', 'white',
			];
		}

		// Use one thread only, to avoid deadlock bugs on OOM
		$env = [ 'OMP_NUM_THREADS' => 1 ];
		if ( strval( $imageMagickTempDir ) !== '' ) {
			$env['MAGICK_TMPDIR'] = $imageMagickTempDir;
		}

		$rotation = isset( $params['disableRotation'] ) ? 0 : $this->getRotation( $image );
		list( $width, $height ) = $this->extractPreRotationDimensions( $params, $rotation );

		$cmd = Shell::escape( ...array_merge(
			[ $imageMagickConvertCommand ],
			$quality,
			// Specify white background color, will be used for transparent images
			// in Internet Explorer/Windows instead of default black.
			[ '-background', 'white' ],
			$decoderHint,
			[ $this->escapeMagickInput( $params['srcPath'], $scene ) ],
			$animation_pre,
			// For the -thumbnail option a "!" is needed to force exact size,
			// or ImageMagick may decide your ratio is wrong and slice off
			// a pixel.
			[ '-thumbnail', "{$width}x{$height}!" ],
			// Add the source url as a comment to the thumb, but don't add the flag if there's no comment
			( $params['comment'] !== ''
				? [ '-set', 'comment', $this->escapeMagickProperty( $params['comment'] ) ]
				: [] ),
			// T108616: Avoid exposure of local file path
			[ '+set', 'Thumb::URI' ],
			[ '-depth', 8 ],
			$sharpen,
			[ '-rotate', "-$rotation" ],
			$subsampling,
			$animation_post,
			[ $this->escapeMagickOutput( $params['dstPath'] ) ] ) );

		wfDebug( __METHOD__ . ": running ImageMagick: $cmd" );
		$retval = 0;
		$err = wfShellExecWithStderr( $cmd, $retval, $env );

		if ( $retval !== 0 ) {
			$this->logErrorForExternalProcess( $retval, $err, $cmd );

			return $this->getMediaTransformError( $params, "$err\nError code: $retval" );
		}

		return false; # No error
	}

	/**
	 * Transform an image using the Imagick PHP extension
	 *
	 * @param File $image File associated with this thumbnail
	 * @param array $params Array with scaler params
	 *
	 * @return MediaTransformError|false Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformImageMagickExt( $image, $params ) {
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$sharpenReductionThreshold = $mainConfig->get( MainConfigNames::SharpenReductionThreshold );
		$sharpenParameter = $mainConfig->get( MainConfigNames::SharpenParameter );
		$maxAnimatedGifArea = $mainConfig->get( MainConfigNames::MaxAnimatedGifArea );
		$jpegPixelFormat = $mainConfig->get( MainConfigNames::JpegPixelFormat );
		$jpegQuality = $mainConfig->get( MainConfigNames::JpegQuality );
		try {
			$im = new Imagick();
			$im->readImage( $params['srcPath'] );

			if ( $params['mimeType'] == 'image/jpeg' ) {
				// Sharpening, see T8193
				if ( ( $params['physicalWidth'] + $params['physicalHeight'] )
					/ ( $params['srcWidth'] + $params['srcHeight'] )
					< $sharpenReductionThreshold
				) {
					// Hack, since $wgSharpenParameter is written specifically for the command line convert
					list( $radius, $sigma ) = explode( 'x', $sharpenParameter, 2 );
					$im->sharpenImage( (float)$radius, (float)$sigma );
				}
				$qualityVal = isset( $params['quality'] ) ? (string)$params['quality'] : null;
				$im->setCompressionQuality( $qualityVal ?: $jpegQuality );
				if ( $params['interlace'] ) {
					$im->setInterlaceScheme( Imagick::INTERLACE_JPEG );
				}
				if ( $jpegPixelFormat ) {
					$factors = $this->imageMagickSubsampling( $jpegPixelFormat );
					$im->setSamplingFactors( $factors );
				}
			} elseif ( $params['mimeType'] == 'image/png' ) {
				$im->setCompressionQuality( 95 );
				if ( $params['interlace'] ) {
					$im->setInterlaceScheme( Imagick::INTERLACE_PNG );
				}
			} elseif ( $params['mimeType'] == 'image/gif' ) {
				if ( $this->getImageArea( $image ) > $maxAnimatedGifArea ) {
					// Extract initial frame only; we're so big it'll
					// be a total drag. :P
					$im->setImageScene( 0 );
				} elseif ( $this->isAnimatedImage( $image ) ) {
					// Coalesce is needed to scale animated GIFs properly (T3017).
					$im = $im->coalesceImages();
				}
				// GIF interlacing is only available since 6.3.4
				if ( $params['interlace'] ) {
					$im->setInterlaceScheme( Imagick::INTERLACE_GIF );
				}
			}

			$rotation = isset( $params['disableRotation'] ) ? 0 : $this->getRotation( $image );
			list( $width, $height ) = $this->extractPreRotationDimensions( $params, $rotation );

			$im->setImageBackgroundColor( new ImagickPixel( 'white' ) );

			// Call Imagick::thumbnailImage on each frame
			foreach ( $im as $i => $frame ) {
				if ( !$frame->thumbnailImage( $width, $height, /* fit */ false ) ) {
					return $this->getMediaTransformError( $params, "Error scaling frame $i" );
				}
			}
			$im->setImageDepth( 8 );

			if ( $rotation && !$im->rotateImage( new ImagickPixel( 'white' ), 360 - $rotation ) ) {
				return $this->getMediaTransformError( $params, "Error rotating $rotation degrees" );
			}

			if ( $this->isAnimatedImage( $image ) ) {
				wfDebug( __METHOD__ . ": Writing animated thumbnail" );
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
	 * @param File $image File associated with this thumbnail
	 * @param array $params Array with scaler params
	 *
	 * @return MediaTransformError|false Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformCustom( $image, $params ) {
		// Use a custom convert command
		$customConvertCommand = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::CustomConvertCommand );

		// Variables: %s %d %w %h
		$matchLookupTable = [
			'%d' => Shell::escape( $params['dstPath'] ),
			'%s' => Shell::escape( $params['srcPath'] ),
			'%w' => Shell::escape( $params['physicalWidth'] ),
			'%h' => Shell::escape( $params['physicalHeight'] ),
		];
		// Find all variables in the original command at once,
		// so that replacement values cannot inject variable placeholders
		$cmd = preg_replace_callback( '/%[dswh]/',
			static function ( $m ) use ( &$matchLookupTable ) {
				if ( !isset( $matchLookupTable[$m[0]] ) ) {
					return $m[0];
				}
				// We only want to replace each of the variables once
				$replacement = $matchLookupTable[$m[0]];
				unset( $matchLookupTable[$m[0]] );
				return $replacement;
			},
			$customConvertCommand
		);
		wfDebug( __METHOD__ . ": Running custom convert command $cmd" );
		$retval = 0;
		$err = wfShellExecWithStderr( $cmd, $retval );

		if ( $retval !== 0 ) {
			$this->logErrorForExternalProcess( $retval, $err, $cmd );

			return $this->getMediaTransformError( $params, $err );
		}

		return false; # No error
	}

	/**
	 * Transform an image using the built in GD library
	 *
	 * @param File $image File associated with this thumbnail
	 * @param array $params Array with scaler params
	 *
	 * @return MediaTransformError|false Error object if error occurred, false (=no error) otherwise
	 */
	protected function transformGd( $image, $params ) {
		# Use PHP's builtin GD library functions.
		# First find out what kind of file this is, and select the correct
		# input routine for this.

		$typemap = [
			'image/gif' => [ 'imagecreatefromgif', 'palette', false, 'imagegif' ],
			'image/jpeg' => [ 'imagecreatefromjpeg', 'truecolor', true,
				[ __CLASS__, 'imageJpegWrapper' ] ],
			'image/png' => [ 'imagecreatefrompng', 'bits', false, 'imagepng' ],
			'image/vnd.wap.wbmp' => [ 'imagecreatefromwbmp', 'palette', false, 'imagewbmp' ],
			'image/xbm' => [ 'imagecreatefromxbm', 'palette', false, 'imagexbm' ],
		];

		if ( !isset( $typemap[$params['mimeType']] ) ) {
			$err = 'Image type not supported';
			wfDebug( $err );
			$errMsg = wfMessage( 'thumbnail_image-type' )->text();

			return $this->getMediaTransformError( $params, $errMsg );
		}
		list( $loader, $colorStyle, $useQuality, $saveType ) = $typemap[$params['mimeType']];

		if ( !function_exists( $loader ) ) {
			$err = "Incomplete GD library configuration: missing function $loader";
			wfDebug( $err );
			$errMsg = wfMessage( 'thumbnail_gd-library', $loader )->text();

			return $this->getMediaTransformError( $params, $errMsg );
		}

		if ( !file_exists( $params['srcPath'] ) ) {
			$err = "File seems to be missing: {$params['srcPath']}";
			wfDebug( $err );
			$errMsg = wfMessage( 'thumbnail_image-missing', $params['srcPath'] )->text();

			return $this->getMediaTransformError( $params, $errMsg );
		}

		if ( filesize( $params['srcPath'] ) === 0 ) {
			$err = "Image file size seems to be zero.";
			wfDebug( $err );
			$errMsg = wfMessage( 'thumbnail_image-size-zero', $params['srcPath'] )->text();

			return $this->getMediaTransformError( $params, $errMsg );
		}

		$src_image = $loader( $params['srcPath'] );

		$rotation = function_exists( 'imagerotate' ) && !isset( $params['disableRotation'] ) ?
			$this->getRotation( $image ) :
			0;
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

		$funcParams = [ $dst_image, $params['dstPath'] ];
		if ( $useQuality && isset( $params['quality'] ) ) {
			$funcParams[] = $params['quality'];
		}
		$saveType( ...$funcParams );

		imagedestroy( $dst_image );
		imagedestroy( $src_image );

		return false; # No error
	}

	/**
	 * Callback for transformGd when transforming jpeg images.
	 *
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param resource|object $dst_image Image resource of the original image
	 * @param string $thumbPath File path to write the thumbnail image to
	 * @param int|null $quality Quality of the thumbnail from 1-100,
	 *    or null to use default quality.
	 */
	public static function imageJpegWrapper( $dst_image, $thumbPath, $quality = null ) {
		$jpegQuality = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::JpegQuality );

		if ( $quality === null ) {
			$quality = $jpegQuality;
		}

		imageinterlace( $dst_image );
		imagejpeg( $dst_image, $thumbPath, $quality );
	}

	/**
	 * Returns whether the current scaler supports rotation (im and gd do)
	 * @stable to override
	 *
	 * @return bool
	 */
	public function canRotate() {
		$scaler = $this->getScalerType( null, false );
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
	 * @see $wgEnableAutoRotation
	 * @stable to override
	 * @return bool Whether auto rotation is enabled
	 */
	public function autoRotateEnabled() {
		$enableAutoRotation = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::EnableAutoRotation );

		if ( $enableAutoRotation === null ) {
			// Only enable auto-rotation when we actually can
			return $this->canRotate();
		}

		return $enableAutoRotation;
	}

	/**
	 * @stable to override
	 * @param File $file
	 * @param array $params Rotate parameters.
	 *   'rotation' clockwise rotation in degrees, allowed are multiples of 90
	 * @since 1.21
	 * @return MediaTransformError|false
	 */
	public function rotate( $file, $params ) {
		$imageMagickConvertCommand = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::ImageMagickConvertCommand );

		$rotation = ( $params['rotation'] + $this->getRotation( $file ) ) % 360;
		$scene = false;

		$scaler = $this->getScalerType( null, false );
		switch ( $scaler ) {
			case 'im':
				$cmd = Shell::escape( $imageMagickConvertCommand ) . " " .
					Shell::escape( $this->escapeMagickInput( $params['srcPath'], $scene ) ) .
					" -rotate " . Shell::escape( "-$rotation" ) . " " .
					Shell::escape( $this->escapeMagickOutput( $params['dstPath'] ) );
				wfDebug( __METHOD__ . ": running ImageMagick: $cmd" );
				$retval = 0;
				$err = wfShellExecWithStderr( $cmd, $retval );
				if ( $retval !== 0 ) {
					$this->logErrorForExternalProcess( $retval, $err, $cmd );

					return new MediaTransformError( 'thumbnail_error', 0, 0, $err );
				}

				return false;
			case 'imext':
				$im = new Imagick();
				$im->readImage( $params['srcPath'] );
				if ( !$im->rotateImage( new ImagickPixel( 'white' ), 360 - $rotation ) ) {
					return new MediaTransformError( 'thumbnail_error', 0, 0,
						"Error rotating $rotation degrees" );
				}
				$result = $im->writeImage( $params['dstPath'] );
				if ( !$result ) {
					return new MediaTransformError( 'thumbnail_error', 0, 0,
						"Unable to write image to {$params['dstPath']}" );
				}

				return false;
			default:
				return new MediaTransformError( 'thumbnail_error', 0, 0,
					"$scaler rotation not implemented" );
		}
	}
}
