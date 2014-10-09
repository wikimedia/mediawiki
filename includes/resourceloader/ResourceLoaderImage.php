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
	 * @param string $name Image name
	 * @param string $module Module name
	 * @param string $path Path to image file
	 * @param array $variantConfiguration
	 * @param array $allowedVariants
	 * @throws MWException
	 */
	public function __construct( $name, $module, $path, $variantConfiguration, $allowedVariants ) {
		$this->name = $name;
		$this->module = $module;
		$this->path = $path;
		$this->allowedVariants = $allowedVariants;
		$this->variantConfiguration = $variantConfiguration;

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

	public function getVariants() {
		return $this->allowedVariants;
	}

	public function getModule() {
		return $this->module;
	}

	public function getExtension( $format = 'original' ) {
		$ext = pathinfo( $this->path, PATHINFO_EXTENSION );
		if ( $format === 'rasterized' && $ext === 'svg' ) {
			return 'png';
		} else {
			return $ext;
		}
	}

	public function getMimeType( $format = 'original' ) {
		$ext = $this->getExtension( $format );
		return self::$fileTypes[$ext];
	}

	public function getUrl( $script, $version, $variant, $format ) {
		$query = array(
			'variant' => $variant,
			'format' => $format,
			'modules' => $this->getModule(),
			'image' => $this->getName(),
			'version' => $version,
		);

		return wfExpandUrl( wfAppendQuery( $script, $query ), PROTO_RELATIVE );
	}

	public function getDataUri( $variant, $format ) {
		$type = $this->getMimeType( $format );
		$contents = $this->getImageData( $variant, $format );
		return CSSMin::encodeStringAsDataURI( $contents, $type );
	}

	public function getImageData( $variant, $format ) {
		if ( $this->getExtension() !== 'svg' ) {
			return file_get_contents( $this->path );
		}

		if ( $variant && in_array( $variant, $this->getVariants() ) ) {
			$data = $this->variantize( $this->variantConfiguration[$variant] );
		} else {
			$data = file_get_contents( $this->path );
		}

		if ( $format === 'rasterized' ) {
			$data = $this->rasterize( $data );
		}

		return $data;
	}

	public function sendResponseHeaders( $format ) {
		$mime = $this->getMimeType( $format );
		$filename = $this->getName() . '.' . $this->getExtension( $format );

		header( 'Content-Type: ' . $mime );
		header( 'Content-Disposition: ' .
			FileBackend::makeContentDisposition( 'inline', $filename ) );
	}

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

	protected function rasterize( $svg ) {
		// TODO SvgHandler::rasterize()
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
