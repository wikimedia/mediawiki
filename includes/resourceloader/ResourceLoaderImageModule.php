<?php
/**
 * Resource loader module for generated and embedded images.
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
 * @author Trevor Parscal
 */

/**
 * Resource loader module for generated and embedded images.
 * 
 * @since 1.25
 */
class ResourceLoaderImageModule extends ResourceLoaderModule {

	/* Protected Members */

	/** @var string Local base path, see __construct() */
	protected $localBasePath = '';

	protected $origin = self::ORIGIN_CORE_SITEWIDE;

	protected $images = array();

	/* Static Members */

	protected static $type = array(
		'svg' => 'image/svg+xml',
		'png' => 'image/png',
		'gif' => 'image/gif',
		'jpg' => 'image/jpg',
	);

	/* Methods */

	/**
	 * Constructs a new module from an options array.
	 *
	 * @param array $options List of options; if not given or empty, an empty module will be
	 *     constructed
	 * @param string $localBasePath Base path to prepend to all local paths in $options. Defaults
	 *     to $IP
	 *
	 * Below is a description for the $options array:
	 * @throws MWException
	 * @par Construction options:
	 * @code
	 *     array(
	 *         // Base path to prepend to all local paths in $options. Defaults to $IP
	 *         'localBasePath' => [base path],
	 *         'images' => array(
	 *             [image type] => array(
	 *                 [file path string],
	 *                 [file path string] => array(
	 *                     'name' => [image name string, defaults to file name],
	 *                     'variants' => [array of variant name strings],
	 *                 ),
	 *             )
	 *         )
	 *     )
	 * @endcode
	 */
	public function __construct( $options = array(), $localBasePath = null ) {
		$this->localBasePath = self::extractLocalBasePath( $options, $localBasePath );

		foreach ( $options as $member => $option ) {
			if ( $member === 'images' ) {
				if ( !is_array( $option ) ) {
					throw new MWException(
						"Invalid collated file path list error. " .
						"'$option' given, array expected."
					);
				}
				foreach ( $option as $key => $value ) {
					if ( !is_string( $key ) ) {
						throw new MWException(
							"Invalid collated file path list key error. " .
							"'$key' given, string expected."
						);
					}
					$this->{$member}[$key] = (array)$value;
				}
			}
		}
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		// Build CSS rules
		$rules = array();

		foreach ( $this->images as $type => $list ) {
			foreach ( $list as $key => $value ) {
				$file = is_int( $key ) ? $value : $key;
				$options = is_array( $value ) ? $value : array();
				$variants = isset( $options['variants'] ) ? $options['variants'] : array();
				$name = isset( $options['name'] ) ?
					$options['name'] : pathinfo( $file, PATHINFO_FILENAME );
				$ext = pathinfo( $file, PATHINFO_EXTENSION );
				if ( !isset( self::$types[$ext] ) ) {
					throw new MWException(
						"Invalid image type error. " .
						"'$ext' given, svg, png, gif or jpg expected."
					);
				}
				$mimetype = self::$types[$ext];
				$content = file_get_contents( $this->localBasePath . $file );
				if ( $ext === 'svg' ) {
					// TODO: Get variant configurations from $context->getSkin()
					// TODO: Merge global and local variants
					// TODO: Generate an alternate version for each variant by parsing $content
					// TODO: Add a fill attribute to groups or shapes in the SVG
					// TODO: Render PNGs for the original and each variant if $context says so
				}
				// TODO: Output rules for each variant too
				$data = base64_encode( $content );
				$rules[] = ".oo-ui-$type-$name {\n\tbackground-image: url(data:$mimetype;base64,$data);\n}";
			}
		}

		$style = implode( "\n", $rules );
		if ( $this->getFlip( $context ) ) {
			$style = CSSJanus::transform( $style, true, false );
		}
		return array( 'all' => $style );
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}

	/* Static Methods */

	/**
	 * Extract a local base path from module definition information.
	 *
	 * @param array $options Module definition
	 * @param string $localBasePath Path to use if not provided in module definition. Defaults
	 *     to $IP
	 * @return string Local base path
	 */
	public static function extractLocalBasePath( $options, $localBasePath = null ) {
		global $IP;

		if ( $localBasePath === null ) {
			$localBasePath = $IP;
		}

		if ( array_key_exists( 'localBasePath', $options ) ) {
			$localBasePath = (string)$options['localBasePath'];
		}

		return $localBasePath;
	}

}
