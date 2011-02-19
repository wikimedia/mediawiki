<?php
/**
 * SVGMetadataExtractor.php
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
 * @ingroup Media
 * @author Derk-Jan Hartman <hartman _at_ videolan d0t org>
 * @author Brion Vibber
 * @copyright Copyright © 2010-2010 Brion Vibber, Derk-Jan Hartman
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

class SVGMetadataExtractor {
	static function getMetadata( $filename ) {
		$svg = new SVGReader( $filename );
		return $svg->getMetadata();
	}
}

class SVGReader {
	const DEFAULT_WIDTH = 512;
	const DEFAULT_HEIGHT = 512;

	private $reader = null;
	private $mDebug = false;
	private $metadata = Array();

	/**
	 * Constructor
	 *
	 * Creates an SVGReader drawing from the source provided
	 * @param $source String: URI from which to read
	 */
	function __construct( $source ) {
		$this->reader = new XMLReader();
		$this->reader->open( $source, null, LIBXML_NOERROR | LIBXML_NOWARNING );
		#$this->reader->setParserProperty( XMLReader::DEFAULTATTRS, FALSE );
		#$this->reader->setParserProperty( XMLReader::DEFAULTATTRS, FALSE );
		#$this->reader->setParserProperty( XMLReader::VALIDATE , FALSE );

		$this->metadata['width'] = self::DEFAULT_WIDTH;
		$this->metadata['height'] = self::DEFAULT_HEIGHT;

		$this->read();
	}

	/*
	 * @return Array with the known metadata
	 */
	public function getMetadata() {
		return $this->metadata;
	}

	/*
	 * Read the SVG
	 */
	public function read() {
		$keepReading = $this->reader->read();

		/* Skip until first element */
		while( $keepReading && $this->reader->nodeType != XmlReader::ELEMENT ) {
			$keepReading = $this->reader->read();
		}

		if ( !$this->qualifiedNameEquals( $this->reader->name, 'svg', 'svg' ) ) {
			throw new MWException( "Expected <svg> tag, got ".
				$this->reader->name );
		}
		$this->debug( "<svg> tag is correct." );
		$this->handleSVGAttribs();

		$exitDepth =  $this->reader->depth;
		$keepReading = $this->reader->read();
		$skip = false;
		while ( $keepReading ) {
			$tag = $this->reader->name;
			$type = $this->reader->nodeType;

			$this->debug( "$tag" );

			if ( $this->qualifiedNameEquals( $tag, 'svg', 'svg' ) && $type == XmlReader::END_ELEMENT && $this->reader->depth <= $exitDepth ) {
				break;
			} elseif ( $this->qualifiedNameEquals( $tag, 'svg', 'title' ) ) {
				$this->readField( $tag, 'title' );
			} elseif ( $this->qualifiedNameEquals( $tag, 'svg', 'desc' )  ) {
				$this->readField( $tag, 'description' );
			} elseif ( $this->qualifiedNameEquals( $tag, 'svg', 'metadata' ) && $type == XmlReader::ELEMENT ) {
				$this->readXml( $tag, 'metadata' );
			} elseif ( $tag !== '#text' ) {
				$this->debug( "Unhandled top-level XML tag $tag" );
				$this->animateFilter( $tag );
				//$skip = true;
			}

			if ($skip) {
				$keepReading = $this->reader->next();
				$skip = false;
				$this->debug( "Skip" );
			} else {
				$keepReading = $this->reader->read();
			}
		}

		$this->reader->close();

		return true;
	}

	/*
	 * Read a textelement from an element
	 *
	 * @param String $name of the element that we are reading from
	 * @param String $metafield that we will fill with the result
	 */
	private function readField( $name, $metafield=null ) {
		$this->debug ( "Read field $metafield" );
		if( !$metafield || $this->reader->nodeType != XmlReader::ELEMENT ) {
			return;
		}
		$keepReading = $this->reader->read();
		while( $keepReading ) {
			if( $this->reader->name == $name && $this->reader->nodeType == XmlReader::END_ELEMENT ) {
				break;
			} elseif( $this->reader->nodeType == XmlReader::TEXT ){
				$this->metadata[$metafield] = $this->reader->value;
			}
			$keepReading = $this->reader->read();
		}
	}

	/*
	 * Read an XML snippet from an element
	 *
	 * @param String $metafield that we will fill with the result
	 */
	private function readXml( $metafield=null ) {
		$this->debug ( "Read top level metadata" );
		if( !$metafield || $this->reader->nodeType != XmlReader::ELEMENT ) {
			return;
		}
		// TODO: find and store type of xml snippet. metadata['metadataType'] = "rdf"
		$this->metadata[$metafield] = $this->reader->readInnerXML();
		$this->reader->next();
	}

	/*
	 * Filter all children, looking for animate elements
	 *
	 * @param String $name of the element that we are reading from
	 */
	private function animateFilter( $name ) {
		$this->debug ( "animate filter" );
		if( $this->reader->nodeType != XmlReader::ELEMENT ) {
			return;
		}
		$exitDepth =  $this->reader->depth;
		$keepReading = $this->reader->read();
		while( $keepReading ) {
			if( $this->reader->name == $name && $this->reader->depth <= $exitDepth
				&& $this->reader->nodeType == XmlReader::END_ELEMENT ) {
				break;
			} elseif ( $this->reader->nodeType == XmlReader::ELEMENT ) {
				switch( $this->reader->name ) {
					case 'animate':
					case 'svg:animate':
					case 'set':
					case 'svg:set':
					case 'animateMotion':
					case 'svg:animateMotion':
					case 'animateColor':
					case 'svg:animateColor':
					case 'animateTransform':
					case 'svg:animateTransform':
						$this->debug( "HOUSTON WE HAVE ANIMATION" );
						$this->metadata['animated'] = true;
						break;
				}
			}
			$keepReading = $this->reader->read();
		}
	}

	private function throwXmlError( $err ) {
		$this->debug( "FAILURE: $err" );
		wfDebug( "SVGReader XML error: $err\n" );
	}

	private function debug( $data ) {
		if( $this->mDebug ) {
			wfDebug( "SVGReader: $data\n" );
		}
	}

	private function warn( $data ) {
		wfDebug( "SVGReader: $data\n" );
	}

	private function notice( $data ) {
		wfDebug( "SVGReader WARN: $data\n" );
	}

	/*
	 * Parse the attributes of an SVG element
	 *
	 * The parser has to be in the start element of <svg>
	 */
	private function handleSVGAttribs( ) {
		$defaultWidth = self::DEFAULT_WIDTH;
		$defaultHeight = self::DEFAULT_HEIGHT;
		$aspect = 1.0;
		$width = null;
		$height = null;

		if( $this->reader->getAttribute('viewBox') ) {
			// min-x min-y width height
			$viewBox = preg_split( '/\s+/', trim( $this->reader->getAttribute('viewBox') ) );
			if( count( $viewBox ) == 4 ) {
				$viewWidth = $this->scaleSVGUnit( $viewBox[2] );
				$viewHeight = $this->scaleSVGUnit( $viewBox[3] );
				if( $viewWidth > 0 && $viewHeight > 0 ) {
					$aspect = $viewWidth / $viewHeight;
					$defaultHeight = $defaultWidth / $aspect;
				}
			}
		}
		if( $this->reader->getAttribute('width') ) {
			$width = $this->scaleSVGUnit( $this->reader->getAttribute('width'), $defaultWidth );
		}
		if( $this->reader->getAttribute('height') ) {
			$height = $this->scaleSVGUnit( $this->reader->getAttribute('height'), $defaultHeight );
		}

		if( !isset( $width ) && !isset( $height ) ) {
			$width = $defaultWidth;
			$height = $width / $aspect;
		} elseif( isset( $width ) && !isset( $height ) ) {
			$height = $width / $aspect;
		} elseif( isset( $height ) && !isset( $width ) ) {
			$width = $height * $aspect;
		}

		if( $width > 0 && $height > 0 ) {
			$this->metadata['width'] = intval( round( $width ) );
			$this->metadata['height'] = intval( round( $height ) );
		}
	}

	/**
	 * Return a rounded pixel equivalent for a labeled CSS/SVG length.
	 * http://www.w3.org/TR/SVG11/coords.html#UnitIdentifiers
	 *
	 * @param $length String: CSS/SVG length.
	 * @param $viewportSize: Float optional scale for percentage units...
	 * @return float: length in pixels
	 */
	static function scaleSVGUnit( $length, $viewportSize=512 ) {
		static $unitLength = array(
			'px' => 1.0,
			'pt' => 1.25,
			'pc' => 15.0,
			'mm' => 3.543307,
			'cm' => 35.43307,
			'in' => 90.0,
			'em' => 16.0, // fake it?
			'ex' => 12.0, // fake it?
			''   => 1.0, // "User units" pixels by default
			);
		$matches = array();
		if( preg_match( '/^\s*(\d+(?:\.\d+)?)(em|ex|px|pt|pc|cm|mm|in|%|)\s*$/', $length, $matches ) ) {
			$length = floatval( $matches[1] );
			$unit = $matches[2];
			if( $unit == '%' ) {
				return $length * 0.01 * $viewportSize;
			} else {
				return $length * $unitLength[$unit];
			}
		} else {
			// Assume pixels
			return floatval( $length );
		}
	}

	/**
	 * XML namespace check
	 *
	 * Check if a read node name matches the expected nodeName
	 * @param $qualifiedName as read by XMLReader
	 * @param $prefix the namespace prefix that you expect for this element, defaults to svg namespace
	 * @param $localName the localName part of the element that you want to match
	 *
	 * @return boolean
	 */
	private function qualifiedNameEquals( $qualifiedName, $prefix="svg", $localName ) {
		if( ($qualifiedName == $localName && $prefix == "svg" ) ||
		     $qualifiedName == ($prefix . ":" . $localName) ) {
			return true;
		}
		return false;
	}
}
