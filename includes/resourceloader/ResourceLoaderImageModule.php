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

	/**
	 * Local base path, see __construct()
	 * @var string
	 */
	protected $localBasePath = '';

	protected $origin = self::ORIGIN_CORE_SITEWIDE;

	protected $images = array();
	protected $variants = array();
	protected $prefix = array();
	protected $targets = array( 'desktop', 'mobile' );

	/**
	 * Constructs a new module from an options array.
	 *
	 * @param array $options List of options; if not given or empty, an empty module will be
	 *     constructed
	 * @param string $localBasePath Base path to prepend to all local paths in $options. Defaults
	 *     to $IP
	 *
	 * Below is a description for the $options array:
	 * @par Construction options:
	 * @code
	 *     array(
	 *         // Base path to prepend to all local paths in $options. Defaults to $IP
	 *         'localBasePath' => [base path],
	 *         // CSS class prefix to use in all style rules
	 *         'prefix' => [CSS class prefix],
	 *         // List of variants that may be used for the image files
	 *         'variants' => array(
	 *             // ([image type] is a string, used in generated CSS class
	 *             //  names and to match variants to images)
	 *             [image type] => array(
	 *                 [variant name] => array(
	 *                     'color' => [color string, e.g. '#ffff00'],
	 *                     'global' => [boolean, if true, this variant is available
	 *                                  for all images of this type],
	 *                 ),
	 *             )
	 *         ),
	 *         // List of image files and their options
	 *         'images' => array(
	 *             [image type] => array(
	 *                 [file path string],
	 *                 [file path string] => array(
	 *                     'name' => [image name string, defaults to file name],
	 *                     'variants' => [array of variant name strings, variants
	 *                                    available for this image],
	 *                 ),
	 *             )
	 *         ),
	 *     )
	 * @endcode
	 * @throws MWException
	 */
	public function __construct( $options = array(), $localBasePath = null ) {
		$this->localBasePath = self::extractLocalBasePath( $options, $localBasePath );

		if ( !isset( $options['prefix'] ) || !$options['prefix'] ) {
			throw new MWException(
				"Required 'prefix' option not given or empty."
			);
		}

		foreach ( $options as $member => $option ) {
			switch ( $member ) {
				case 'images':
					if ( !is_array( $option ) ) {
						throw new MWException(
							"Invalid collated file path list error. '$option' given, array expected."
						);
					}
					foreach ( $option as $key => $value ) {
						if ( !is_string( $key ) ) {
							throw new MWException(
								"Invalid collated file path list key error. '$key' given, string expected."
							);
						}
						$this->{$member}[$key] = (array)$value;
					}
					break;

				case 'variants':
					if ( !is_array( $option ) ) {
						throw new MWException(
							"Invalid variant list error. '$option' given, array expected."
						);
					}
					$this->{$member} = $option;
					break;

				case 'prefix':
					$this->{$member} = (string)$option;
					break;
			}
		}
	}

	/**
	 * Get CSS class prefix used by this module.
	 * @return string
	 */
	public function getPrefix() {
		return $this->prefix;
	}

	/**
	 * Get a ResourceLoaderImage object for given image.
	 * @param string $name Image name
	 * @return ResourceLoaderImage|null
	 */
	public function getImage( $name ) {
		$images = $this->getImages();
		return isset( $images[$name] ) ? $images[$name] : null;
	}

	/**
	 * Get ResourceLoaderImage objects for all images.
	 * @return ResourceLoaderImage[] Array keyed by image name
	 */
	public function getImages() {
		if ( !isset( $this->imageObjects ) ) {
			$this->imageObjects = array();

			foreach ( $this->images as $type => $list ) {
				foreach ( $list as $name => $options ) {
					$imageDesc = is_string( $options ) ? $options : $options['image'];

					$allowedVariants = array_merge(
						isset( $options['variants'] ) ? $options['variants'] : array(),
						$this->getGlobalVariants( $type )
					);
					if ( isset( $this->variants[$type] ) ) {
						$variantConfig = array_intersect_key(
							$this->variants[$type],
							array_fill_keys( $allowedVariants, true )
						);
					} else {
						$variantConfig = array();
					}

					$image = new ResourceLoaderImage(
						$name,
						$this->getName(),
						$imageDesc,
						$this->localBasePath,
						$variantConfig
					);
					$this->imageObjects[ $image->getName() ] = $image;
				}
			}
		}

		return $this->imageObjects;
	}

	/**
	 * Get list of variants in this module that are 'global' for given type of images, i.e., available
	 * for every image of given type regardless of image options.
	 * @param string $type Image type
	 * @return string[]
	 */
	public function getGlobalVariants( $type ) {
		if ( !isset( $this->globalVariants[$type] ) ) {
			$this->globalVariants[$type] = array();

			if ( isset( $this->variants[$type] ) ) {
				foreach ( $this->variants[$type] as $name => $config ) {
					if ( isset( $config['global'] ) && $config['global'] ) {
						$this->globalVariants[$type][] = $name;
					}
				}
			}
		}

		return $this->globalVariants[$type];
	}

	/**
	 * Get the type of given image.
	 * @param string $imageName Image name
	 * @return string
	 */
	public function getImageType( $imageName ) {
		foreach ( $this->images as $type => $list ) {
			foreach ( $list as $key => $value ) {
				$file = is_int( $key ) ? $value : $key;
				$options = is_array( $value ) ? $value : array();
				$name = isset( $options['name'] ) ? $options['name'] : pathinfo( $file, PATHINFO_FILENAME );
				if ( $name === $imageName ) {
					return $type;
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
		$script = $context->getResourceLoader()->getLoadScript( $this->getSource() );
		$prefix = $this->getPrefix();

		foreach ( $this->getImages() as $name => $image ) {
			$type = $this->getImageType( $name );

			$declarations = $this->getCssDeclarations(
				$image->getDataUri( $context, null, 'original' ),
				$image->getUrl( $context, $script, null, 'rasterized' )
			);
			$declarations = implode( "\n\t", $declarations );
			$rules[] = ".$prefix-$type-$name {\n\t$declarations\n}";

			// TODO: Get variant configurations from $context->getSkin()
			foreach ( $image->getVariants() as $variant ) {
				$declarations = $this->getCssDeclarations(
					$image->getDataUri( $context, $variant, 'original' ),
					$image->getUrl( $context, $script, $variant, 'rasterized' )
				);
				$declarations = implode( "\n\t", $declarations );
				$rules[] = ".$prefix-$type-$name-$variant {\n\t$declarations\n}";
			}
		}

		$style = implode( "\n", $rules );
		if ( $this->getFlip( $context ) ) {
			$style = CSSJanus::transform( $style, true, false );
		}
		return array( 'all' => $style );
	}

	/**
	 * @param string $primary Primary URI
	 * @param string $fallback Fallback URI
	 * @return string[] CSS declarations to use given URIs as background-image
	 */
	protected function getCssDeclarations( $primary, $fallback ) {
		// SVG support using a transparent gradient to guarantee cross-browser
		// compatibility (browsers able to understand gradient syntax support also SVG).
		// http://pauginer.tumblr.com/post/36614680636/invisible-gradient-technique
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
