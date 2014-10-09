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
	protected static $fileTypes = array(
		'svg' => 'image/svg+xml',
		'png' => 'image/png',
		'gif' => 'image/gif',
		'jpg' => 'image/jpg',
	);

	/**
	 * @param string $name Image name
	 * @param string $module Module name
	 * @param string $path Path to image file
	 * @param array $variants
	 * @throws MWException
	 */
	public function __construct( $name, $module, $path, $variants ) {
		$this->name = $name;
		$this->module = $module;
		$this->path = $path;
		$this->variants = $variants;

		$ext = $this->getExtension();
		if ( !isset( self::$fileTypes[$ext] ) ) {
			throw new MWException(
				"Invalid image type error. '$ext' given, svg, png, gif or jpg expected."
			);
		}
	}

	/**
	 * Get name of this image.
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get name of the module this image belongs to.
	 * @return string
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * Get the list of variants this image can be converted to.
	 * @return string[]
	 */
	public function getVariants() {
		return array_keys( $this->variants );
	}

	/**
	 * Get the extension of the image.
	 * @param string $format Format to get the extension for, 'original' or 'rasterized'
	 * @return string Extension without leading dot, e.g. 'png'
	 */
	public function getExtension( $format = 'original' ) {
		$ext = pathinfo( $this->path, PATHINFO_EXTENSION );
		if ( $format === 'rasterized' && $ext === 'svg' ) {
			return 'png';
		} else {
			return $ext;
		}
	}

	/**
	 * Get the MIME type of the image.
	 * @param string $format Format to get the MIME type for, 'original' or 'rasterized'
	 * @return string
	 */
	public function getMimeType( $format = 'original' ) {
		$ext = $this->getExtension( $format );
		return self::$fileTypes[$ext];
	}

	/**
	 * Get the load.php URL that will produce this image.
	 * @param string $script URL to load.php
	 * @param string|null $version 'version' parameter to load.php
	 * @param string|null $variant Variant to get the URL for
	 * @param string $format Format to get the URL for, 'original' or 'rasterized'
	 * @return string
	 */
	public function getUrl( $script, $version, $variant, $format ) {
		$query = array(
			'modules' => $this->getModule(),
			'image' => $this->getName(),
			'variant' => $variant,
			'format' => $format,
			'version' => $version,
		);

		return wfExpandUrl( wfAppendQuery( $script, $query ), PROTO_RELATIVE );
	}

	/**
	 * Get the data: URI that will produce this image.
	 * @param string|null $variant Variant to get the URI for
	 * @param string $format Format to get the URI for, 'original' or 'rasterized'
	 * @return string
	 */
	public function getDataUri( $variant, $format ) {
		$type = $this->getMimeType( $format );
		$contents = $this->getImageData( $variant, $format );
		return CSSMin::encodeStringAsDataURI( $contents, $type );
	}

	/**
	 * Get actual image data for this image. This can be saved to a file or sent to the browser to
	 * produce the converted image.
	 *
	 * Call getExtension() or getMimeType() with the same $format argument to learn what file type the
	 * returned data uses.
	 *
	 * @param string|null $variant Variant to get the data for
	 * @param string $format Format to get the data for, 'original' or 'rasterized'
	 * @return string Possibly binary image data
	 */
	public function getImageData( $variant, $format ) {
		if ( $this->getExtension() !== 'svg' ) {
			return file_get_contents( $this->path );
		}

		if ( $variant && isset( $this->variants[$variant] ) ) {
			$data = $this->variantize( $this->variants[$variant] );
		} else {
			$data = file_get_contents( $this->path );
		}

		if ( $format === 'rasterized' ) {
			$data = $this->rasterize( $data );
		}

		return $data;
	}

	/**
	 * Send response headers (using the header() function) that are necessary to correctly serve the
	 * image data for this image, as returned by getImageData().
	 *
	 * Note that the headers are independent of the image variant being generated.
	 *
	 * @param string $format Format to send the headers for, 'original' or 'rasterized'
	 */
	public function sendResponseHeaders( $format ) {
		$mime = $this->getMimeType( $format );
		$filename = $this->getName() . '.' . $this->getExtension( $format );

		header( 'Content-Type: ' . $mime );
		header( 'Content-Disposition: ' .
			FileBackend::makeContentDisposition( 'inline', $filename ) );
	}

	/**
	 * Convert this image, which is assumed to be SVG, to given variant.
	 * @param array $variantConf Array with a 'color' key, its value will be used as fill color
	 * @return string New SVG file data
	 */
	protected function variantize( $variantConf ) {
		$dom = new DomDocument;
		$dom->load( $this->path );
		$root = $dom->documentElement;
		$wrapper = $dom->createElement( 'g' );
		while ( $root->firstChild ) {
			$wrapper->appendChild( $root->firstChild );
		}
		$root->appendChild( $wrapper );
		$wrapper->setAttribute( 'fill', $variantConf['color'] );
		return $dom->saveXml();
	}

	/**
	 * Convert passed image data, which is assumed to be SVG, to PNG.
	 * @param string $svg SVG file data
	 * @return string|bool PNG file data, or false on failure
	 */
	protected function rasterize( $svg ) {
		// TODO Use SvgHandler::rasterize()
		$process = proc_open(
			'rsvg-convert',
			array( 0 => array( 'pipe', 'r' ), 1 => array( 'pipe', 'w' ) ),
			$pipes
		);

		if ( is_resource( $process ) ) {
			fwrite( $pipes[0], $svg );
			fclose( $pipes[0] );
			$png = stream_get_contents( $pipes[1] );
			fclose( $pipes[1] );
			proc_close( $process );

			return $png;
		}
		return false;
	}
}
