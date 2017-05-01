<?php
/**
 * Class encapsulating an image used in a ResourceLoaderImageModule.
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
 */

/**
 * Class encapsulating an image used in a ResourceLoaderImageModule.
 *
 * @since 1.25
 */
class ResourceLoaderImage {

	/**
	 * Map of allowed file extensions to their MIME types.
	 * @var array
	 */
	protected static $fileTypes = [
		'svg' => 'image/svg+xml',
		'png' => 'image/png',
		'gif' => 'image/gif',
		'jpg' => 'image/jpg',
	];

	/**
	 * @param string $name Image name
	 * @param string $module Module name
	 * @param string|array $descriptor Path to image file, or array structure containing paths
	 * @param string $basePath Directory to which paths in descriptor refer
	 * @param array $variants
	 * @throws InvalidArgumentException
	 */
	public function __construct( $name, $module, $descriptor, $basePath, $variants ) {
		$this->name = $name;
		$this->module = $module;
		$this->descriptor = $descriptor;
		$this->basePath = $basePath;
		$this->variants = $variants;

		// Expand shorthands:
		// [ "en,de,fr" => "foo.svg" ]
		// → [ "en" => "foo.svg", "de" => "foo.svg", "fr" => "foo.svg" ]
		if ( is_array( $this->descriptor ) && isset( $this->descriptor['lang'] ) ) {
			foreach ( array_keys( $this->descriptor['lang'] ) as $langList ) {
				if ( strpos( $langList, ',' ) !== false ) {
					$this->descriptor['lang'] += array_fill_keys(
						explode( ',', $langList ),
						$this->descriptor['lang'][$langList]
					);
					unset( $this->descriptor['lang'][$langList] );
				}
			}
		}
		// Remove 'deprecated' key
		if ( is_array( $this->descriptor ) ) {
			unset( $this->descriptor[ 'deprecated' ] );
		}

		// Ensure that all files have common extension.
		$extensions = [];
		$descriptor = (array)$this->descriptor;
		array_walk_recursive( $descriptor, function ( $path ) use ( &$extensions ) {
			$extensions[] = pathinfo( $path, PATHINFO_EXTENSION );
		} );
		$extensions = array_unique( $extensions );
		if ( count( $extensions ) !== 1 ) {
			throw new InvalidArgumentException(
				"File type for different image files of '$name' not the same in module '$module'"
			);
		}
		$ext = $extensions[0];
		if ( !isset( self::$fileTypes[$ext] ) ) {
			throw new InvalidArgumentException(
				"Invalid file type for image files of '$name' (valid: svg, png, gif, jpg) in module '$module'"
			);
		}
		$this->extension = $ext;
	}

	/**
	 * Get name of this image.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get name of the module this image belongs to.
	 *
	 * @return string
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * Get the list of variants this image can be converted to.
	 *
	 * @return string[]
	 */
	public function getVariants() {
		return array_keys( $this->variants );
	}

	/**
	 * Get the path to image file for given context.
	 *
	 * @param ResourceLoaderContext $context Any context
	 * @return string
	 */
	public function getPath( ResourceLoaderContext $context ) {
		$desc = $this->descriptor;
		if ( is_string( $desc ) ) {
			return $this->basePath . '/' . $desc;
		} elseif ( isset( $desc['lang'][$context->getLanguage()] ) ) {
			return $this->basePath . '/' . $desc['lang'][$context->getLanguage()];
		} elseif ( isset( $desc[$context->getDirection()] ) ) {
			return $this->basePath . '/' . $desc[$context->getDirection()];
		} else {
			return $this->basePath . '/' . $desc['default'];
		}
	}

	/**
	 * Get the extension of the image.
	 *
	 * @param string $format Format to get the extension for, 'original' or 'rasterized'
	 * @return string Extension without leading dot, e.g. 'png'
	 */
	public function getExtension( $format = 'original' ) {
		if ( $format === 'rasterized' && $this->extension === 'svg' ) {
			return 'png';
		} else {
			return $this->extension;
		}
	}

	/**
	 * Get the MIME type of the image.
	 *
	 * @param string $format Format to get the MIME type for, 'original' or 'rasterized'
	 * @return string
	 */
	public function getMimeType( $format = 'original' ) {
		$ext = $this->getExtension( $format );
		return self::$fileTypes[$ext];
	}

	/**
	 * Get the load.php URL that will produce this image.
	 *
	 * @param ResourceLoaderContext $context Any context
	 * @param string $script URL to load.php
	 * @param string|null $variant Variant to get the URL for
	 * @param string $format Format to get the URL for, 'original' or 'rasterized'
	 * @return string
	 */
	public function getUrl( ResourceLoaderContext $context, $script, $variant, $format ) {
		$query = [
			'modules' => $this->getModule(),
			'image' => $this->getName(),
			'variant' => $variant,
			'format' => $format,
			'lang' => $context->getLanguage(),
			'skin' => $context->getSkin(),
			'version' => $context->getVersion(),
		];

		return wfAppendQuery( $script, $query );
	}

	/**
	 * Get the data: URI that will produce this image.
	 *
	 * @param ResourceLoaderContext $context Any context
	 * @param string|null $variant Variant to get the URI for
	 * @param string $format Format to get the URI for, 'original' or 'rasterized'
	 * @return string
	 */
	public function getDataUri( ResourceLoaderContext $context, $variant, $format ) {
		$type = $this->getMimeType( $format );
		$contents = $this->getImageData( $context, $variant, $format );
		return CSSMin::encodeStringAsDataURI( $contents, $type );
	}

	/**
	 * Get actual image data for this image. This can be saved to a file or sent to the browser to
	 * produce the converted image.
	 *
	 * Call getExtension() or getMimeType() with the same $format argument to learn what file type the
	 * returned data uses.
	 *
	 * @param ResourceLoaderContext $context Image context, or any context if $variant and $format
	 *     given.
	 * @param string|null $variant Variant to get the data for. Optional; if given, overrides the data
	 *     from $context.
	 * @param string $format Format to get the data for, 'original' or 'rasterized'. Optional; if
	 *     given, overrides the data from $context.
	 * @return string|false Possibly binary image data, or false on failure
	 * @throws MWException If the image file doesn't exist
	 */
	public function getImageData( ResourceLoaderContext $context, $variant = false, $format = false ) {
		if ( $variant === false ) {
			$variant = $context->getVariant();
		}
		if ( $format === false ) {
			$format = $context->getFormat();
		}

		$path = $this->getPath( $context );
		if ( !file_exists( $path ) ) {
			throw new MWException( "File '$path' does not exist" );
		}

		if ( $this->getExtension() !== 'svg' ) {
			return file_get_contents( $path );
		}

		if ( $variant && isset( $this->variants[$variant] ) ) {
			$data = $this->variantize( $this->variants[$variant], $context );
		} else {
			$data = file_get_contents( $path );
		}

		if ( $format === 'rasterized' ) {
			$data = $this->rasterize( $data );
			if ( !$data ) {
				wfDebugLog( 'ResourceLoaderImage', __METHOD__ . " failed to rasterize for $path" );
			}
		}

		return $data;
	}

	/**
	 * Send response headers (using the header() function) that are necessary to correctly serve the
	 * image data for this image, as returned by getImageData().
	 *
	 * Note that the headers are independent of the language or image variant.
	 *
	 * @param ResourceLoaderContext $context Image context
	 */
	public function sendResponseHeaders( ResourceLoaderContext $context ) {
		$format = $context->getFormat();
		$mime = $this->getMimeType( $format );
		$filename = $this->getName() . '.' . $this->getExtension( $format );

		header( 'Content-Type: ' . $mime );
		header( 'Content-Disposition: ' .
			FileBackend::makeContentDisposition( 'inline', $filename ) );
	}

	/**
	 * Convert this image, which is assumed to be SVG, to given variant.
	 *
	 * @param array $variantConf Array with a 'color' key, its value will be used as fill color
	 * @param ResourceLoaderContext $context Image context
	 * @return string New SVG file data
	 */
	protected function variantize( $variantConf, ResourceLoaderContext $context ) {
		$dom = new DomDocument;
		$dom->loadXML( file_get_contents( $this->getPath( $context ) ) );
		$root = $dom->documentElement;
		$wrapper = $dom->createElement( 'g' );
		while ( $root->firstChild ) {
			$wrapper->appendChild( $root->firstChild );
		}
		$root->appendChild( $wrapper );
		$wrapper->setAttribute( 'fill', $variantConf['color'] );
		return $dom->saveXML();
	}

	/**
	 * Massage the SVG image data for converters which don't understand some path data syntax.
	 *
	 * This is necessary for rsvg and ImageMagick when compiled with rsvg support.
	 * Upstream bug is https://bugzilla.gnome.org/show_bug.cgi?id=620923, fixed 2014-11-10, so
	 * this will be needed for a while. (T76852)
	 *
	 * @param string $svg SVG image data
	 * @return string Massaged SVG image data
	 */
	protected function massageSvgPathdata( $svg ) {
		$dom = new DomDocument;
		$dom->loadXML( $svg );
		foreach ( $dom->getElementsByTagName( 'path' ) as $node ) {
			$pathData = $node->getAttribute( 'd' );
			// Make sure there is at least one space between numbers, and that leading zero is not omitted.
			// rsvg has issues with syntax like "M-1-2" and "M.445.483" and especially "M-.445-.483".
			$pathData = preg_replace( '/(-?)(\d*\.\d+|\d+)/', ' ${1}0$2 ', $pathData );
			// Strip unnecessary leading zeroes for prettiness, not strictly necessary
			$pathData = preg_replace( '/([ -])0(\d)/', '$1$2', $pathData );
			$node->setAttribute( 'd', $pathData );
		}
		return $dom->saveXML();
	}

	/**
	 * Convert passed image data, which is assumed to be SVG, to PNG.
	 *
	 * @param string $svg SVG image data
	 * @return string|bool PNG image data, or false on failure
	 */
	protected function rasterize( $svg ) {
		/**
		 * This code should be factored out to a separate method on SvgHandler, or perhaps a separate
		 * class, with a separate set of configuration settings.
		 *
		 * This is a distinct use case from regular SVG rasterization:
		 * * We can skip many sanity and security checks (as the images come from a trusted source,
		 *   rather than from the user).
		 * * We need to provide extra options to some converters to achieve acceptable quality for very
		 *   small images, which might cause performance issues in the general case.
		 * * We want to directly pass image data to the converter, rather than a file path.
		 *
		 * See https://phabricator.wikimedia.org/T76473#801446 for examples of what happens with the
		 * default settings.
		 *
		 * For now, we special-case rsvg (used in WMF production) and do a messy workaround for other
		 * converters.
		 */

		global $wgSVGConverter, $wgSVGConverterPath;

		$svg = $this->massageSvgPathdata( $svg );

		// Sometimes this might be 'rsvg-secure'. Long as it's rsvg.
		if ( strpos( $wgSVGConverter, 'rsvg' ) === 0 ) {
			$command = 'rsvg-convert';
			if ( $wgSVGConverterPath ) {
				$command = wfEscapeShellArg( "$wgSVGConverterPath/" ) . $command;
			}

			$process = proc_open(
				$command,
				[ 0 => [ 'pipe', 'r' ], 1 => [ 'pipe', 'w' ] ],
				$pipes
			);

			if ( is_resource( $process ) ) {
				fwrite( $pipes[0], $svg );
				fclose( $pipes[0] );
				$png = stream_get_contents( $pipes[1] );
				fclose( $pipes[1] );
				proc_close( $process );

				return $png ?: false;
			}
			return false;

		} else {
			// Write input to and read output from a temporary file
			$tempFilenameSvg = tempnam( wfTempDir(), 'ResourceLoaderImage' );
			$tempFilenamePng = tempnam( wfTempDir(), 'ResourceLoaderImage' );

			file_put_contents( $tempFilenameSvg, $svg );

			$metadata = SVGMetadataExtractor::getMetadata( $tempFilenameSvg );
			if ( !isset( $metadata['width'] ) || !isset( $metadata['height'] ) ) {
				unlink( $tempFilenameSvg );
				return false;
			}

			$handler = new SvgHandler;
			$res = $handler->rasterize(
				$tempFilenameSvg,
				$tempFilenamePng,
				$metadata['width'],
				$metadata['height']
			);
			unlink( $tempFilenameSvg );

			$png = null;
			if ( $res === true ) {
				$png = file_get_contents( $tempFilenamePng );
				unlink( $tempFilenamePng );
			}

			return $png ?: false;
		}
	}
}
