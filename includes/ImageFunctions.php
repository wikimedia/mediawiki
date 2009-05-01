<?php
/**
 * Return a rounded pixel equivalent for a labeled CSS/SVG length.
 * http://www.w3.org/TR/SVG11/coords.html#UnitIdentifiers
 *
 * @param $length String: CSS/SVG length.
 * @param $viewportSize: Float optional scale for percentage units...
 * @return float: length in pixels
 */
function wfScaleSVGUnit( $length, $viewportSize=512 ) {
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
					$viewWidth = wfScaleSVGUnit( $viewBox[2] );
					$viewHeight = wfScaleSVGUnit( $viewBox[3] );
					if( $viewWidth > 0 && $viewHeight > 0 ) {
						$aspect = $viewWidth / $viewHeight;
						$defaultHeight = $defaultWidth / $aspect;
					}
				}
			}
			if( isset( $attribs['width'] ) ) {
				$width = wfScaleSVGUnit( $attribs['width'], $defaultWidth );
			}
			if( isset( $attribs['height'] ) ) {
				$height = wfScaleSVGUnit( $attribs['height'], $defaultHeight );
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
}

/**
 * Compatible with PHP getimagesize()
 * @todo support gzipped SVGZ
 * @todo check XML more carefully
 * @todo sensible defaults
 *
 * @param $filename String: full name of the file (passed to php fopen()).
 * @return array
 */
function wfGetSVGsize( $filename ) {
	$filter = new XmlSizeFilter();
	$xml = new XmlTypeCheck( $filename, array( $filter, 'filter' ) );
	if( $xml->wellFormed ) {
		return array( $filter->width, $filter->height, 'SVG',
			"width=\"$filter->width\" height=\"$filter->height\"" );
	}
	
	return false;
}

/**
 * Determine if an image exists on the 'bad image list'.
 *
 * The format of MediaWiki:Bad_image_list is as follows:
 *    * Only list items (lines starting with "*") are considered
 *    * The first link on a line must be a link to a bad image
 *    * Any subsequent links on the same line are considered to be exceptions,
 *      i.e. articles where the image may occur inline.
 *
 * @param $name string the image name to check
 * @param $contextTitle Title: the page on which the image occurs, if known
 * @return bool
 */
function wfIsBadImage( $name, $contextTitle = false ) {
	static $badImages = false;
	wfProfileIn( __METHOD__ );

	# Handle redirects
	$redirectTitle = RepoGroup::singleton()->checkRedirect( Title::makeTitle( NS_FILE, $name ) );
	if( $redirectTitle ) {
		$name = $redirectTitle->getDbKey();
	}

	# Run the extension hook
	$bad = false;
	if( !wfRunHooks( 'BadImage', array( $name, &$bad ) ) ) {
		wfProfileOut( __METHOD__ );
		return $bad;
	}

	if( !$badImages ) {
		# Build the list now
		$badImages = array();
		$lines = explode( "\n", wfMsgForContentNoTrans( 'bad_image_list' ) );
		foreach( $lines as $line ) {
			# List items only
			if ( substr( $line, 0, 1 ) !== '*' ) {
				continue;
			}

			# Find all links
			$m = array();
			if ( !preg_match_all( '/\[\[:?(.*?)\]\]/', $line, $m ) ) {
				continue;
			}

			$exceptions = array();
			$imageDBkey = false;
			foreach ( $m[1] as $i => $titleText ) {
				$title = Title::newFromText( $titleText );
				if ( !is_null( $title ) ) {
					if ( $i == 0 ) {
						$imageDBkey = $title->getDBkey();
					} else {
						$exceptions[$title->getPrefixedDBkey()] = true;
					}
				}
			}

			if ( $imageDBkey !== false ) {
				$badImages[$imageDBkey] = $exceptions;
			}
		}
	}

	$contextKey = $contextTitle ? $contextTitle->getPrefixedDBkey() : false;
	$bad = isset( $badImages[$name] ) && !isset( $badImages[$name][$contextKey] );
	wfProfileOut( __METHOD__ );
	return $bad;
}

/**
 * Calculate the largest thumbnail width for a given original file size
 * such that the thumbnail's height is at most $maxHeight.
 * @param $boxWidth Integer Width of the thumbnail box.
 * @param $boxHeight Integer Height of the thumbnail box.
 * @param $maxHeight Integer Maximum height expected for the thumbnail.
 * @return Integer.
 */
function wfFitBoxWidth( $boxWidth, $boxHeight, $maxHeight ) {
	$idealWidth = $boxWidth * $maxHeight / $boxHeight;
	$roundedUp = ceil( $idealWidth );
	if( round( $roundedUp * $boxHeight / $boxWidth ) > $maxHeight )
		return floor( $idealWidth );
	else
		return $roundedUp;
}
