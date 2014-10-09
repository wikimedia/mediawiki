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

	protected static $fileTypes = array(
		'svg' => 'image/svg+xml',
		'png' => 'image/png',
		'gif' => 'image/gif',
		'jpg' => 'image/jpg',
	);

	/* Methods */

	/**
	 * @param ResourceLoader $resourceLoader
	 * @param string $module Module name
	 * @param string $path Path to image file
	 * @param string $type
	 * @param array $options List of options
	 * @throws MWException
	 * @par Construction options:
	 * @code
	 *     array(
	 *         'name' => [image name string, defaults to file name],
	 *         'variants' => [array of variant name strings],
	 *     )
	 * @endcode
	 */
	public function __construct( $resourceLoader, $module, $path, $type, $options = array() ) {
		$this->resourceLoader = $resourceLoader;
		$this->module = $module;
		$this->path = $path;
		$this->type = $type;
		$this->name = isset( $options['name'] ) ? $options['name'] : pathinfo( $path, PATHINFO_FILENAME );
		$this->variants = isset( $options['variants'] ) ? $options['variants'] : array();

		$ext = $this->getExtension();
		if ( !isset( self::$fileTypes[$ext] ) ) {
			throw new MWException(
				"Invalid image type error. '$ext' given, svg, png, gif or jpg expected."
			);
		}
	}

	public function getName() {
		return $this->name;
	}

	public function getType() {
		return $this->type;
	}

	public function getExtension() {
		return pathinfo( $this->path, PATHINFO_EXTENSION );
	}

	public function getMimeType() {
		$ext = $this->getExtension();
		return self::$fileTypes[$ext];
	}

	public function getUrl( $variant, $format ) {
		$script = $this->resourceLoader->getLoadScript( $source );
		$query = array(
			'variant' => $variant,
			'format' => $format,
			'module' => $this->getModule(),
			'image' => $this->getImage(),
		);

		return wfExpandUrl( wfAppendQuery( $script, $query ), PROTO_RELATIVE );
	}

	public function getDataUri( $variant, $format ) {
		$mimetype = $this->getMimeType(); // TODO handle $format
		$content = $this->getImageData( $variant, $format );
		$data = base64_encode( $content );
		return "data:$mimetype;base64,$data";
	}

	public function getImageData( $variant, $format ) {
		if ( $format === 'original' ) {
			$data = file_get_contents( $this->path );
			if ( $variant ) {
				// Not implemented yet
			}
			return $data;
		} elseif ( $format === 'rasterize' ) {
			// Not implemented yet
		} else {
			throw new MwException( "Invalid format specified" );
		}
	}

	public function respond( $variant, $format ) {
		// TODO Cache headers
		header( "Content-Type: " . $this->getMimeType() );
		header( "Content-Disposition: " .
			FileBackend::makeContentDisposition( 'inline', $this->getName() . '.' . $this->getExtension() ) );
		echo $this->getImageData( $variant, $format );
	}
}
