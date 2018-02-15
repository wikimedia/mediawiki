<?php
/**
 * ResourceLoader module for generated and embedded images.
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
 * ResourceLoader module for generated and embedded images.
 *
 * @since 1.25
 */
class ResourceLoaderImageModule extends ResourceLoaderModule {

	protected $definition = null;

	/**
	 * Local base path, see __construct()
	 * @var string
	 */
	protected $localBasePath = '';

	protected $origin = self::ORIGIN_CORE_SITEWIDE;

	protected $images = [];
	protected $variants = [];
	protected $prefix = null;
	protected $selectorWithoutVariant = '.{prefix}-{name}';
	protected $selectorWithVariant = '.{prefix}-{name}-{variant}';
	protected $targets = [ 'desktop', 'mobile' ];

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
	 *     [
	 *         // Base path to prepend to all local paths in $options. Defaults to $IP
	 *         'localBasePath' => [base path],
	 *         // Path to JSON file that contains any of the settings below
	 *         'data' => [file path string]
	 *         // CSS class prefix to use in all style rules
	 *         'prefix' => [CSS class prefix],
	 *         // Alternatively: Format of CSS selector to use in all style rules
	 *         'selector' => [CSS selector template, variables: {prefix} {name} {variant}],
	 *         // Alternatively: When using variants
	 *         'selectorWithoutVariant' => [CSS selector template, variables: {prefix} {name}],
	 *         'selectorWithVariant' => [CSS selector template, variables: {prefix} {name} {variant}],
	 *         // List of variants that may be used for the image files
	 *         'variants' => [
	 *             // This level of nesting can be omitted if you use the same images for every skin
	 *             [skin name (or 'default')] => [
	 *                 [variant name] => [
	 *                     'color' => [color string, e.g. '#ffff00'],
	 *                     'global' => [boolean, if true, this variant is available
	 *                                  for all images of this type],
	 *                 ],
	 *                 ...
	 *             ],
	 *             ...
	 *         ],
	 *         // List of image files and their options
	 *         'images' => [
	 *             // This level of nesting can be omitted if you use the same images for every skin
	 *             [skin name (or 'default')] => [
	 *                 [icon name] => [
	 *                     'file' => [file path string or array whose values are file path strings
	 *                                    and whose keys are 'default', 'ltr', 'rtl', a single
	 *                                    language code like 'en', or a list of language codes like
	 *                                    'en,de,ar'],
	 *                     'variants' => [array of variant name strings, variants
	 *                                    available for this image],
	 *                 ],
	 *                 ...
	 *             ],
	 *             ...
	 *         ],
	 *     ]
	 * @endcode
	 * @throws InvalidArgumentException
	 */
	public function __construct( $options = [], $localBasePath = null ) {
		$this->localBasePath = self::extractLocalBasePath( $options, $localBasePath );

		$this->definition = $options;
	}

	/**
	 * Parse definition and external JSON data, if referenced.
	 */
	protected function loadFromDefinition() {
		if ( $this->definition === null ) {
			return;
		}

		$options = $this->definition;
		$this->definition = null;

		if ( isset( $options['data'] ) ) {
			$dataPath = $this->localBasePath . '/' . $options['data'];
			$data = json_decode( file_get_contents( $dataPath ), true );
			$options = array_merge( $data, $options );
		}

		// Accepted combinations:
		// * prefix
		// * selector
		// * selectorWithoutVariant + selectorWithVariant
		// * prefix + selector
		// * prefix + selectorWithoutVariant + selectorWithVariant

		$prefix = isset( $options['prefix'] ) && $options['prefix'];
		$selector = isset( $options['selector'] ) && $options['selector'];
		$selectorWithoutVariant = isset( $options['selectorWithoutVariant'] )
			&& $options['selectorWithoutVariant'];
		$selectorWithVariant = isset( $options['selectorWithVariant'] )
			&& $options['selectorWithVariant'];

		if ( $selectorWithoutVariant && !$selectorWithVariant ) {
			throw new InvalidArgumentException(
				"Given 'selectorWithoutVariant' but no 'selectorWithVariant'."
			);
		}
		if ( $selectorWithVariant && !$selectorWithoutVariant ) {
			throw new InvalidArgumentException(
				"Given 'selectorWithVariant' but no 'selectorWithoutVariant'."
			);
		}
		if ( $selector && $selectorWithVariant ) {
			throw new InvalidArgumentException(
				"Incompatible 'selector' and 'selectorWithVariant'+'selectorWithoutVariant' given."
			);
		}
		if ( !$prefix && !$selector && !$selectorWithVariant ) {
			throw new InvalidArgumentException(
				"None of 'prefix', 'selector' or 'selectorWithVariant'+'selectorWithoutVariant' given."
			);
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
					if ( !isset( $option['default'] ) ) {
						// Backwards compatibility
						$option = [ 'default' => $option ];
					}
					foreach ( $option as $skin => $data ) {
						if ( !is_array( $option ) ) {
							throw new InvalidArgumentException(
								"Invalid list error. '$option' given, array expected."
							);
						}
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
		$this->loadFromDefinition();
		return $this->prefix;
	}

	/**
	 * Get CSS selector templates used by this module.
	 * @return string
	 */
	public function getSelectors() {
		$this->loadFromDefinition();
		return [
			'selectorWithoutVariant' => $this->selectorWithoutVariant,
			'selectorWithVariant' => $this->selectorWithVariant,
		];
	}

	/**
	 * Get a ResourceLoaderImage object for given image.
	 * @param string $name Image name
	 * @param ResourceLoaderContext $context
	 * @return ResourceLoaderImage|null
	 */
	public function getImage( $name, ResourceLoaderContext $context ) {
		$this->loadFromDefinition();
		$images = $this->getImages( $context );
		return isset( $images[$name] ) ? $images[$name] : null;
	}

	/**
	 * Get ResourceLoaderImage objects for all images.
	 * @param ResourceLoaderContext $context
	 * @return ResourceLoaderImage[] Array keyed by image name
	 */
	public function getImages( ResourceLoaderContext $context ) {
		$skin = $context->getSkin();
		if ( !isset( $this->imageObjects ) ) {
			$this->loadFromDefinition();
			$this->imageObjects = [];
		}
		if ( !isset( $this->imageObjects[$skin] ) ) {
			$this->imageObjects[$skin] = [];
			if ( !isset( $this->images[$skin] ) ) {
				$this->images[$skin] = isset( $this->images['default'] ) ?
					$this->images['default'] :
					[];
			}
			foreach ( $this->images[$skin] as $name => $options ) {
				$fileDescriptor = is_string( $options ) ? $options : $options['file'];

				$allowedVariants = array_merge(
					( is_array( $options ) && isset( $options['variants'] ) ) ? $options['variants'] : [],
					$this->getGlobalVariants( $context )
				);
				if ( isset( $this->variants[$skin] ) ) {
					$variantConfig = array_intersect_key(
						$this->variants[$skin],
						array_fill_keys( $allowedVariants, true )
					);
				} else {
					$variantConfig = [];
				}

				$image = new ResourceLoaderImage(
					$name,
					$this->getName(),
					$fileDescriptor,
					$this->localBasePath,
					$variantConfig
				);
				$this->imageObjects[$skin][$image->getName()] = $image;
			}
		}

		return $this->imageObjects[$skin];
	}

	/**
	 * Get list of variants in this module that are 'global', i.e., available
	 * for every image regardless of image options.
	 * @param ResourceLoaderContext $context
	 * @return string[]
	 */
	public function getGlobalVariants( ResourceLoaderContext $context ) {
		$skin = $context->getSkin();
		if ( !isset( $this->globalVariants ) ) {
			$this->loadFromDefinition();
			$this->globalVariants = [];
		}
		if ( !isset( $this->globalVariants[$skin] ) ) {
			$this->globalVariants[$skin] = [];
			if ( !isset( $this->variants[$skin] ) ) {
				$this->variants[$skin] = isset( $this->variants['default'] ) ?
					$this->variants['default'] :
					[];
			}
			foreach ( $this->variants[$skin] as $name => $config ) {
				if ( isset( $config['global'] ) && $config['global'] ) {
					$this->globalVariants[$skin][] = $name;
				}
			}
		}

		return $this->globalVariants[$skin];
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$this->loadFromDefinition();

		// Build CSS rules
		$rules = [];
		$script = $context->getResourceLoader()->getLoadScript( $this->getSource() );
		$selectors = $this->getSelectors();

		foreach ( $this->getImages( $context ) as $name => $image ) {
			$declarations = $this->getStyleDeclarations( $context, $image, $script );
			$selector = strtr(
				$selectors['selectorWithoutVariant'],
				[
					'{prefix}' => $this->getPrefix(),
					'{name}' => $name,
					'{variant}' => '',
				]
			);
			$rules[] = "$selector {\n\t$declarations\n}";

			foreach ( $image->getVariants() as $variant ) {
				$declarations = $this->getStyleDeclarations( $context, $image, $script, $variant );
				$selector = strtr(
					$selectors['selectorWithVariant'],
					[
						'{prefix}' => $this->getPrefix(),
						'{name}' => $name,
						'{variant}' => $variant,
					]
				);
				$rules[] = "$selector {\n\t$declarations\n}";
			}
		}

		$style = implode( "\n", $rules );
		return [ 'all' => $style ];
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @param ResourceLoaderImage $image Image to get the style for
	 * @param string $script URL to load.php
	 * @param string|null $variant Variant to get the style for
	 * @return string
	 */
	private function getStyleDeclarations(
		ResourceLoaderContext $context,
		ResourceLoaderImage $image,
		$script,
		$variant = null
	) {
		$imageDataUri = $image->getDataUri( $context, $variant, 'original' );
		$primaryUrl = $imageDataUri ?: $image->getUrl( $context, $script, $variant, 'original' );
		$declarations = $this->getCssDeclarations(
			$primaryUrl,
			$image->getUrl( $context, $script, $variant, 'rasterized' )
		);
		return implode( "\n\t", $declarations );
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
		$primaryUrl = CSSMin::buildUrlValue( $primary );
		$fallbackUrl = CSSMin::buildUrlValue( $fallback );
		return [
			"background-image: $fallbackUrl;",
			"background-image: linear-gradient(transparent, transparent), $primaryUrl;",
		];
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * Get the definition summary for this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$this->loadFromDefinition();
		$summary = parent::getDefinitionSummary( $context );

		$options = [];
		foreach ( [
			'localBasePath',
			'images',
			'variants',
			'prefix',
			'selectorWithoutVariant',
			'selectorWithVariant',
		] as $member ) {
			$options[$member] = $this->{$member};
		};

		$summary[] = [
			'options' => $options,
			'fileHashes' => $this->getFileHashes( $context ),
		];
		return $summary;
	}

	/**
	 * Helper method for getDefinitionSummary.
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getFileHashes( ResourceLoaderContext $context ) {
		$this->loadFromDefinition();
		$files = [];
		foreach ( $this->getImages( $context ) as $name => $image ) {
			$files[] = $image->getPath( $context );
		}
		$files = array_values( array_unique( $files ) );
		return array_map( [ __CLASS__, 'safeFileHash' ], $files );
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

	/**
	 * @return string
	 */
	public function getType() {
		return self::LOAD_STYLES;
	}
}
