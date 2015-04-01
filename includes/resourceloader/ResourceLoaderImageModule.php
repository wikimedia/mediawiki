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
	protected $prefix = null;
	protected $selectorWithoutVariant = '.{prefix}-{name}';
	protected $selectorWithVariant = '.{prefix}-{name}-{variant}';
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
	 *         // Alternatively: Format of CSS selector to use in all style rules
	 *         'selector' => [CSS selector template, variables: {prefix} {name} {variant}],
	 *         // Alternatively: When using variants
	 *         'selectorWithoutVariant' => [CSS selector template, variables: {prefix} {name}],
	 *         'selectorWithVariant' => [CSS selector template, variables: {prefix} {name} {variant}],
	 *         // List of variants that may be used for the image files
	 *         'variants' => array(
	 *                 [variant name] => array(
	 *                     'color' => [color string, e.g. '#ffff00'],
	 *                     'global' => [boolean, if true, this variant is available
	 *                                  for all images of this type],
	 *                 ),
	 *             ...
	 *         ),
	 *         // List of image files and their options
	 *         'images' => array(
	 *                 [file path string],
	 *                 [file path string] => array(
	 *                     'name' => [image name string, defaults to file name],
	 *                     'variants' => [array of variant name strings, variants
	 *                                    available for this image],
	 *                 ),
	 *             ...
	 *         ),
	 *     )
	 * @endcode
	 * @throws InvalidArgumentException
	 */
	public function __construct( $options = array(), $localBasePath = null ) {
		$this->localBasePath = self::extractLocalBasePath( $options, $localBasePath );

		// Accepted combinations:
		// * prefix
		// * selector
		// * selectorWithoutVariant + selectorWithVariant
		// * prefix + selector
		// * prefix + selectorWithoutVariant + selectorWithVariant

		$prefix = isset( $options['prefix'] ) && $options['prefix'];
		$selector = isset( $options['selector'] ) && $options['selector'];
		$selectorWithoutVariant = isset( $options['selectorWithoutVariant'] ) && $options['selectorWithoutVariant'];
		$selectorWithVariant = isset( $options['selectorWithVariant'] ) && $options['selectorWithVariant'];

		if ( $selectorWithoutVariant && !$selectorWithVariant ) {
			throw new InvalidArgumentException( "Given 'selectorWithoutVariant' but no 'selectorWithVariant'." );
		}
		if ( $selectorWithVariant && !$selectorWithoutVariant ) {
			throw new InvalidArgumentException( "Given 'selectorWithVariant' but no 'selectorWithoutVariant'." );
		}
		if ( $selector && $selectorWithVariant ) {
			throw new InvalidArgumentException( "Incompatible 'selector' and 'selectorWithVariant'+'selectorWithoutVariant' given." );
		}
		if ( !$prefix && !$selector && !$selectorWithVariant ) {
			throw new InvalidArgumentException( "None of 'prefix', 'selector' or 'selectorWithVariant'+'selectorWithoutVariant' given." );
		}

		foreach ( $options as $member => $option ) {
			switch ( $member ) {
				case 'images':
				case 'variants':
					if ( !is_array( $option ) ) {
						throw new InvalidArgumentException(
							"Invalid list error. '$option' given, array expected."
						);
					}
					$this->{$member} = $option;
					break;

				case 'prefix':
				case 'selectorWithoutVariant':
				case 'selectorWithVariant':
					$this->{$member} = (string)$option;
					break;

				case 'selector':
					$this->selectorWithoutVariant = $this->selectorWithVariant = (string)$option;
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
	 * Get CSS selector templates used by this module.
	 * @return string
	 */
	public function getSelectors() {
		return array(
			'selectorWithoutVariant' => $this->selectorWithoutVariant,
			'selectorWithVariant' => $this->selectorWithVariant,
		);
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

			foreach ( $this->images as $name => $options ) {
				$fileDescriptor = is_string( $options ) ? $options : $options['file'];

				$allowedVariants = array_merge(
					is_array( $options ) && isset( $options['variants'] ) ? $options['variants'] : array(),
					$this->getGlobalVariants()
				);
				if ( isset( $this->variants ) ) {
					$variantConfig = array_intersect_key(
						$this->variants,
						array_fill_keys( $allowedVariants, true )
					);
				} else {
					$variantConfig = array();
				}

				$image = new ResourceLoaderImage(
					$name,
					$this->getName(),
					$fileDescriptor,
					$this->localBasePath,
					$variantConfig
				);
				$this->imageObjects[ $image->getName() ] = $image;
			}
		}

		return $this->imageObjects;
	}

	/**
	 * Get list of variants in this module that are 'global', i.e., available
	 * for every image regardless of image options.
	 * @return string[]
	 */
	public function getGlobalVariants() {
		if ( !isset( $this->globalVariants ) ) {
			$this->globalVariants = array();

			if ( isset( $this->variants ) ) {
				foreach ( $this->variants as $name => $config ) {
					if ( isset( $config['global'] ) && $config['global'] ) {
						$this->globalVariants[] = $name;
					}
				}
			}
		}

		return $this->globalVariants;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		// Build CSS rules
		$rules = array();
		$script = $context->getResourceLoader()->getLoadScript( $this->getSource() );
		$selectors = $this->getSelectors();

		foreach ( $this->getImages() as $name => $image ) {
			$declarations = $this->getCssDeclarations(
				$image->getDataUri( $context, null, 'original' ),
				$image->getUrl( $context, $script, null, 'rasterized' )
			);
			$declarations = implode( "\n\t", $declarations );
			$selector = strtr(
				$selectors['selectorWithoutVariant'],
				array(
					'{prefix}' => $this->getPrefix(),
					'{name}' => $name,
					'{variant}' => '',
				)
			);
			$rules[] = "$selector {\n\t$declarations\n}";

			foreach ( $image->getVariants() as $variant ) {
				$declarations = $this->getCssDeclarations(
					$image->getDataUri( $context, $variant, 'original' ),
					$image->getUrl( $context, $script, $variant, 'rasterized' )
				);
				$declarations = implode( "\n\t", $declarations );
				$selector = strtr(
					$selectors['selectorWithVariant'],
					array(
						'{prefix}' => $this->getPrefix(),
						'{name}' => $name,
						'{variant}' => $variant,
					)
				);
				$rules[] = "$selector {\n\t$declarations\n}";
			}
		}

		$style = implode( "\n", $rules );
		return array( 'all' => $style );
	}

	/**
	 * SVG support using a transparent gradient to guarantee cross-browser
	 * compatibility (browsers able to understand gradient syntax support also SVG).
	 * http://pauginer.tumblr.com/post/36614680636/invisible-gradient-technique
	 *
	 * Keep synchronized with the .background-image-svg LESS mixin in
	 * /resources/src/mediawiki.less/mediawiki.mixins.less.
	 *
	 * @param string $primary Primary URI
	 * @param string $fallback Fallback URI
	 * @return string[] CSS declarations to use given URIs as background-image
	 */
	protected function getCssDeclarations( $primary, $fallback ) {
		return array(
			"background-image: url($fallback);",
			"background-image: -webkit-linear-gradient(transparent, transparent), url($primary);",
			"background-image: linear-gradient(transparent, transparent), url($primary);",
			"background-image: -o-linear-gradient(transparent, transparent), url($fallback);",
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
