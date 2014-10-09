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
			switch ( $member ) {
				case 'images':
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
					break;

				case 'variants':
					$this->{$member} = (array)$option;
					break;
			}
		}
	}

	public function getImage( $name ) {
		$images = $this->getImages();
		return $images[$name];
	}

	public function getImages() {
		if ( !isset( $this->realImages ) ) {
			$this->realImages = array();

			foreach ( $this->images as $type => $list ) {
				foreach ( $list as $key => $value ) {
					$file = is_int( $key ) ? $value : $key;
					$options = is_array( $value ) ? $value : array();
					// TODO This is some awful code, five parameters to the constructor?
					// Should we pass $this and $context instead, and move all the code below there as well?
					$path = $this->localBasePath . '/' . $file;
					$name = isset( $options['name'] ) ? $options['name'] : pathinfo( $path, PATHINFO_FILENAME );
					$variantConfiguration = $this->variants[$type];
					$image = new ResourceLoaderImage( $this->getName(), $path, $type, $variantConfiguration, $options );
					$this->realImages[ $image->getName() ] = $image;
				}
			}
		}

		return $this->realImages;
	}

	public function getVariant( $type, $name ) {
		return isset( $this->variants[$type][$name] ) ? $this->variants[$type][$name] : null;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		// Build CSS rules
		$rules = array();
		$script = $context->getResourceLoader()->getLoadScript( $this->getSource() );

		foreach ( $this->getImages() as $name => $image ) {
			$type = $image->getType();
			$name = $image->getName();
			$ext = $image->getExtension();

			$declarations = $this->getCssDeclarations(
				$image->getDataUri( null, 'original' ),
				$image->getUrl( $script, null, 'rasterized' )
			);
			$declarations = implode( "\n\t", $declarations );
			$rules[] = ".oo-ui-$type-$name {\n\t$declarations\n}";

			// TODO: Get variant configurations from $context->getSkin()
			// TODO: Merge global and local variants
			// TODO: Customizable class names
			foreach ( $image->getVariants() as $variant ) {
				$declarations = $this->getCssDeclarations(
					$image->getDataUri( $variant, 'original' ),
					$image->getUrl( $script, $variant, 'rasterized' )
				);
				$declarations = implode( "\n\t", $declarations );
				$rules[] = ".oo-ui-$type-$name-$variant {\n\t$declarations\n}";
			}
		}

		$style = implode( "\n", $rules );
		if ( $this->getFlip( $context ) ) {
			$style = CSSJanus::transform( $style, true, false );
		}
		return array( 'all' => $style );
	}

	/**
	 * SVG support using a transparent gradient to guarantee cross-browser
	 * compatibility (browsers able to understand gradient syntax support also SVG).
	 * http://pauginer.tumblr.com/post/36614680636/invisible-gradient-technique
	 */
	protected function getCssDeclarations( $primary, $fallback ) {
		return array(
			"background-image: url($fallback);",
			"background-image: -webkit-linear-gradient(transparent, transparent), url($primary);",
			"background-image: linear-gradient(transparent, transparent), url($primary);",
		);
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
