<?php
/**
 * SVGMetadataExtractor.php
 *
 * @file
 * @ingroup Media
 */

class SVGMetadataExtractor {
	static function getMetadata( $filename ) {
		$filter = new XmlSizeFilter();
		$xml = new XmlTypeCheck( $filename, array( $filter, 'filter' ) );
		if( $xml->wellFormed ) {
			return array(
				'width' => $filter->width,
				'height' => $filter->height
			);
		}
	}
}

class XmlSizeFilter {
	const DEFAULT_WIDTH = 512;
	const DEFAULT_HEIGHT = 512;
	var $first = true;
	var $width = self::DEFAULT_WIDTH;
	var $height = self::DEFAULT_HEIGHT;
	function filter( $name, $attribs ) {
		if( $this->first ) {
			$defaultWidth = self::DEFAULT_WIDTH;
			$defaultHeight = self::DEFAULT_HEIGHT;
			$aspect = 1.0;
			$width = null;
			$height = null;
			
			if( isset( $attribs['viewBox'] ) ) {
				// min-x min-y width height
				$viewBox = preg_split( '/\s+/', trim( $attribs['viewBox'] ) );
				if( count( $viewBox ) == 4 ) {
					$viewWidth = $this->scaleSVGUnit( $viewBox[2] );
					$viewHeight = $this->scaleSVGUnit( $viewBox[3] );
					if( $viewWidth > 0 && $viewHeight > 0 ) {
						$aspect = $viewWidth / $viewHeight;
						$defaultHeight = $defaultWidth / $aspect;
					}
				}
			}
			if( isset( $attribs['width'] ) ) {
				$width = $this->scaleSVGUnit( $attribs['width'], $defaultWidth );
			}
			if( isset( $attribs['height'] ) ) {
				$height = $this->scaleSVGUnit( $attribs['height'], $defaultHeight );
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
				$this->width = intval( round( $width ) );
				$this->height = intval( round( $height ) );
			}
			
			$this->first = false;
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
	function scaleSVGUnit( $length, $viewportSize=512 ) {
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
}
