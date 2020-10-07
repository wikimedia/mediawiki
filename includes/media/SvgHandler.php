<?php
/**
 * Handler for SVG images.
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

use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;
use Wikimedia\ScopedCallback;

/**
 * Handler for SVG images.
 *
 * @ingroup Media
 */
class SvgHandler extends ImageHandler {
	public const SVG_METADATA_VERSION = 2;

	/** @var array A list of metadata tags that can be converted
	 *  to the commonly used exif tags. This allows messages
	 *  to be reused, and consistent tag names for {{#formatmetadata:..}}
	 */
	private static $metaConversion = [
		'originalwidth' => 'ImageWidth',
		'originalheight' => 'ImageLength',
		'description' => 'ImageDescription',
		'title' => 'ObjectName',
	];

	public function isEnabled() {
		global $wgSVGConverters, $wgSVGConverter;
		if ( !isset( $wgSVGConverters[$wgSVGConverter] ) ) {
			wfDebug( "\$wgSVGConverter is invalid, disabling SVG rendering." );

			return false;
		} else {
			return true;
		}
	}

	public function mustRender( $file ) {
		return true;
	}

	public function isVectorized( $file ) {
		return true;
	}

	/**
	 * @param File $file
	 * @return bool
	 */
	public function isAnimatedImage( $file ) {
		# @todo Detect animated SVGs
		$metadata = $file->getMetadata();
		if ( $metadata ) {
			$metadata = $this->unpackMetadata( $metadata );
			if ( isset( $metadata['animated'] ) ) {
				return $metadata['animated'];
			}
		}

		return false;
	}

	/**
	 * Which languages (systemLanguage attribute) is supported.
	 *
	 * @note This list is not guaranteed to be exhaustive.
	 * To avoid OOM errors, we only look at first bit of a file.
	 * Thus all languages on this list are present in the file,
	 * but its possible for the file to have a language not on
	 * this list.
	 *
	 * @param File $file
	 * @return array Array of language codes, or empty if no language switching supported.
	 */
	public function getAvailableLanguages( File $file ) {
		$metadata = $file->getMetadata();
		$langList = [];
		if ( $metadata ) {
			$metadata = $this->unpackMetadata( $metadata );
			if ( isset( $metadata['translations'] ) ) {
				foreach ( $metadata['translations'] as $lang => $langType ) {
					if ( $langType === SVGReader::LANG_FULL_MATCH ) {
						$langList[] = strtolower( $lang );
					}
				}
			}
		}
		return array_unique( $langList );
	}

	/**
	 * SVG's systemLanguage matching rules state:
	 * 'The `systemLanguage` attribute ... [e]valuates to "true" if one of the languages indicated
	 * by user preferences exactly equals one of the languages given in the value of this parameter,
	 * or if one of the languages indicated by user preferences exactly equals a prefix of one of
	 * the languages given in the value of this parameter such that the first tag character
	 * following the prefix is "-".'
	 *
	 * Return the first element of $svgLanguages that matches $userPreferredLanguage
	 *
	 * @see https://www.w3.org/TR/SVG/struct.html#SystemLanguageAttribute
	 * @param string $userPreferredLanguage
	 * @param array $svgLanguages
	 * @return string|null
	 */
	public function getMatchedLanguage( $userPreferredLanguage, array $svgLanguages ) {
		foreach ( $svgLanguages as $svgLang ) {
			if ( strcasecmp( $svgLang, $userPreferredLanguage ) === 0 ) {
				return $svgLang;
			}
			$trimmedSvgLang = $svgLang;
			while ( strpos( $trimmedSvgLang, '-' ) !== false ) {
				$trimmedSvgLang = substr( $trimmedSvgLang, 0, strrpos( $trimmedSvgLang, '-' ) );
				if ( strcasecmp( $trimmedSvgLang, $userPreferredLanguage ) === 0 ) {
					return $svgLang;
				}
			}
		}
		return null;
	}

	/**
	 * Determines render language from image parameters
	 *
	 * @param array $params
	 * @return string
	 */
	protected function getLanguageFromParams( array $params ) {
		return $params['lang'] ?? $params['targetlang'] ?? 'en';
	}

	/**
	 * What language to render file in if none selected
	 *
	 * @param File $file Language code
	 * @return string
	 */
	public function getDefaultRenderLanguage( File $file ) {
		return 'en';
	}

	/**
	 * We do not support making animated svg thumbnails
	 * @param File $file
	 * @return bool
	 */
	public function canAnimateThumbnail( $file ) {
		return false;
	}

	/**
	 * @param File $image
	 * @param array &$params
	 * @return bool
	 */
	public function normaliseParams( $image, &$params ) {
		if ( parent::normaliseParams( $image, $params ) ) {
			$params = $this->normaliseParamsInternal( $image, $params );
			return true;
		}

		return false;
	}

	/**
	 * Code taken out of normaliseParams() for testability
	 *
	 * @since 1.33
	 *
	 * @param File $image
	 * @param array $params
	 * @return array Modified $params
	 */
	protected function normaliseParamsInternal( $image, $params ) {
		global $wgSVGMaxSize;

		# Don't make an image bigger than wgMaxSVGSize on the smaller side
		if ( $params['physicalWidth'] <= $params['physicalHeight'] ) {
			if ( $params['physicalWidth'] > $wgSVGMaxSize ) {
				$srcWidth = $image->getWidth( $params['page'] );
				$srcHeight = $image->getHeight( $params['page'] );
				$params['physicalWidth'] = $wgSVGMaxSize;
				$params['physicalHeight'] = File::scaleHeight( $srcWidth, $srcHeight, $wgSVGMaxSize );
			}
		} elseif ( $params['physicalHeight'] > $wgSVGMaxSize ) {
			$srcWidth = $image->getWidth( $params['page'] );
			$srcHeight = $image->getHeight( $params['page'] );
			$params['physicalWidth'] = File::scaleHeight( $srcHeight, $srcWidth, $wgSVGMaxSize );
			$params['physicalHeight'] = $wgSVGMaxSize;
		}
		// To prevent the proliferation of thumbnails in languages not present in SVGs, unless
		// explicitly forced by user.
		if ( isset( $params['targetlang'] ) && !$image->getMatchedLanguage( $params['targetlang'] ) ) {
			unset( $params['targetlang'] );
		}

		return $params;
	}

	/**
	 * @param File $image
	 * @param string $dstPath
	 * @param string $dstUrl
	 * @param array $params
	 * @param int $flags
	 * @return bool|MediaTransformError|ThumbnailImage|TransformParameterError
	 */
	public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		$clientWidth = $params['width'];
		$clientHeight = $params['height'];
		$physicalWidth = $params['physicalWidth'];
		$physicalHeight = $params['physicalHeight'];
		$lang = $this->getLanguageFromParams( $params );

		if ( $flags & self::TRANSFORM_LATER ) {
			return new ThumbnailImage( $image, $dstUrl, $dstPath, $params );
		}

		$metadata = $this->unpackMetadata( $image->getMetadata() );
		if ( isset( $metadata['error'] ) ) { // sanity check
			$err = wfMessage( 'svg-long-error', $metadata['error']['message'] );

			return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight, $err );
		}

		if ( !wfMkdirParents( dirname( $dstPath ), null, __METHOD__ ) ) {
			return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight,
				wfMessage( 'thumbnail_dest_directory' ) );
		}

		$srcPath = $image->getLocalRefPath();
		if ( $srcPath === false ) { // Failed to get local copy
			wfDebugLog( 'thumbnail',
				sprintf( 'Thumbnail failed on %s: could not get local copy of "%s"',
					wfHostname(), $image->getName() ) );

			return new MediaTransformError( 'thumbnail_error',
				$params['width'], $params['height'],
				wfMessage( 'filemissing' )
			);
		}

		// Make a temp dir with a symlink to the local copy in it.
		// This plays well with rsvg-convert policy for external entities.
		// https://git.gnome.org/browse/librsvg/commit/?id=f01aded72c38f0e18bc7ff67dee800e380251c8e
		$tmpDir = wfTempDir() . '/svg_' . wfRandomString( 24 );
		$lnPath = "$tmpDir/" . basename( $srcPath );
		$ok = mkdir( $tmpDir, 0771 );
		if ( !$ok ) {
			wfDebugLog( 'thumbnail',
				sprintf( 'Thumbnail failed on %s: could not create temporary directory %s',
					wfHostname(), $tmpDir ) );
			return new MediaTransformError( 'thumbnail_error',
				$params['width'], $params['height'],
				wfMessage( 'thumbnail-temp-create' )->text()
			);
		}
		$ok = symlink( $srcPath, $lnPath );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$cleaner = new ScopedCallback( function () use ( $tmpDir, $lnPath ) {
			Wikimedia\suppressWarnings();
			unlink( $lnPath );
			rmdir( $tmpDir );
			Wikimedia\restoreWarnings();
		} );
		if ( !$ok ) {
			wfDebugLog( 'thumbnail',
				sprintf( 'Thumbnail failed on %s: could not link %s to %s',
					wfHostname(), $lnPath, $srcPath ) );
			return new MediaTransformError( 'thumbnail_error',
				$params['width'], $params['height'],
				wfMessage( 'thumbnail-temp-create' )
			);
		}

		$status = $this->rasterize( $lnPath, $dstPath, $physicalWidth, $physicalHeight, $lang );
		if ( $status === true ) {
			return new ThumbnailImage( $image, $dstUrl, $dstPath, $params );
		} else {
			return $status; // MediaTransformError
		}
	}

	/**
	 * Transform an SVG file to PNG
	 * This function can be called outside of thumbnail contexts
	 * @param string $srcPath
	 * @param string $dstPath
	 * @param string $width
	 * @param string $height
	 * @param bool|string $lang Language code of the language to render the SVG in
	 * @throws MWException
	 * @return bool|MediaTransformError
	 */
	public function rasterize( $srcPath, $dstPath, $width, $height, $lang = false ) {
		global $wgSVGConverters, $wgSVGConverter, $wgSVGConverterPath;
		$err = false;
		$retval = '';
		if ( isset( $wgSVGConverters[$wgSVGConverter] ) ) {
			if ( is_array( $wgSVGConverters[$wgSVGConverter] ) ) {
				// This is a PHP callable
				$func = $wgSVGConverters[$wgSVGConverter][0];
				if ( !is_callable( $func ) ) {
					throw new MWException( "$func is not callable" );
				}
				$err = $func( $srcPath,
					$dstPath,
					$width,
					$height,
					$lang,
					...array_slice( $wgSVGConverters[$wgSVGConverter], 1 )
				);
				$retval = (bool)$err;
			} else {
				// External command
				$cmd = str_replace(
					[ '$path/', '$width', '$height', '$input', '$output' ],
					[ $wgSVGConverterPath ? Shell::escape( "$wgSVGConverterPath/" ) : "",
						intval( $width ),
						intval( $height ),
						Shell::escape( $srcPath ),
						Shell::escape( $dstPath ) ],
					$wgSVGConverters[$wgSVGConverter]
				);

				$env = [];
				if ( $lang !== false ) {
					$env['LANG'] = $lang;
				}

				wfDebug( __METHOD__ . ": $cmd" );
				$err = wfShellExecWithStderr( $cmd, $retval, $env );
			}
		}
		$removed = $this->removeBadFile( $dstPath, $retval );
		if ( $retval != 0 || $removed ) {
			$this->logErrorForExternalProcess( $retval, $err, $cmd );
			return new MediaTransformError( 'thumbnail_error', $width, $height, $err );
		}

		return true;
	}

	public static function rasterizeImagickExt( $srcPath, $dstPath, $width, $height ) {
		$im = new Imagick( $srcPath );
		$im->setImageFormat( 'png' );
		$im->setBackgroundColor( 'transparent' );
		$im->setImageDepth( 8 );

		if ( !$im->thumbnailImage( intval( $width ), intval( $height ), /* fit */ false ) ) {
			return 'Could not resize image';
		}
		if ( !$im->writeImage( $dstPath ) ) {
			return "Could not write to $dstPath";
		}
	}

	/**
	 * @param File|FSFile $file
	 * @param string $path Unused
	 * @param bool|array $metadata
	 * @return array|false
	 */
	public function getImageSize( $file, $path, $metadata = false ) {
		if ( $metadata === false && $file instanceof File ) {
			$metadata = $file->getMetadata();
		}
		$metadata = $this->unpackMetadata( $metadata );

		if ( isset( $metadata['width'] ) && isset( $metadata['height'] ) ) {
			return [ $metadata['width'], $metadata['height'], 'SVG',
				"width=\"{$metadata['width']}\" height=\"{$metadata['height']}\"" ];
		} else { // error
			return [ 0, 0, 'SVG', "width=\"0\" height=\"0\"" ];
		}
	}

	public function getThumbType( $ext, $mime, $params = null ) {
		return [ 'png', 'image/png' ];
	}

	/**
	 * Subtitle for the image. Different from the base
	 * class so it can be denoted that SVG's have
	 * a "nominal" resolution, and not a fixed one,
	 * as well as so animation can be denoted.
	 *
	 * @param File $file
	 * @return string
	 */
	public function getLongDesc( $file ) {
		global $wgLang;

		$metadata = $this->unpackMetadata( $file->getMetadata() );
		if ( isset( $metadata['error'] ) ) {
			return wfMessage( 'svg-long-error', $metadata['error']['message'] )->text();
		}

		$size = $wgLang->formatSize( $file->getSize() );

		if ( $this->isAnimatedImage( $file ) ) {
			$msg = wfMessage( 'svg-long-desc-animated' );
		} else {
			$msg = wfMessage( 'svg-long-desc' );
		}

		$msg->numParams( $file->getWidth(), $file->getHeight() )->params( $size );

		return $msg->parse();
	}

	/**
	 * @param File|FSFile $file
	 * @param string $filename
	 * @return string Serialised metadata
	 */
	public function getMetadata( $file, $filename ) {
		$metadata = [ 'version' => self::SVG_METADATA_VERSION ];

		try {
			$svgReader = new SVGReader( $filename );
			$metadata += $svgReader->getMetadata();
		} catch ( Exception $e ) { // @todo SVG specific exceptions
			// File not found, broken, etc.
			$metadata['error'] = [
				'message' => $e->getMessage(),
				'code' => $e->getCode()
			];
			wfDebug( __METHOD__ . ': ' . $e->getMessage() );
		}

		return serialize( $metadata );
	}

	protected function unpackMetadata( $metadata ) {
		Wikimedia\suppressWarnings();
		$unser = unserialize( $metadata );
		Wikimedia\restoreWarnings();
		if ( isset( $unser['version'] ) && $unser['version'] == self::SVG_METADATA_VERSION ) {
			return $unser;
		} else {
			return false;
		}
	}

	public function getMetadataType( $image ) {
		return 'parsed-svg';
	}

	public function isMetadataValid( $image, $metadata ) {
		$meta = $this->unpackMetadata( $metadata );
		if ( $meta === false ) {
			return self::METADATA_BAD;
		}
		if ( !isset( $meta['originalWidth'] ) ) {
			// Old but compatible
			return self::METADATA_COMPATIBLE;
		}

		return self::METADATA_GOOD;
	}

	protected function visibleMetadataFields() {
		$fields = [ 'objectname', 'imagedescription' ];

		return $fields;
	}

	/**
	 * @param File $file
	 * @param bool|IContextSource $context Context to use (optional)
	 * @return array|bool
	 */
	public function formatMetadata( $file, $context = false ) {
		$result = [
			'visible' => [],
			'collapsed' => []
		];
		$metadata = $file->getMetadata();
		if ( !$metadata ) {
			return false;
		}
		$metadata = $this->unpackMetadata( $metadata );
		if ( !$metadata || isset( $metadata['error'] ) ) {
			return false;
		}

		/* @todo Add a formatter
		$format = new FormatSVG( $metadata );
		$formatted = $format->getFormattedData();
		*/

		// Sort fields into visible and collapsed
		$visibleFields = $this->visibleMetadataFields();

		$showMeta = false;
		foreach ( $metadata as $name => $value ) {
			$tag = strtolower( $name );
			if ( isset( self::$metaConversion[$tag] ) ) {
				$tag = strtolower( self::$metaConversion[$tag] );
			} else {
				// Do not output other metadata not in list
				continue;
			}
			$showMeta = true;
			self::addMeta( $result,
				in_array( $tag, $visibleFields ) ? 'visible' : 'collapsed',
				'exif',
				$tag,
				$value
			);
		}

		return $showMeta ? $result : false;
	}

	/**
	 * @param string $name Parameter name
	 * @param mixed $value Parameter value
	 * @return bool Validity
	 */
	public function validateParam( $name, $value ) {
		if ( in_array( $name, [ 'width', 'height' ] ) ) {
			// Reject negative heights, widths
			return ( $value > 0 );
		} elseif ( $name == 'lang' ) {
			// Validate $code
			if ( $value === ''
				|| !MediaWikiServices::getInstance()->getLanguageNameUtils()
					->isValidCode( $value )
			) {
				return false;
			}

			return true;
		}

		// Only lang, width and height are acceptable keys
		return false;
	}

	/**
	 * @param array $params Name=>value pairs of parameters
	 * @return string Filename to use
	 */
	public function makeParamString( $params ) {
		$lang = '';
		$code = $this->getLanguageFromParams( $params );
		if ( $code !== 'en' ) {
			$lang = 'lang' . strtolower( $code ) . '-';
		}
		if ( !isset( $params['width'] ) ) {
			return false;
		}

		return "$lang{$params['width']}px";
	}

	public function parseParamString( $str ) {
		$m = false;
		if ( preg_match( '/^lang([a-z]+(?:-[a-z]+)*)-(\d+)px$/i', $str, $m ) ) {
			return [ 'width' => array_pop( $m ), 'lang' => $m[1] ];
		} elseif ( preg_match( '/^(\d+)px$/', $str, $m ) ) {
			return [ 'width' => $m[1], 'lang' => 'en' ];
		} else {
			return false;
		}
	}

	public function getParamMap() {
		return [ 'img_lang' => 'lang', 'img_width' => 'width' ];
	}

	/**
	 * @param array $params
	 * @return array
	 */
	protected function getScriptParams( $params ) {
		$scriptParams = [ 'width' => $params['width'] ];
		if ( isset( $params['lang'] ) ) {
			$scriptParams['lang'] = $params['lang'];
		}

		return $scriptParams;
	}

	public function getCommonMetaArray( File $file ) {
		$metadata = $file->getMetadata();
		if ( !$metadata ) {
			return [];
		}
		$metadata = $this->unpackMetadata( $metadata );
		if ( !$metadata || isset( $metadata['error'] ) ) {
			return [];
		}
		$stdMetadata = [];
		foreach ( $metadata as $name => $value ) {
			$tag = strtolower( $name );
			if ( $tag === 'originalwidth' || $tag === 'originalheight' ) {
				// Skip these. In the exif metadata stuff, it is assumed these
				// are measured in px, which is not the case here.
				continue;
			}
			if ( isset( self::$metaConversion[$tag] ) ) {
				$tag = self::$metaConversion[$tag];
				$stdMetadata[$tag] = $value;
			}
		}

		return $stdMetadata;
	}
}
