<?php
/**
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
 * Module for skin stylesheets.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class ResourceLoaderSkinModule extends ResourceLoaderFileModule {
	/**
	 * All skins are assumed to be compatible with mobile
	 */
	public $targets = [ 'desktop', 'mobile' ];

	/**
	 * Every skin should define which features it would like to reuse for core inside a
	 * ResourceLoader module that has set the class to ResourceLoaderSkinModule.
	 * For a feature to be valid it must be listed here along with the associated resources
	 *
	 * The following features are available:
	 *
	 * "logo":
	 *     Adds CSS to style an element with class `mw-wiki-logo` using the value of wgLogos['1x'].
	 *     This is enabled by default if no features are added.
	 *
	 * "normalize":
	 *     Styles needed to normalize rendering across different browser rendering engines.
	 *     All to address bugs and common browser inconsistencies for skins and extensions.
	 *     Inspired by necolas' normalize.css. This is meant to be kept lean,
	 *     basic styling beyond normalization should live in one of the following modules.
	 *
	 * "elements":
	 *     The base level that only contains the most basic of common skin styles.
	 *     Only styles for single elements are included, no styling for complex structures like the
	 *     TOC is present. This level is for skins that want to implement the entire style of even
	 *     content area structures like the TOC themselves.
	 *
	 * "content":
	 *     The most commonly used level for skins implemented from scratch. This level includes all
	 *     the single element styles from "elements" as well as styles for complex structures such
	 *     as the TOC that are output in the content area by MediaWiki rather than the skin.
	 *     Essentially this is the common level that lets skins leave the style of the content area
	 *     as it is normally styled, while leaving the rest of the skin up to the skin
	 *     implementation.
	 *
	 * "interface":
	 *     The highest level, this stylesheet contains extra common styles for classes like
	 *     .firstHeading, #contentSub, et cetera which are not outputted by MediaWiki but are common
	 *     to skins like MonoBook, Vector, etc... Essentially this level is for styles that are
	 *     common to MonoBook clones.
	 *
	 * "i18n-ordered-lists":
	 *     Styles for ordered lists elements that support mixed language content.
	 *
	 * "i18n-all-lists-margins":
	 *     Styles for margins of list elements where LTR and RTL are mixed.
	 *
	 * "i18n-headings":
	 *     Styles for line-heights of headings across different languages.
	 *
	 * "legacy":
	 *     For backwards compatibility a legacy feature is provided.
	 *     New skins should not use this if they can avoid doing so.
	 *     This feature also contains all `i18n-` prefixed features.
	 */
	private const FEATURE_FILES = [
		'logo' => [
			// Applies the logo and ensures it downloads prior to printing.
			'all' => [ 'resources/src/mediawiki.skinning/logo.less' ],
			// Reserves whitespace for the logo in a pseudo element.
			'print' => [ 'resources/src/mediawiki.skinning/logo-print.less' ],
		],
		'content' => [
			'screen' => [ 'resources/src/mediawiki.skinning/content.css' ],
		],
		'interface' => [
			'screen' => [ 'resources/src/mediawiki.skinning/interface.css' ],
		],
		'normalize' => [
			'screen' => [ 'resources/src/mediawiki.skinning/normalize.less' ],
		],
		'elements' => [
			'screen' => [ 'resources/src/mediawiki.skinning/elements.css' ],
		],
		'legacy' => [
			'all' => [ 'resources/src/mediawiki.skinning/messageBoxes.less' ],
			'print' => [ 'resources/src/mediawiki.skinning/commonPrint.css' ],
			'screen' => [ 'resources/src/mediawiki.skinning/legacy.less' ],
		],
		'i18n-ordered-lists' => [
			'screen' => [ 'resources/src/mediawiki.skinning/i18n-ordered-lists.less' ],
		],
		'i18n-all-lists-margins' => [
			'screen' => [ 'resources/src/mediawiki.skinning/i18n-all-lists-margins.less' ],
		],
		'i18n-headings' => [
			'screen' => [ 'resources/src/mediawiki.skinning/i18n-headings.less' ],
		],
	];

	/** @var string[] */
	private $features;

	/** @var array */
	private const DEFAULT_FEATURES = [
		'logo' => false,
		'content' => false,
		'interface' => false,
		'elements' => false,
		'legacy' => false,
		'i18n-ordered-lists' => false,
		'i18n-all-lists-margins' => false,
		'i18n-headings' => false,
	];

	public function __construct(
		array $options = [],
		$localBasePath = null,
		$remoteBasePath = null
	) {
		parent::__construct( $options, $localBasePath, $remoteBasePath );
		$features = $options['features'] ??
			// For historic reasons if nothing is declared logo and legacy features are enabled.
			[
				'logo' => true,
				'legacy' => true
			];
		$enabledFeatures = [];
		$compatibilityMode = false;
		foreach ( $features as $key => $enabled ) {
			if ( is_bool( $enabled ) ) {
				$enabledFeatures[$key] = $enabled;
			} else {
				// operating in array mode.
				$enabledFeatures[$enabled] = true;
				$compatibilityMode = true;
			}
		}
		// If the module didn't specify an option use the default features values.
		// This allows new features to be turned on automatically.
		if ( !$compatibilityMode ) {
			foreach ( self::DEFAULT_FEATURES as $key => $enabled ) {
				if ( !isset( $enabledFeatures[$key] ) ) {
					$enabledFeatures[$key] = $enabled;
				}
			}
		}
		$this->features = array_filter(
			array_keys( $enabledFeatures ),
			function ( $key ) use ( $enabledFeatures ) {
				return $enabledFeatures[ $key ];
			}
		);
	}

	/**
	 * Get styles defined in the module definition, plus any enabled feature styles.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyleFiles( ResourceLoaderContext $context ) {
		$styles = parent::getStyleFiles( $context );

		list( $defaultLocalBasePath, $defaultRemoteBasePath ) =
			ResourceLoaderFileModule::extractBasePaths();

		foreach ( $this->features as $feature ) {
			if ( !isset( self::FEATURE_FILES[$feature] ) ) {
				// We could be an old version of MediaWiki and a new feature is being requested (T271441).
				continue;
			}
			foreach ( self::FEATURE_FILES[$feature] as $mediaType => $files ) {
				if ( !isset( $styles[$mediaType] ) ) {
					$styles[$mediaType] = [];
				}
				foreach ( $files as $filepath ) {
					$styles[$mediaType][] = new ResourceLoaderFilePath(
						$filepath,
						$defaultLocalBasePath,
						$defaultRemoteBasePath
					);
				}
			}
		}

		return $styles;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$logo = $this->getLogoData( $this->getConfig() );
		$styles = parent::getStyles( $context );
		$this->normalizeStyles( $styles );

		$isLogoFeatureEnabled = in_array( 'logo', $this->features );
		if ( $isLogoFeatureEnabled ) {
			$default = !is_array( $logo ) ? $logo : $logo['1x'];
			$styles['all'][] = '.mw-wiki-logo { background-image: ' .
				CSSMin::buildUrlValue( $default ) .
				'; }';
			if ( is_array( $logo ) ) {
				if ( isset( $logo['svg'] ) ) {
					$styles['all'][] = '.mw-wiki-logo { ' .
						'background-image: -webkit-linear-gradient(transparent, transparent), ' .
							CSSMin::buildUrlValue( $logo['svg'] ) . '; ' .
						'background-image: linear-gradient(transparent, transparent), ' .
							CSSMin::buildUrlValue( $logo['svg'] ) . ';' .
						'background-size: 135px auto; }';
				} else {
					if ( isset( $logo['1.5x'] ) ) {
						$styles[
							'(-webkit-min-device-pixel-ratio: 1.5), ' .
							'(min--moz-device-pixel-ratio: 1.5), ' .
						'(min-resolution: 1.5dppx), ' .
							'(min-resolution: 144dpi)'
						][] = '.mw-wiki-logo { background-image: ' .
						CSSMin::buildUrlValue( $logo['1.5x'] ) . ';' .
						'background-size: 135px auto; }';
					}
					if ( isset( $logo['2x'] ) ) {
						$styles[
							'(-webkit-min-device-pixel-ratio: 2), ' .
							'(min--moz-device-pixel-ratio: 2), ' .
							'(min-resolution: 2dppx), ' .
							'(min-resolution: 192dpi)'
						][] = '.mw-wiki-logo { background-image: ' .
						CSSMin::buildUrlValue( $logo['2x'] ) . ';' .
						'background-size: 135px auto; }';
					}
				}
			}
		}

		return $styles;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getPreloadLinks( ResourceLoaderContext $context ) {
		return $this->getLogoPreloadlinks();
	}

	/**
	 * Helper method for getPreloadLinks()
	 * @return array
	 */
	private function getLogoPreloadlinks() : array {
		$logo = $this->getLogoData( $this->getConfig() );

		$logosPerDppx = [];
		$logos = [];

		$preloadLinks = [];

		if ( !is_array( $logo ) ) {
			// No media queries required if we only have one variant
			$preloadLinks[$logo] = [ 'as' => 'image' ];
			return $preloadLinks;
		}

		if ( isset( $logo['svg'] ) ) {
			// No media queries required if we only have a 1x and svg variant
			// because all preload-capable browsers support SVGs
			$preloadLinks[$logo['svg']] = [ 'as' => 'image' ];
			return $preloadLinks;
		}

		foreach ( $logo as $dppx => $src ) {
			// Keys are in this format: "1.5x"
			$dppx = substr( $dppx, 0, -1 );
			$logosPerDppx[$dppx] = $src;
		}

		// Because PHP can't have floats as array keys
		uksort( $logosPerDppx, function ( $a, $b ) {
			$a = floatval( $a );
			$b = floatval( $b );
			// Sort from smallest to largest (e.g. 1x, 1.5x, 2x)
			return $a <=> $b;
		} );

		foreach ( $logosPerDppx as $dppx => $src ) {
			$logos[] = [
				'dppx' => $dppx,
				'src' => $src
			];
		}

		$logosCount = count( $logos );
		// Logic must match ResourceLoaderSkinModule:
		// - 1x applies to resolution < 1.5dppx
		// - 1.5x applies to resolution >= 1.5dppx && < 2dppx
		// - 2x applies to resolution >= 2dppx
		// Note that min-resolution and max-resolution are both inclusive.
		for ( $i = 0; $i < $logosCount; $i++ ) {
			if ( $i === 0 ) {
				// Smallest dppx
				// min-resolution is ">=" (larger than or equal to)
				// "not min-resolution" is essentially "<"
				$media_query = 'not all and (min-resolution: ' . $logos[1]['dppx'] . 'dppx)';
			} elseif ( $i !== $logosCount - 1 ) {
				// In between
				// Media query expressions can only apply "not" to the entire expression
				// (e.g. can't express ">= 1.5 and not >= 2).
				// Workaround: Use <= 1.9999 in place of < 2.
				$upper_bound = floatval( $logos[$i + 1]['dppx'] ) - 0.000001;
				$media_query = '(min-resolution: ' . $logos[$i]['dppx'] .
					'dppx) and (max-resolution: ' . $upper_bound . 'dppx)';
			} else {
				// Largest dppx
				$media_query = '(min-resolution: ' . $logos[$i]['dppx'] . 'dppx)';
			}

			$preloadLinks[$logos[$i]['src']] = [
				'as' => 'image',
				'media' => $media_query
			];
		}

		return $preloadLinks;
	}

	/**
	 * Ensure all media keys use array values.
	 *
	 * Normalises arrays returned by the ResourceLoaderFileModule::getStyles() method.
	 *
	 * @param array &$styles Associative array, keys are strings (media queries),
	 *   values are strings or arrays
	 */
	private function normalizeStyles( array &$styles ) : void {
		foreach ( $styles as $key => $val ) {
			if ( !is_array( $val ) ) {
				$styles[$key] = [ $val ];
			}
		}
	}

	/**
	 * Return an array of all available logos that a skin may use.
	 * @since 1.35
	 * @param Config $conf
	 * @return array with the following keys:
	 *  - 1x: a square logo (required)
	 *  - 2x: a square logo for HD displays (optional)
	 *  - wordmark: a rectangle logo (wordmark) for print media and skins which desire
	 *      horizontal logo (optional)
	 */
	public static function getAvailableLogos( $conf ) : array {
		$logos = $conf->get( 'Logos' );
		if ( $logos === false ) {
			// no logos were defined... this will either
			// 1. Load from wgLogo and wgLogoHD
			// 2. Trigger runtime exception if those are not defined.
			$logos = [];
		}

		// If logos['1x'] is not defined, see if we can use wgLogo
		if ( !isset( $logos[ '1x' ] ) ) {
			$logo = $conf->get( 'Logo' );
			if ( $logo ) {
				$logos['1x'] = $logo;
			}
		}

		try {
			$logoHD = $conf->get( 'LogoHD' );
			// make sure not false
			if ( $logoHD ) {
				// wfDeprecated( __METHOD__ . ' with $wgLogoHD set instead of $wgLogos', '1.35', false, 1 );
				$logos += $logoHD;
			}
		} catch ( ConfigException $e ) {
			// no backwards compatibility changes needed.
		}

		// check the configuration is valid
		if ( !isset( $logos['1x'] ) ) {
			throw new \RuntimeException( "The key `1x` is required for wgLogos or wgLogo must be defined." );
		}
		// return the modified logos!
		return $logos;
	}

	/**
	 * @since 1.31
	 * @param Config $conf
	 * @return string|array Single url if no variants are defined,
	 *  or an array of logo urls keyed by dppx in form "<float>x".
	 *  Key "1x" is always defined. Key "svg" may also be defined,
	 *  in which case variants other than "1x" are omitted.
	 */
	protected function getLogoData( Config $conf ) {
		$logoHD = self::getAvailableLogos( $conf );
		$logo = $logoHD['1x'];

		$logo1Url = OutputPage::transformResourcePath( $conf, $logo );

		$logoUrls = [
			'1x' => $logo1Url,
		];

		if ( isset( $logoHD['svg'] ) ) {
			$logoUrls['svg'] = OutputPage::transformResourcePath(
				$conf,
				$logoHD['svg']
			);
		} elseif ( isset( $logoHD['1.5x'] ) || isset( $logoHD['2x'] ) ) {
			// Only 1.5x and 2x are supported
			if ( isset( $logoHD['1.5x'] ) ) {
				$logoUrls['1.5x'] = OutputPage::transformResourcePath(
					$conf,
					$logoHD['1.5x']
				);
			}
			if ( isset( $logoHD['2x'] ) ) {
				$logoUrls['2x'] = OutputPage::transformResourcePath(
					$conf,
					$logoHD['2x']
				);
			}
		} else {
			// Return a string rather than a one-element array, getLogoPreloadlinks depends on this
			return $logo1Url;
		}

		return $logoUrls;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		// Regardless of whether the files are specified, we always
		// provide mw-wiki-logo styles.
		return false;
	}

	/**
	 * Get language-specific LESS variables for this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getLessVars( ResourceLoaderContext $context ) {
		$lessVars = parent::getLessVars( $context );
		$logos = self::getAvailableLogos( $this->getConfig() );

		if ( isset( $logos['wordmark'] ) ) {
			$logo = $logos['wordmark'];
			$lessVars[ 'logo-enabled' ] = true;
			$lessVars[ 'logo-wordmark-url' ] = CSSMin::buildUrlValue( $logo['src'] );
			$lessVars[ 'logo-wordmark-width' ] = intval( $logo['width'] );
			$lessVars[ 'logo-wordmark-height' ] = intval( $logo['height'] );
		} else {
			$lessVars[ 'logo-enabled' ] = false;
		}
		return $lessVars;
	}

	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$summary = parent::getDefinitionSummary( $context );
		$summary[] = [
			'logos' => self::getAvailableLogos( $this->getConfig() ),
		];
		return $summary;
	}
}
