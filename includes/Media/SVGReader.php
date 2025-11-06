<?php

/**
 * Extraction of SVG image metadata.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 * @author "Derk-Jan Hartman <hartman _at_ videolan d0t org>"
 * @author Brooke Vibber
 * @copyright Copyright © 2010-2010 Brooke Vibber, Derk-Jan Hartman
 * @license GPL-2.0-or-later
 */

use MediaWiki\Language\LanguageCode;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\AtEase\AtEase;

/**
 * @ingroup Media
 */
class SVGReader {
	private const DEFAULT_WIDTH = 512;
	private const DEFAULT_HEIGHT = 512;
	private const NS_SVG = 'http://www.w3.org/2000/svg';
	public const LANG_PREFIX_MATCH = 1;
	public const LANG_FULL_MATCH = 2;

	/** @var XMLReader */
	private $reader;

	/** @var bool */
	private $mDebug = false;

	/** @var array */
	private $metadata = [];
	/** @var int[] */
	private $languages = [];
	/** @var int[] */
	private $languagePrefixes = [];

	/**
	 * Creates an SVGReader drawing from the source provided
	 * @param string $source URI from which to read
	 * @throws InvalidSVGException
	 */
	public function __construct( $source ) {
		$svgMetadataCutoff = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::SVGMetadataCutoff );
		$this->reader = new XMLReader();

		// Don't use $file->getSize() since file object passed to SVGHandler::getMetadata is bogus.
		$size = filesize( $source );
		if ( $size === false ) {
			throw new InvalidSVGException( "Error getting filesize of SVG." );
		}

		if ( $size > $svgMetadataCutoff ) {
			$this->debug( "SVG is $size bytes, which is bigger than {$svgMetadataCutoff}. Truncating." );
			$contents = file_get_contents( $source, false, null, 0, $svgMetadataCutoff );
			if ( $contents === false ) {
				throw new InvalidSVGException( 'Error reading SVG file.' );
			}
			$status = $this->reader->XML( $contents, null, LIBXML_NOERROR | LIBXML_NOWARNING );
		} else {
			$status = $this->reader->open( $source, null, LIBXML_NOERROR | LIBXML_NOWARNING );
		}
		if ( !$status ) {
			throw new InvalidSVGException( "Error getting xml of SVG." );
		}

		// Expand entities, since Adobe Illustrator uses them for xmlns
		// attributes (T33719). Note that libxml2 has some protection
		// against large recursive entity expansions so this is not as
		// insecure as it might appear to be. However, it is still extremely
		// insecure. It's necessary to wrap any read() calls with
		// libxml_disable_entity_loader() to avoid arbitrary local file
		// inclusion, or even arbitrary code execution if the expect
		// extension is installed (T48859).
		// phpcs:ignore Generic.PHP.NoSilencedErrors -- suppress deprecation per T268847
		$oldDisable = @libxml_disable_entity_loader( true );
		$this->reader->setParserProperty( XMLReader::SUBST_ENTITIES, true );

		$this->metadata['width'] = self::DEFAULT_WIDTH;
		$this->metadata['height'] = self::DEFAULT_HEIGHT;

		// The size in the units specified by the SVG file
		// (for the metadata box)
		// Per the SVG spec, if unspecified, default to '100%'
		$this->metadata['originalWidth'] = '100%';
		$this->metadata['originalHeight'] = '100%';

		// Because we cut off the end of the svg making an invalid one. Complicated
		// try catch thing to make sure warnings get restored. Seems like there should
		// be a better way.
		AtEase::suppressWarnings();
		try {
			// Note: if this throws, the width/height will be taken to be 0x0.
			// Should we consider it the default 512x512 instead?
			$this->read();
		} finally {
			libxml_disable_entity_loader( $oldDisable );
			AtEase::restoreWarnings();
		}
	}

	/**
	 * @return array Array with the known metadata
	 */
	public function getMetadata() {
		return $this->metadata;
	}

	/**
	 * Read the SVG
	 * @throws InvalidSVGException
	 * @return bool
	 */
	protected function read() {
		$keepReading = $this->reader->read();

		/* Skip until first element */
		while ( $keepReading && $this->reader->nodeType !== XMLReader::ELEMENT ) {
			$keepReading = $this->reader->read();
		}

		if ( $this->reader->localName !== 'svg' || $this->reader->namespaceURI !== self::NS_SVG ) {
			throw new InvalidSVGException( "Expected <svg> tag, got " .
				$this->reader->localName . " in NS " . $this->reader->namespaceURI );
		}
		$this->debug( '<svg> tag is correct.' );
		$this->handleSVGAttribs();

		$exitDepth = $this->reader->depth;
		$keepReading = $this->reader->read();
		while ( $keepReading ) {
			$tag = $this->reader->localName;
			$type = $this->reader->nodeType;
			$isSVG = ( $this->reader->namespaceURI === self::NS_SVG );

			$this->debug( "$tag" );

			if ( $isSVG && $tag === 'svg' && $type === XMLReader::END_ELEMENT
				&& $this->reader->depth <= $exitDepth
			) {
					break;
			}

			if ( $isSVG && $tag === 'title' ) {
				$this->readField( $tag, 'title' );
			} elseif ( $isSVG && $tag === 'desc' ) {
				$this->readField( $tag, 'description' );
			} elseif ( $isSVG && $tag === 'metadata' && $type === XMLReader::ELEMENT ) {
				$this->readXml( 'metadata' );
			} elseif ( $isSVG && $tag === 'script' ) {
				// We normally do not allow scripted svgs.
				// However its possible to configure MW to let them
				// in, and such files should be considered animated.
				$this->metadata['animated'] = true;
			} elseif ( $tag !== '#text' ) {
				$this->debug( "Unhandled top-level XML tag $tag" );

				// Recurse into children of current tag, looking for animation and languages.
				$this->animateFilterAndLang( $tag );
			}

			// Goto next element, which is sibling of current (Skip children).
			$keepReading = $this->reader->next();
		}

		$this->reader->close();

		$this->metadata['translations'] = $this->languages + $this->languagePrefixes;

		return true;
	}

	/**
	 * Read a textelement from an element
	 *
	 * @param string $name Name of the element that we are reading from
	 * @param string|null $metafield Field that we will fill with the result
	 */
	private function readField( $name, $metafield = null ) {
		$this->debug( "Read field $metafield" );
		if ( !$metafield || $this->reader->nodeType !== XMLReader::ELEMENT ) {
			return;
		}
		$keepReading = $this->reader->read();
		while ( $keepReading ) {
			if ( $this->reader->localName === $name
				&& $this->reader->namespaceURI === self::NS_SVG
				&& $this->reader->nodeType === XMLReader::END_ELEMENT
			) {
				break;
			}

			if ( $this->reader->nodeType === XMLReader::TEXT ) {
				$this->metadata[$metafield] = trim( $this->reader->value );
			}
			$keepReading = $this->reader->read();
		}
	}

	/**
	 * Read an XML snippet from an element
	 *
	 * @param string|null $metafield Field that we will fill with the result
	 */
	private function readXml( $metafield = null ) {
		$this->debug( "Read top level metadata" );
		if ( !$metafield || $this->reader->nodeType !== XMLReader::ELEMENT ) {
			return;
		}
		// @todo Find and store type of xml snippet. metadata['metadataType'] = "rdf"
		$this->metadata[$metafield] = trim( $this->reader->readInnerXml() );

		$this->reader->next();
	}

	/**
	 * Filter all children, looking for animated elements.
	 * Also get a list of languages that can be targeted.
	 *
	 * @param string $name Name of the element that we are reading from
	 */
	private function animateFilterAndLang( $name ) {
		$this->debug( "animate filter for tag $name" );
		if ( $this->reader->nodeType !== XMLReader::ELEMENT ) {
			return;
		}
		if ( $this->reader->isEmptyElement ) {
			return;
		}
		$exitDepth = $this->reader->depth;
		$keepReading = $this->reader->read();
		while ( $keepReading ) {
			if ( $this->reader->localName === $name && $this->reader->depth <= $exitDepth
				&& $this->reader->nodeType === XMLReader::END_ELEMENT
			) {
				break;
			}

			if ( $this->reader->namespaceURI === self::NS_SVG
				&& $this->reader->nodeType === XMLReader::ELEMENT
			) {
				$sysLang = $this->reader->getAttribute( 'systemLanguage' );
				if ( $sysLang !== null && $sysLang !== '' ) {
					// See https://www.w3.org/TR/SVG/struct.html#SystemLanguageAttribute
					$langList = explode( ',', $sysLang );
					foreach ( $langList as $langItem ) {
						$langItem = trim( $langItem );
						if ( LanguageCode::isWellFormedLanguageTag( $langItem ) ) {
							$this->languages[$langItem] = self::LANG_FULL_MATCH;
						}
						// Note, the standard says that any prefix should work,
						// here we do only the initial prefix, since that will catch
						// 99% of cases, and we are going to compare against fallbacks.
						// This differs mildly from how the spec says languages should be
						// handled, however it matches better how the MediaWiki language
						// preference is generally handled.
						$dash = strpos( $langItem, '-' );
						// Intentionally checking both !false and > 0 at the same time.
						if ( $dash ) {
							$itemPrefix = substr( $langItem, 0, $dash );
							if ( LanguageCode::isWellFormedLanguageTag( $itemPrefix ) ) {
								$this->languagePrefixes[$itemPrefix] = self::LANG_PREFIX_MATCH;
							}
						}
					}
				}
				switch ( $this->reader->localName ) {
					case 'style':
						$styleContents = $this->reader->readString();
						if (
							str_contains( $styleContents, 'animated' ) ||
							str_contains( $styleContents, '@keyframes' )
						) {
							$this->debug( "HOUSTON WE HAVE ANIMATION" );
							$this->metadata['animated'] = true;
						}
						break;
					case 'script':
						// Normally we disallow files with
						// <script>, but its possible
						// to configure MW to disable
						// such checks.
					case 'animate':
					case 'set':
					case 'animateMotion':
					case 'animateColor':
					case 'animateTransform':
						$this->debug( "HOUSTON WE HAVE ANIMATION" );
						$this->metadata['animated'] = true;
						break;
				}
			}
			$keepReading = $this->reader->read();
		}
	}

	private function debug( string $data ) {
		if ( $this->mDebug ) {
			wfDebug( "SVGReader: $data" );
		}
	}

	/**
	 * Parse the attributes of an SVG element
	 *
	 * The parser has to be in the start element of "<svg>"
	 */
	private function handleSVGAttribs() {
		$defaultWidth = self::DEFAULT_WIDTH;
		$defaultHeight = self::DEFAULT_HEIGHT;
		$aspect = 1.0;
		$width = null;
		$height = null;

		if ( $this->reader->getAttribute( 'viewBox' ) ) {
			// min-x min-y width height
			$viewBox = preg_split( '/\s*[\s,]\s*/', trim( $this->reader->getAttribute( 'viewBox' ) ?? '' ) );
			if ( count( $viewBox ) === 4 ) {
				$viewWidth = self::scaleSVGUnit( $viewBox[2] );
				$viewHeight = self::scaleSVGUnit( $viewBox[3] );
				if ( $viewWidth > 0 && $viewHeight > 0 ) {
					$aspect = $viewWidth / $viewHeight;
					$defaultHeight = $defaultWidth / $aspect;
				}
			}
		}
		if ( $this->reader->getAttribute( 'width' ) ) {
			$width = self::scaleSVGUnit( $this->reader->getAttribute( 'width' ) ?? '', $defaultWidth );
			$this->metadata['originalWidth'] = $this->reader->getAttribute( 'width' );
		}
		if ( $this->reader->getAttribute( 'height' ) ) {
			$height = self::scaleSVGUnit( $this->reader->getAttribute( 'height' ) ?? '', $defaultHeight );
			$this->metadata['originalHeight'] = $this->reader->getAttribute( 'height' );
		}

		if ( $width === null && $height === null ) {
			$width = $defaultWidth;
			$height = $width / $aspect;
		} elseif ( $width !== null && $height === null ) {
			$height = $width / $aspect;
		} elseif ( $height !== null && $width === null ) {
			$width = $height * $aspect;
		}

		if ( $width > 0 && $height > 0 ) {
			$this->metadata['width'] = (int)round( $width );
			$this->metadata['height'] = (int)round( $height );
		}
	}

	/**
	 * Return a rounded pixel equivalent for a labeled CSS/SVG length.
	 * https://www.w3.org/TR/SVG11/coords.html#Units
	 * https://www.w3.org/TR/css-values-3/#lengths
	 *
	 * @param string $length CSS/SVG length.
	 * @param float|int $viewportSize Optional scale for percentage units...
	 * @return float Length in pixels
	 */
	public static function scaleSVGUnit( $length, $viewportSize = 512 ) {
		// Per CSS values spec, assume 96dpi.
		static $unitLength = [
			'px' => 1.0,
			'pt' => 1.333333,
			'pc' => 16.0,
			'mm' => 3.7795275,
			'q' => 0.944881,
			'cm' => 37.795275,
			'in' => 96.0,
			'em' => 16.0, // Browser default font size if unspecified
			'rem' => 16.0,
			'ch' => 8.0, // Spec says 1em if impossible to determine
			'ex' => 8.0, // Spec says 0.5em if impossible to determine
			'' => 1.0, // "User units" pixels by default
		];
		// TODO: Does not support vw, vh, vmin, vmax.
		$matches = [];
		if ( preg_match(
			'/^\s*([-+]?\d*(?:\.\d+|\d+)(?:[Ee][-+]?\d+)?)\s*' .
			'(rem|em|ex|px|pt|pc|cm|mm|in|ch|q|%)\s*$/i',
			$length,
			$matches
		) ) {
			$length = (float)$matches[1];
			$unit = strtolower( $matches[2] );
			if ( $unit === '%' ) {
				return $length * 0.01 * $viewportSize;
			}

			return $length * $unitLength[$unit];
		}

		// Assume pixels
		return (float)$length;
	}
}
