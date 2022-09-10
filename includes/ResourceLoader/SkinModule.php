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
namespace MediaWiki\ResourceLoader;

use Config;
use ConfigException;
use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use OutputPage;
use Wikimedia\Minify\CSSMin;

/**
 * Module for skin stylesheets.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class SkinModule extends LessVarFileModule {
	/**
	 * All skins are assumed to be compatible with mobile
	 */
	public $targets = [ 'desktop', 'mobile' ];

	/**
	 * Every skin should define which features it would like to reuse for core inside a
	 * ResourceLoader module that has set the class to SkinModule.
	 * For a feature to be valid it must be listed here along with the associated resources
	 *
	 * The following features are available:
	 *
	 * "accessibility":
	 *     Adds universal accessibility rules.
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
	 *     Deprecated. Alias for "content-media".
	 *
	 * "content-thumbnails":
	 *     Deprecated. Alias for "content-media".
	 *
	 * "content-media":
	 *     Styles for thumbnails and floated elements.
	 *     Will add styles for the new media structure on wikis where $wgParserEnableLegacyMediaDOM is disabled,
	 *     or $wgUseContentMediaStyles is enabled.
	 *     See https://www.mediawiki.org/wiki/Parsing/Media_structure
	 *
	 * "content-links":
	 *     The skin will apply optional styling rules for links that should be styled differently
	 *     to the rules in `elements` and `normalize`. It provides support for .mw-selflink,
	 *     a.new (red links), a.stub (stub links) and some basic styles for external links.
	 *     It also provides rules supporting the underline user preference.
	 *
	 * "content-links-external":
	 *     The skin will apply optional styling rules to links to provide icons for different file types.
	 *
	 * "content-body":
	 *     Styles for the mw-parser-output class.
	 *
	 * "content-tables":
	 *     Styles .wikitable style tables.
	 *
	 * "interface":
	 *     Shorthand for a set of styles that are common
	 *     to skins like MonoBook, Vector, etc... Essentially this level is for styles that are
	 *     common to MonoBook clones.
	 *     This enables interface-core, interface-indicators, interface-subtitle,
	 *      interface-user-message, interface-site-notice and interface-edit-section-links.
	 *
	 * "interface-category":
	 *     Styles used for styling the categories in a horizontal bar at the bottom of the content.
	 *
	 * "interface-core":
	 *     Required interface core styles. Disabling these is not recommended.
	 *
	 * "interface-edit-section-links":
	 *     Default interface styling for edit section links.
	 *
	 * "interface-indicators":
	 *     Default interface styling for indicators.
	 *
	 * "interface-message-box":
	 *     Styles for message boxes.
	 *
	 * "interface-site-notice":
	 *     Default interface styling for site notices.
	 *
	 * "interface-subtitle":
	 *     Default interface styling for subtitle area.
	 *
	 * "interface-user-message":
	 *     Default interface styling for html-user-message (you have new talk page messages box)
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
	 * "toc"
	 *     Styling rules for the table of contents.
	 *
	 * NOTE: The order of the keys defines the order in which the styles are output.
	 */
	private const FEATURE_FILES = [
		'accessibility' => [
			'all' => [ 'resources/src/mediawiki.skinning/accessibility.less' ],
		],
		'normalize' => [
			'all' => [ 'resources/src/mediawiki.skinning/normalize.less' ],
		],
		'logo' => [
			// Applies the logo and ensures it downloads prior to printing.
			'all' => [ 'resources/src/mediawiki.skinning/logo.less' ],
			// Reserves whitespace for the logo in a pseudo element.
			'print' => [ 'resources/src/mediawiki.skinning/logo-print.less' ],
		],
		'content-media' => [
			'all' => [ 'resources/src/mediawiki.skinning/content.thumbnails-common.less' ],
			'screen' => [ 'resources/src/mediawiki.skinning/content.thumbnails-screen.less' ],
			'print' => [ 'resources/src/mediawiki.skinning/content.thumbnails-print.less' ],
		],
		'content-links' => [
			'screen' => [ 'resources/src/mediawiki.skinning/content.links.less' ]
		],
		'content-links-external' => [
			'screen' => [ 'resources/src/mediawiki.skinning/content.externallinks.less' ]
		],
		'content-body' => [
			'screen' => [ 'resources/src/mediawiki.skinning/content.body.less' ],
			'print' => [ 'resources/src/mediawiki.skinning/content.body-print.less' ],
		],
		'content-tables' => [
			'screen' => [ 'resources/src/mediawiki.skinning/content.tables.less' ],
			'print' => [ 'resources/src/mediawiki.skinning/content.tables-print.less' ]
		],
		// Legacy shorthand for 6 features: interface-core, interface-edit-section-links,
		// interface-indicators, interface-subtitle, interface-site-notice, interface-user-message
		'interface' => [],
		'interface-category' => [
			'screen' => [ 'resources/src/mediawiki.skinning/interface.category.less' ],
			'print' => [ 'resources/src/mediawiki.skinning/interface.category-print.less' ],
		],
		'interface-core' => [
			'screen' => [ 'resources/src/mediawiki.skinning/interface.less' ],
			'print' => [ 'resources/src/mediawiki.skinning/interface-print.less' ],
		],
		'interface-edit-section-links' => [
			'screen' => [ 'resources/src/mediawiki.skinning/interface-edit-section-links.less' ],
		],
		'interface-indicators' => [
			'screen' => [ 'resources/src/mediawiki.skinning/interface-indicators.less' ],
		],
		'interface-site-notice' => [
			'screen' => [ 'resources/src/mediawiki.skinning/interface-site-notice.less' ],
		],
		'interface-subtitle' => [
			'screen' => [ 'resources/src/mediawiki.skinning/interface-subtitle.less' ],
		],
		'interface-message-box' => [
			'all' => [ 'resources/src/mediawiki.skinning/messageBoxes.less' ],
		],
		'interface-user-message' => [
			'screen' => [ 'resources/src/mediawiki.skinning/interface-user-message.less' ],
		],
		'elements' => [
			'screen' => [ 'resources/src/mediawiki.skinning/elements.less' ],
			'print' => [ 'resources/src/mediawiki.skinning/elements-print.less' ],
		],
		// The styles of the legacy feature was removed in 1.39. This can be removed when no skins are referencing it
		// (Dropping this line will trigger InvalidArgumentException: Feature 'legacy' is not recognised)
		'legacy' => [],
		'i18n-ordered-lists' => [
			'screen' => [ 'resources/src/mediawiki.skinning/i18n-ordered-lists.less' ],
		],
		'i18n-all-lists-margins' => [
			'screen' => [ 'resources/src/mediawiki.skinning/i18n-all-lists-margins.less' ],
		],
		'i18n-headings' => [
			'screen' => [ 'resources/src/mediawiki.skinning/i18n-headings.less' ],
		],
		'toc' => [
			'all' => [ 'resources/src/mediawiki.skinning/toc/common.css' ],
			'screen' => [ 'resources/src/mediawiki.skinning/toc/screen.less' ],
			'print' => [ 'resources/src/mediawiki.skinning/toc/print.css' ],
		],
	];

	/** @var string[] */
	private $features;

	/**
	 * Defaults for when a 'features' parameter is specified.
	 *
	 * When these apply, they are the merged into the specified options.
	 *
	 * @var array<string,bool>
	 */
	private const DEFAULT_FEATURES_SPECIFIED = [
		'accessibility' => true,
		'content-body' => true,
		'interface-core' => true,
		'toc' => true,
	];

	/**
	 * Default for when the 'features' parameter is absent.
	 *
	 * For backward-compatibility, when the parameter is not declared
	 * only 'logo' styles are loaded.
	 *
	 * @var string[]
	 */
	private const DEFAULT_FEATURES_ABSENT = [
		'logo',
	];

	private const LESS_MESSAGES = [
		// `toc` feature, used in screen.less
		'hidetoc',
		'showtoc',
	];

	/**
	 * @param array $options
	 * - features: Map from feature keys to boolean indicating whether to load
	 *   or not include the associated styles.
	 *   Keys not specified get their default from self::DEFAULT_FEATURES_SPECIFIED.
	 *
	 *   If this is set to a list of strings, then the defaults do not apply.
	 *   Use this at your own risk as it means you opt-out from backwards compatibility
	 *   provided through these defaults. For example, when features are migrated
	 *   to the SkinModule system from other parts of MediaWiki, those new feature keys
	 *   may be enabled by default, and opting out means you may be missing some styles
	 *   after an upgrade until you enable them or implement them by other means.
	 *
	 * - lessMessages: Interface message keys to export as LESS variables.
	 *   See also ResourceLoaderLessVarFileModule.
	 *
	 * @param string|null $localBasePath
	 * @param string|null $remoteBasePath
	 * @see Additonal options at $wgResourceModules
	 */
	public function __construct(
		array $options = [],
		$localBasePath = null,
		$remoteBasePath = null
	) {
		$features = $options['features'] ?? self::DEFAULT_FEATURES_ABSENT;
		$listMode = array_keys( $features ) === range( 0, count( $features ) - 1 );

		$messages = '';
		// NOTE: Compatibility is only applied when features are provided
		// in map-form. The list-form does not currently get these.
		$features = $listMode ? self::applyFeaturesCompatibility(
			array_fill_keys( $features, true ), false, $messages
		) : self::applyFeaturesCompatibility( $features, true, $messages );

		foreach ( $features as $key => $enabled ) {
			if ( !isset( self::FEATURE_FILES[$key] ) ) {
				throw new InvalidArgumentException( "Feature '$key' is not recognised" );
			}
		}

		$this->features = $listMode
			? array_keys( array_filter( $features ) )
			: array_keys( array_filter( $features + self::DEFAULT_FEATURES_SPECIFIED ) );

		// Only the `toc` feature makes use of interface messages.
		// For skins not using the `toc` feature, make sure LocalisationCache
		// remains untouched (T270027).
		if ( in_array( 'toc', $this->features ) ) {
			$options['lessMessages'] = array_merge(
				$options['lessMessages'] ?? [],
				self::LESS_MESSAGES
			);
		}

		if ( $messages !== '' ) {
			$messages .= 'More information can be found at [[mw:Manual:ResourceLoaderSkinModule]]. ';
			$options['deprecated'] = $messages;
		}
		parent::__construct( $options, $localBasePath, $remoteBasePath );
	}

	/**
	 * @internal
	 * @param array $features
	 * @param bool $addUnspecifiedFeatures whether to add new features if missing
	 * @param string &$messages to report deprecations
	 * @return array
	 */
	protected static function applyFeaturesCompatibility(
		array $features, bool $addUnspecifiedFeatures = true, &$messages = ''
	): array {
		// The `content` feature is mapped to `content-media`.
		if ( isset( $features[ 'content' ] ) ) {
			$features[ 'content-media' ] = $features[ 'content' ];
			unset( $features[ 'content' ] );
			$messages .= '[1.37] The use of the `content` feature with ResourceLoaderSkinModule'
				. ' is deprecated. Use `content-media` instead. ';
		}

		// The `content-thumbnails` feature is mapped to `content-media`.
		if ( isset( $features[ 'content-thumbnails' ] ) ) {
			$features[ 'content-media' ] = $features[ 'content-thumbnails' ];
			$messages .= '[1.37] The use of the `content-thumbnails` feature with ResourceLoaderSkinModule'
				. ' is deprecated. Use `content-media` instead. ';
			unset( $features[ 'content-thumbnails' ] );
		}

		// If `content-links` feature is set but no preference for `content-links-external` is set
		if ( $addUnspecifiedFeatures && isset( $features[ 'content-links' ] )
			&& !isset( $features[ 'content-links-external' ] )
		) {
			// Assume the same true/false preference for both.
			$features[ 'content-links-external' ] = $features[ 'content-links' ];
		}

		// The legacy feature no longer exists (T89981) but to avoid fatals in skins is retained.
		if ( isset( $features['legacy'] ) && $features['legacy'] ) {
			$messages .= '[1.37] The use of the `legacy` feature with ResourceLoaderSkinModule is deprecated'
				. '(T89981) and is a NOOP since 1.39 (T304325). This should be urgently omited to retain compatibility '
				. 'with future MediaWiki versions';
		}

		// The `content-links` feature was split out from `elements`.
		// Make sure skins asking for `elements` also get these by default.
		if ( $addUnspecifiedFeatures && isset( $features[ 'element' ] ) && !isset( $features[ 'content-links' ] ) ) {
			$features[ 'content-links' ] = $features[ 'element' ];
		}

		// `content-parser-output` was renamed to `content-body`.
		// No need to go through deprecation process here since content-parser-output added and removed in 1.36.
		// Remove this check when no matches for
		// https://codesearch.wmcloud.org/search/?q=content-parser-output&i=nope&files=&excludeFiles=&repos=
		if ( isset( $features[ 'content-parser-output' ] ) ) {
			$features[ 'content-body' ] = $features[ 'content-parser-output' ];
			unset( $features[ 'content-parser-output' ] );
		}

		// The interface module is a short hand for several modules. Enable them now.
		if ( isset( $features[ 'interface' ] ) && $features[ 'interface' ] ) {
			unset( $features[ 'interface' ] );
			$features[ 'interface-core' ] = true;
			$features[ 'interface-indicators' ] = true;
			$features[ 'interface-subtitle' ] = true;
			$features[ 'interface-user-message' ] = true;
			$features[ 'interface-site-notice' ] = true;
			$features[ 'interface-edit-section-links' ] = true;
		}
		return $features;
	}

	/**
	 * Get styles defined in the module definition, plus any enabled feature styles.
	 *
	 * @param Context $context
	 * @return string[][]
	 */
	public function getStyleFiles( Context $context ) {
		$styles = parent::getStyleFiles( $context );

		// Bypass the current module paths so that these files are served from core,
		// instead of the individual skin's module directory.
		list( $defaultLocalBasePath, $defaultRemoteBasePath ) =
			FileModule::extractBasePaths(
				[],
				null,
				$this->getConfig()->get( MainConfigNames::ResourceBasePath )
			);

		$featureFilePaths = [];

		foreach ( self::FEATURE_FILES as $feature => $featureFiles ) {
			if ( in_array( $feature, $this->features ) ) {
				foreach ( $featureFiles as $mediaType => $files ) {
					foreach ( $files as $filepath ) {
						$featureFilePaths[$mediaType][] = new FilePath(
							$filepath,
							$defaultLocalBasePath,
							$defaultRemoteBasePath
						);
					}
				}
				if ( $feature === 'content-media' && (
					!$this->getConfig()->get( MainConfigNames::ParserEnableLegacyMediaDOM ) ||
					$this->getConfig()->get( MainConfigNames::UseContentMediaStyles )
				) ) {
					$featureFilePaths['all'][] = new FilePath(
						'resources/src/mediawiki.skinning/content.media-common.less',
						$defaultLocalBasePath,
						$defaultRemoteBasePath
					);
					$featureFilePaths['screen'][] = new FilePath(
						'resources/src/mediawiki.skinning/content.media-screen.less',
						$defaultLocalBasePath,
						$defaultRemoteBasePath
					);
					$featureFilePaths['print'][] = new FilePath(
						'resources/src/mediawiki.skinning/content.media-print.less',
						$defaultLocalBasePath,
						$defaultRemoteBasePath
					);
				}
			}
		}

		// Styles defines in options are added to the $featureFilePaths to ensure
		// that $featureFilePaths styles precede module defined ones.
		// This is particularly important given the `normalize` styles need to be the first
		// outputted (see T269618).
		foreach ( $styles as $mediaType => $paths ) {
			$featureFilePaths[$mediaType] = array_merge( $featureFilePaths[$mediaType] ?? [], $paths );
		}

		return $featureFilePaths;
	}

	/**
	 * @param Context $context
	 * @return array
	 */
	public function getStyles( Context $context ) {
		$logo = $this->getLogoData( $this->getConfig(), $context->getLanguage() );
		$styles = parent::getStyles( $context );
		$this->normalizeStyles( $styles );

		$isLogoFeatureEnabled = in_array( 'logo', $this->features );
		if ( $isLogoFeatureEnabled ) {
			$default = !is_array( $logo ) ? $logo : ( $logo['1x'] ?? null );
			// Can't add logo CSS if no logo defined.
			if ( !$default ) {
				return $styles;
			}
			$styles['all'][] = '.mw-wiki-logo { background-image: ' .
				CSSMin::buildUrlValue( $default ) .
				'; }';

			if ( is_array( $logo ) ) {
				if ( isset( $logo['svg'] ) ) {
					$styles['all'][] = '.mw-wiki-logo { ' .
						'background-image: linear-gradient(transparent, transparent), ' .
							CSSMin::buildUrlValue( $logo['svg'] ) . ';' .
						'background-size: 135px auto; }';
				} else {
					if ( isset( $logo['1.5x'] ) ) {
						$styles[
							'(-webkit-min-device-pixel-ratio: 1.5), ' .
							'(min-resolution: 1.5dppx), ' .
							'(min-resolution: 144dpi)'
						][] = '.mw-wiki-logo { background-image: ' .
							CSSMin::buildUrlValue( $logo['1.5x'] ) . ';' .
							'background-size: 135px auto; }';
					}
					if ( isset( $logo['2x'] ) ) {
						$styles[
							'(-webkit-min-device-pixel-ratio: 2), ' .
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
	 * @param Context $context
	 * @return array
	 */
	public function getPreloadLinks( Context $context ): array {
		if ( !in_array( 'logo', $this->features ) ) {
			return [];
		}

		$logo = $this->getLogoData( $this->getConfig(), $context->getLanguage() );

		if ( !is_array( $logo ) ) {
			// No media queries required if we only have one variant
			return [ $logo => [ 'as' => 'image' ] ];
		}

		if ( isset( $logo['svg'] ) ) {
			// No media queries required if we only have a 1x and svg variant
			// because all preload-capable browsers support SVGs
			return [ $logo['svg'] => [ 'as' => 'image' ] ];
		}

		$logosPerDppx = [];
		foreach ( $logo as $dppx => $src ) {
			// Keys are in this format: "1.5x"
			$dppx = substr( $dppx, 0, -1 );
			$logosPerDppx[$dppx] = $src;
		}

		// Because PHP can't have floats as array keys
		uksort( $logosPerDppx, static function ( $a, $b ) {
			$a = floatval( $a );
			$b = floatval( $b );
			// Sort from smallest to largest (e.g. 1x, 1.5x, 2x)
			return $a <=> $b;
		} );

		$logos = [];
		foreach ( $logosPerDppx as $dppx => $src ) {
			$logos[] = [
				'dppx' => $dppx,
				'src' => $src
			];
		}

		$logosCount = count( $logos );
		$preloadLinks = [];
		// Logic must match SkinModule:
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
	 * Normalises arrays returned by the FileModule::getStyles() method.
	 *
	 * @param array &$styles Associative array, keys are strings (media queries),
	 *   values are strings or arrays
	 */
	private function normalizeStyles( array &$styles ): void {
		foreach ( $styles as $key => $val ) {
			if ( !is_array( $val ) ) {
				$styles[$key] = [ $val ];
			}
		}
	}

	/**
	 * Modifies configured logo width/height to ensure they are present and scalable
	 * with different font-sizes.
	 * @param array $logoElement with width, height and src keys.
	 * @return array modified version of $logoElement
	 */
	private static function getRelativeSizedLogo( array $logoElement ) {
		$width = $logoElement['width'];
		$height = $logoElement['height'];
		$widthRelative = $width / 16;
		$heightRelative = $height / 16;
		// Allow skins to scale the wordmark with browser font size (T207789)
		$logoElement['style'] = 'width: ' . $widthRelative . 'em; height: ' . $heightRelative . 'em;';
		return $logoElement;
	}

	/**
	 * Return an array of all available logos that a skin may use.
	 * @since 1.35
	 * @param Config $conf
	 * @param string|null $lang Language code for logo variant, since 1.39
	 * @return array with the following keys:
	 *  - 1x(string): a square logo composing the `icon` and `wordmark` (required)
	 *  - 2x (string): a square logo for HD displays (optional)
	 *  - wordmark (object): a rectangle logo (wordmark) for print media and skins which desire
	 *      horizontal logo (optional). Must declare width and height fields,  defined in pixels
	 *      which will be converted to ems based on 16px font-size.
	 *  - tagline (object): replaces `tagline` message in certain skins. Must declare width and
	 *      height fields defined in pixels, which are converted to ems based on 16px font-size.
	 *  - icon (string): a square logo similar to 1x, but without the wordmark. SVG recommended.
	 */
	public static function getAvailableLogos( Config $conf, string $lang = null ): array {
		$logos = $conf->get( MainConfigNames::Logos );
		if ( $logos === false ) {
			// no logos were defined... this will either
			// 1. Load from wgLogo and wgLogoHD
			// 2. Trigger runtime exception if those are not defined.
			$logos = [];
		}
		if ( $lang && isset( $logos['variants'][$lang] ) ) {
			foreach ( $logos['variants'][$lang] as $type => $value ) {
				$logos[$type] = $value;
			}
		}

		// If logos['1x'] is not defined, see if we can use wgLogo
		if ( !isset( $logos[ '1x' ] ) ) {
			$logo = $conf->get( MainConfigNames::Logo );
			if ( $logo ) {
				$logos['1x'] = $logo;
			}
		}

		try {
			$logoHD = $conf->get( MainConfigNames::LogoHD );
			// make sure not false
			if ( $logoHD ) {
				// wfDeprecated( __METHOD__ . ' with $wgLogoHD set instead of $wgLogos', '1.35', false, 1 );
				$logos += $logoHD;
			}
		} catch ( ConfigException $e ) {
			// no backwards compatibility changes needed.
		}

		if ( isset( $logos['wordmark'] ) ) {
			// Allow skins to scale the wordmark with browser font size (T207789)
			$logos['wordmark'] = self::getRelativeSizedLogo( $logos['wordmark'] );
		}
		if ( isset( $logos['tagline'] ) ) {
			$logos['tagline'] = self::getRelativeSizedLogo( $logos['tagline'] );
		}

		return $logos;
	}

	/**
	 * @since 1.31
	 * @param Config $conf
	 * @param string|null $lang Language code for logo variant, since 1.39
	 * @return string|array Single url if no variants are defined,
	 *  or an array of logo urls keyed by dppx in form "<float>x".
	 *  Key "1x" is always defined. Key "svg" may also be defined,
	 *  in which case variants other than "1x" are omitted.
	 */
	protected function getLogoData( Config $conf, string $lang = null ) {
		$logoHD = self::getAvailableLogos( $conf, $lang );
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
	 * @param Context $context
	 * @return bool
	 */
	public function isKnownEmpty( Context $context ) {
		// Regardless of whether the files are specified, we always
		// provide mw-wiki-logo styles.
		return false;
	}

	/**
	 * Get language-specific LESS variables for this module.
	 *
	 * @param Context $context
	 * @return array
	 */
	protected function getLessVars( Context $context ) {
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

	public function getDefinitionSummary( Context $context ) {
		$summary = parent::getDefinitionSummary( $context );
		$summary[] = [
			'logos' => self::getAvailableLogos( $this->getConfig() ),
		];
		return $summary;
	}
}

/** @deprecated since 1.39 */
class_alias( SkinModule::class, 'ResourceLoaderSkinModule' );
