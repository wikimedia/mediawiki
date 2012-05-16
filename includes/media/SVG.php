<?php
/**
 * Handler for SVG images.
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
 */

/**
 * Handler for SVG images.
 *
 * @ingroup Media
 */
class SvgHandler extends ImageHandler {
	const SVG_METADATA_VERSION = 2;

	function isEnabled() {
		global $wgSVGConverters, $wgSVGConverter;
		if ( !isset( $wgSVGConverters[$wgSVGConverter] ) ) {
			wfDebug( "\$wgSVGConverter is invalid, disabling SVG rendering.\n" );
			return false;
		} else {
			return true;
		}
	}

	function mustRender( $file ) {
		return true;
	}

	function isVectorized( $file ) {
		return true;
	}

	/**
	 * @param $file File
	 * @return bool
	 */
	function isAnimatedImage( $file ) {
		# TODO: detect animated SVGs
		$metadata = $file->getMetadata();
		if ( $metadata ) {
			$metadata = $this->unpackMetadata( $metadata );
			if( isset( $metadata['animated'] ) ) {
				return $metadata['animated'];
			}
		}
		return false;
	}

	/**
	 * @param $image File
	 * @param  $params
	 * @return bool
	 */
	function normaliseParams( $image, &$params ) {
		global $wgSVGMaxSize;
		if ( !parent::normaliseParams( $image, $params ) ) {
			return false;
		}
		# Don't make an image bigger than wgMaxSVGSize on the smaller side
		if ( $params['physicalWidth'] <= $params['physicalHeight'] ) {
			if ( $params['physicalWidth'] > $wgSVGMaxSize ) {
				$srcWidth = $image->getWidth( $params['page'] );
				$srcHeight = $image->getHeight( $params['page'] );
				$params['physicalWidth'] = $wgSVGMaxSize;
				$params['physicalHeight'] = File::scaleHeight( $srcWidth, $srcHeight, $wgSVGMaxSize );
			}
		} else {
			if ( $params['physicalHeight'] > $wgSVGMaxSize ) {
				$srcWidth = $image->getWidth( $params['page'] );
				$srcHeight = $image->getHeight( $params['page'] );
				$params['physicalWidth'] = File::scaleHeight( $srcHeight, $srcWidth, $wgSVGMaxSize );
				$params['physicalHeight'] = $wgSVGMaxSize;
			}
		}
		return true;
	}

	/**
	 * @param $image File
	 * @param  $dstPath
	 * @param  $dstUrl
	 * @param  $params
	 * @param int $flags
	 * @return bool|MediaTransformError|ThumbnailImage|TransformParameterError
	 */
	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		$clientWidth = $params['width'];
		$clientHeight = $params['height'];
		$physicalWidth = $params['physicalWidth'];
		$physicalHeight = $params['physicalHeight'];
		$srcPath = $image->getLocalRefPath();

		if ( $flags & self::TRANSFORM_LATER ) {
			return new ThumbnailImage( $image, $dstUrl, $clientWidth, $clientHeight, $dstPath );
		}

		if ( !wfMkdirParents( dirname( $dstPath ), null, __METHOD__ ) ) {
			return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight,
				wfMsg( 'thumbnail_dest_directory' ) );
		}

		$status = $this->rasterize( $srcPath, $dstPath, $physicalWidth, $physicalHeight );
		if( $status === true ) {
			return new ThumbnailImage( $image, $dstUrl, $clientWidth, $clientHeight, $dstPath );
		} else {
			return $status; // MediaTransformError
		}
	}

	/**
	* Transform an SVG file to PNG
	* This function can be called outside of thumbnail contexts
	* @param string $srcPath
	* @param string $dstPath
	* @param string $width
	* @param string $height
	* @return bool|MediaTransformError
	*/
	public function rasterize( $srcPath, $dstPath, $width, $height ) {
		global $wgSVGConverters, $wgSVGConverter, $wgSVGConverterPath;
		$err = false;
		$retval = '';
		if ( isset( $wgSVGConverters[$wgSVGConverter] ) ) {
			if ( is_array( $wgSVGConverters[$wgSVGConverter] ) ) {
				// This is a PHP callable
				$func = $wgSVGConverters[$wgSVGConverter][0];
				$args = array_merge( array( $srcPath, $dstPath, $width, $height ),
					array_slice( $wgSVGConverters[$wgSVGConverter], 1 ) );
				if ( !is_callable( $func ) ) {
					throw new MWException( "$func is not callable" );
				}
				$err = call_user_func_array( $func, $args );
				$retval = (bool)$err;
			} else {
				// External command
				$cmd = str_replace(
					array( '$path/', '$width', '$height', '$input', '$output' ),
					array( $wgSVGConverterPath ? wfEscapeShellArg( "$wgSVGConverterPath/" ) : "",
						   intval( $width ),
						   intval( $height ),
						   wfEscapeShellArg( $srcPath ),
						   wfEscapeShellArg( $dstPath ) ),
					$wgSVGConverters[$wgSVGConverter]
				) . " 2>&1";
				wfProfileIn( 'rsvg' );
				wfDebug( __METHOD__.": $cmd\n" );
				$err = wfShellExec( $cmd, $retval );
				wfProfileOut( 'rsvg' );
			}
		}
		$removed = $this->removeBadFile( $dstPath, $retval );
		if ( $retval != 0 || $removed ) {
			wfDebugLog( 'thumbnail', sprintf( 'thumbnail failed on %s: error %d "%s" from "%s"',
					wfHostname(), $retval, trim($err), $cmd ) );
			return new MediaTransformError( 'thumbnail_error', $width, $height, $err );
		}
		return true;
	}

	public static function rasterizeImagickExt( $srcPath, $dstPath, $width, $height ) {
		$im = new Imagick( $srcPath );
		$im->setImageFormat( 'png' );
		$im->setBackgroundColor( 'transparent' );
		$im->setImageDepth( 8 );

		if ( !$im->thumbnailImage( intval( $width ), intval( $height ), /* fit */ false ) ) {
			return 'Could not resize image';
		}
		if ( !$im->writeImage( $dstPath ) ) {
			return "Could not write to $dstPath";
		}
	}

	/**
	 * @param $file File
	 * @param  $path
	 * @param bool $metadata
	 * @return array
	 */
	function getImageSize( $file, $path, $metadata = false ) {
		if ( $metadata === false ) {
			$metadata = $file->getMetaData();
		}
		$metadata = $this->unpackMetaData( $metadata );

		if ( isset( $metadata['width'] ) && isset( $metadata['height'] ) ) {
			return array( $metadata['width'], $metadata['height'], 'SVG',
					"width=\"{$metadata['width']}\" height=\"{$metadata['height']}\"" );
		}
	}

	function getThumbType( $ext, $mime, $params = null ) {
		return array( 'png', 'image/png' );
	}

	/**
	 * @param $file File
	 * @return string
	 */
	function getLongDesc( $file ) {
		global $wgLang;
		return wfMsgExt( 'svg-long-desc', 'parseinline',
			$wgLang->formatNum( $file->getWidth() ),
			$wgLang->formatNum( $file->getHeight() ),
			$wgLang->formatSize( $file->getSize() ) );
	}

	function getMetadata( $file, $filename ) {
		try {
			$metadata = SVGMetadataExtractor::getMetadata( $filename );
		} catch( Exception $e ) {
 			// Broken file?
			wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );
			return '0';
		}
		$metadata['version'] = self::SVG_METADATA_VERSION;
		return serialize( $metadata );
	}

	function unpackMetadata( $metadata ) {
		wfSuppressWarnings();
		$unser = unserialize( $metadata );
		wfRestoreWarnings();
		if ( isset( $unser['version'] ) && $unser['version'] == self::SVG_METADATA_VERSION ) {
			return $unser;
		} else {
			return false;
		}
	}

	function getMetadataType( $image ) {
		return 'parsed-svg';
	}

	function isMetadataValid( $image, $metadata ) {
		return $this->unpackMetadata( $metadata ) !== false;
	}

	function visibleMetadataFields() {
		$fields = array( 'title', 'description', 'animated' );
		return $fields;
	}

	/**
	 * @param $file File
	 * @return array|bool
	 */
	function formatMetadata( $file ) {
		$result = array(
			'visible' => array(),
			'collapsed' => array()
		);
		$metadata = $file->getMetadata();
		if ( !$metadata ) {
			return false;
		}
		$metadata = $this->unpackMetadata( $metadata );
		if ( !$metadata ) {
			return false;
		}
		unset( $metadata['version'] );
		unset( $metadata['metadata'] ); /* non-formatted XML */

		/* TODO: add a formatter
		$format = new FormatSVG( $metadata );
		$formatted = $format->getFormattedData();
		*/

		// Sort fields into visible and collapsed
		$visibleFields = $this->visibleMetadataFields();

		// Rename fields to be compatible with exif, so that
		// the labels for these fields work.
		$conversion = array( 'width' => 'imagewidth',
			'height' => 'imagelength',
			'description' => 'imagedescription',
			'title' => 'objectname',
		);
		foreach ( $metadata as $name => $value ) {
			$tag = strtolower( $name );
			if ( isset( $conversion[$tag] ) ) {
				$tag = $conversion[$tag];
			}
			self::addMeta( $result,
				in_array( $tag, $visibleFields ) ? 'visible' : 'collapsed',
				'exif',
				$tag,
				$value
			);
		}
		return $result;
	}


	function verifyUpload( $fileName ) {
		global $wgSVGPurifyMaxFileSize;

		$status = Status::newGood();

		$svg = file_get_contents( $fileName );
		$svgp = new SVGPurifier();
		$cfg = $svgp->getConfig(); // $cfg could be cached
		$svg_out = $svgp->purify($svg, $cfg);
		file_put_contents($fileName, $svg_out);

		return $status;
	}
}




/**
 * Class for doing the actual Purification of SVG files.
 *
 * All of the hard work was done by Mario Heiderich
 *
 * @ingroup Media
 */

class SVGPurifier {

	//Groups of white-listed elements
	protected $animation_elements, $descriptive_elements, $shape_elements,
		  $structural_elements, $gradient_elements, $common_elements,
		  $font_face_elements, $glyph_elements, $filter_elements,
		  $filter_function_elements, $filter_lightsource_elements, $text_elements;

	//Groups of white-listed attributes
	protected $root_node_attributes, $conditional_processing_attributes, $core_attributes,
		  $xlink_attributes, $presentation_attributes, $general_attributes,
		  $dimensioning_attributes, $circle_attributes, $path_attributes, $line_attributes,
		  $polyline_polygon_attributes, $rect_attributes, $ellipse_attributes,
		  $animation_attribute_target_attributes, $animation_timing_attributes,
		  $animation_value_attributes, $animation_addition_attributes,
		  $animate_motion_attributes, $linear_gradient_attributes,
		  $radial_gradient_attributes, $filter_primitive_attributes, $filter_attributes,
		  $filter_lightsource_attributes, $glyph_attributes, $clip_path_attributes,
		  $color_profile_attributes, $cursor_attributes, $font_attributes,
		  $font_face_format_attributes, $font_face_name_attributes, $marker_attributes,
		  $mask_attributes, $pattern_attributes, $style_attributes, $text_attributes,
		  $view_attributes, $textpath_attributes, $kerning_attributes, $use_tag_attributes;

	public function __construct() {
		$this->defineElements();
		$this->defineAttributes();
	}

	/**
	 * Setup the HTMLPurifier config, mapping allowed elements, attributes, and
	 * attribute value rules.
	 *
	 * @return HTMLPurifier config
	 */
	public function getConfig() {
		//Setup Config
		$config = HTMLPurifier_Config::createDefault();
		$config->set( 'HTML.AllowedElements', array_merge(
			$this->animation_elements, $this->descriptive_elements, $this->shape_elements,
			$this->structural_elements, $this->gradient_elements, $this->common_elements,
			$this->font_face_elements, $this->glyph_elements, $this->filter_elements,
			$this->filter_function_elements, $this->filter_lightsource_elements, 
			$this->text_elements
		));
		$config->set( 'HTML.Doctype', null );
		$config->set( 'URI.DisableExternalResources', true );
		$config->set( 'Core.RemoveProcessingInstructions', true );

		//Setup Definitions: Mapping elements to attributes
		$def = $config->getHTMLDefinition( true );
		$this->addDefinitionsDescriptiveElements( $def );
		$this->addDefinitionsCommonElements( $def );
		$this->addDefinitionsTextElements( $def );
		$this->addDefinitionsGlyphElements( $def );
		$this->addDefinitionsShapeElements( $def );
		$this->addDefinitionsAnimationElements( $def );
		$this->addDefinitionsStructuralElements( $def );
		$this->addDefinitionsGradientElements( $def );
		$this->addDefinitionsFilterElements( $def );
		$this->addDefinitionsFontFaceElements( $def );

		return ( $config );
	}

	/**
	 * Purify the svg xml
	 * @param $svg xml string defining the svg
	 * @param $config HTMLPurifier config, returned by getConfig()
	 * @return SVG safe for use on the site
	 */
	public static function purify( $svg, $config ) {
		$svg = self::preProcess( trim( $svg ) );
		$purify = new HTMLPurifier( $config );
		$svg = $purify->purify( $svg );
		$svg = self::postProcess( trim( $svg ) );
		return $svg;
	}


	public static function preProcess( $svg ) {
		// namespace corrections from XXE
		$svg = preg_replace( '/xmlns:svgx/sim', 'xmlns', $svg );
		return $svg;
	}

	public static function postProcess( $svg ) {
		$mixed_case_elements = array(
			'altGlyph', 'altGlyphDef', 'altGlyphItem', 'glyphRef',
			'animateColor', 'animateMotion', 'animateTransform',
			'clipPath', 'feBlend', 'feColorMatrix', 'feComponentTransfer',
			'feComposite', 'feConvolveMatrix', 'feDiffuseLighting', 'feDisplacementMap',
			'feDistantLight', 'feFlood', 'feFuncA', 'feFuncB', 'feFuncG', 'feFuncR',
			'feGaussianBlur', 'feImage', 'feMerge', 'feMergeNode', 'feMorphology',
			'feOffset', 'fePointLight', 'feSpecularLighting', 'feSpotLight', 'feTile',
			'feTurbulence', 'linearGradient', 'radialGradient', 'textPath'
		);
		foreach( $mixed_case_elements as $element ) {
			$svg = preg_replace( '/(<\/?)'.$element.'( ?)/im', '$1'.$element.'$2', $svg );
		}
		$mixed_case_attributes = array(
			'attributeName', 'attributeType', 'baseFrequency', 'baseProfile',
			'clipPathUnits', 'contentScriptType', 'contentStyleType', 'diffuseConstant',
			'edgeMode', 'externalResourcesRequired', 'filterRes', 'filterUnits', 'glyphRef',
			'gradientTransform', 'gradientUnits', 'kernelMatrix', 'kernelUnitLength',
			'keyPoints', 'keySplines', 'keyTimes', 'lengthAdjust', 'limitingConeAngle',
			'markerHeight', 'markerUnits', 'markerWidth', 'maskContentUnits', 'maskUnits',
			'numOctaves', 'patternContentUnits', 'patternTransform', 'patternUnits',
			'preserveAlpha', 'preserveAspectRatio', 'primitiveUnits', 'refX', 'refY',
			'repeatCount', 'repeatDur', 'requiredExtensions', 'requiredFeatures',
			'specularConstant', 'specularExponent', 'spreadMethod', 'startOffset',
			'stdDeviation', 'stitchTiles', 'surfaceScale', 'systemLanguage', 'tableValues',
			'targetX', 'targetY', 'textLength', 'viewBox', 'viewTarget', 'xChannelSelector',
			'yChannelSelector', 'zoomAndPan'
		);
		foreach( $mixed_case_attributes as $attribute ) {
			$svg = preg_replace( '/ '.$attribute.'="/im', ' '.$attribute.'="', $svg );
		}
		$mixed_case_attribute_values = array(
			'userSpaceOnUse', 'objectBoundingBox'
		);
		foreach( $mixed_case_attribute_values as $attribute ) {
			$svg = preg_replace( '/="'.$attribute.'"/im', '="'.$attribute.'"', $svg );
		}

		// some insecure and easy to break regex based filters
		$svg = preg_replace( '/(?:import|behavior|expression|binding|-o-link|\\\)/sim', 'INVALID', $svg );
		$svg = preg_replace( '/^[^<]*?<svg /', '<svg ', $svg );
		$svg = preg_replace( '/javascript:[^"]+/', 'INVALID', $svg );

		// namespace corrections from XXE
		$svg = preg_replace( '/xmlns="&[^"]+"/sim', 'xmlns="http://www.w3.org/2000/svg"', $svg );
		$svg = preg_replace( '/xmlns:xlink="&[^"]+"/sim', 'xmlns:xlink="http://www.w3.org/1999/xlink"', $svg );

		return $svg;
	}


	/**
	 * Define Descriptive Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsDescriptiveElements( &$def ) {
		// add definition elements
		$def->addElement(
			'desc',
			'Block',
			'Flow',
			'Common',
			array_merge( $this->core_attributes, $this->general_attributes )
		);
		$def->addElement(
			'metadata',
			'Block',
			'Flow',
			'Common',
			array_merge( $this->core_attributes, $this->general_attributes )
		);
		$def->addElement(
			'title',
			'Block',
			'Flow',
			'Common',
			array_merge( $this->core_attributes, $this->general_attributes )
		);
	}

	/**
	 * Define Common Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsCommonElements( &$def ){
		// add common elements
		$def->addElement(
			'a',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements,
				$this->structural_elements,
				$this->gradient_elements,
				$this->common_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->general_attributes,
				$this->core_attributes,
				$this->xlink_attributes
			)
		);
		$def->addElement(
		  	'clippath',
			'Block',
			'Optional: ' . join( array_merge(
				$this->descriptive_elements,
				$this->animation_elements,
				$this->shape_elements,
				$this->structural_elements,
				array('text', 'use')
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->clip_path_attributes
			)
		);
		$def->addElement(
		  	'color-profile',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->color_profile_attributes
			)
		);
		$def->addElement(
		  	'cursor',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->cursor_attributes
			)
		);
		$def->addElement(
		  	'image',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->xlink_attributes,
				$this->dimensioning_attributes
			)
		);
		$def->addElement(
		  	'marker',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements,
				$this->structural_elements,
				$this->gradient_elements,
				$this->common_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->marker_attributes
			)
		);
		$def->addElement(
		  	'mask',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements,
				$this->structural_elements,
				$this->gradient_elements,
				$this->common_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->mask_attributes,
				$this->dimensioning_attributes
			)
		);
		$def->addElement(
		  	'pattern',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements,
				$this->structural_elements,
				$this->gradient_elements,
				$this->common_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->pattern_attributes,
				$this->xlink_attributes,
				$this->dimensioning_attributes
			)
		);
		$def->addElement(
		  	'style',
			'Stylesheet',
			'Flow',
			'Common',
			array_merge(
				$this->core_attributes,
				$this->style_attributes
			)
		);
		$def->addElement(
		  	'switch',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements,
				$this->common_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes
			)
		);
		$def->addElement(
		  	'view',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->view_attributes
			)
		);
	}

	/**
	 * Define Text Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsTextElements( &$def ){
		// add text elements
		$def->addElement(
		  	'text',
			'Block',
			'Flow',
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->dimensioning_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->text_attributes
			)
		);
		$def->addElement(
		  	'tref',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->dimensioning_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->xlink_attributes
			)
		);
		$def->addElement(
		  	'tspan',
			'Block',
			'Flow',
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->dimensioning_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->text_attributes
			)
		);
		$def->addElement(
		  	'hkern',
			'Block',
			'Empty',
			'Common',
			array_merge(
				$this->core_attributes,
				$this->kerning_attributes
			)
		);
		$def->addElement(
		  	'vkern',
			'Block',
			'Empty',
			'Common',
			array_merge(
				$this->core_attributes,
				$this->kerning_attributes
			)
		);
	}

	/**
	 * Define Glyph Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsGlyphElements( &$def ){
		// add glyph elements
		$def->addElement(
		  	'glyph',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->glyph_elements,
				$this->shape_elements,
				$this->common_elements,
				$this->structural_elements,
				$this->gradient_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->glyph_attributes
			)
		);
		$def->addElement(
		  	'altglyph',
			'Block',
			'Flow',
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->glyph_attributes
			)
		);
		$def->addElement(
		  	'altglyphdef',
			'Block',
			'Required: glyphref | altglyphitem',
			'Common',
			array_merge(
				$this->core_attributes
			)
		);
		$def->addElement(
		  	'altglyphitem',
			'Block',
			'Required: glyphref',
			'Common',
			array_merge(
				$this->core_attributes
			)
		);
		$def->addElement(
		  	'glyphref',
			'Block',
			'Empty',
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->xlink_attributes,
				$this->glyph_attributes
			)
		);
		$def->addElement(
		  	'missing-glyph',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->glyph_elements,
				$this->shape_elements,
				$this->common_elements,
				$this->structural_elements,
				$this->gradient_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->xlink_attributes,
				$this->glyph_attributes
			)
		);

	}

	/**
	 * Define Shape Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsShapeElements( &$def ){
		// add shape elements
		$def->addElement(
		  	'circle',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->circle_attributes
			)
		);
		$def->addElement(
		  	'ellipse',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->ellipse_attributes
			)
		);
		$def->addElement(
		  	'line',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->line_attributes
			)
		);
		$def->addElement(
		  	'mpath',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes
			)
		);
		$def->addElement(
		  	'textpath',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->glyph_elements,
				$this->text_elements,
				array('a')
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->xlink_attributes,
				$this->textpath_attributes
			)
		);
		$def->addElement(
		  	'path',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->path_attributes
			)
		);
		$def->addElement(
		  	'polygon',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->polyline_polygon_attributes
			)
		);
		$def->addElement(
		  	'polyline',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				$this->polyline_polygon_attributes
			)
		);
		$def->addElement(
		  	'rect',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->rect_attributes,
				$this->dimensioning_attributes,
				$this->presentation_attributes
			)
		);
	}

	/**
	 * Define Animation Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsAnimationElements( &$def ){
		// add shape elements
		$def->addElement(
		  	'animatecolor',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->animation_attribute_target_attributes,
				$this->animation_timing_attributes,
				$this->animation_value_attributes,
				$this->animation_addition_attributes
			)
		);
		$def->addElement(
		  	'animatemotion',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->animation_attribute_target_attributes,
				$this->animation_timing_attributes,
				$this->animation_value_attributes,
				$this->animation_addition_attributes,
				$this->animate_motion_attributes
			)
		);
		$def->addElement(
		  	'animatetransform',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->animation_attribute_target_attributes,
				$this->animation_timing_attributes,
				$this->animation_value_attributes,
				$this->animation_addition_attributes,
				array( 'type' => 'Enum#translate|scale|rotate|skewX|skewY' )
			)
		);
		$def->addElement(
		  	'animate',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->animation_attribute_target_attributes,
				$this->animation_timing_attributes,
				$this->animation_value_attributes,
				$this->animation_addition_attributes
			)
		);
		$def->addElement(
		  	'set',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->animation_attribute_target_attributes,
				$this->animation_timing_attributes,
				array( 'to' => 'CDATA' )
			)
		);
		$def->addElement(
		  	'stop',
			'Block',
			'Optional: ' . join( $this->descriptive_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->presentation_attributes,
				array( 'offset' => 'CDATA' )
			)
		);

	}

	/**
	 * Define Structure Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsStructuralElements( &$def ){

		// add structural elements
		$def->addElement(
		  	'defs',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements,
				$this->structural_elements,
				$this->gradient_elements,
				$this->common_elements,
				$this->filter_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->presentation_attributes,
				$this->general_attributes,
				$this->circle_attributes
			)
		);

		$def->addElement(
		  	'g',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements,
				$this->structural_elements,
				$this->gradient_elements,
				$this->common_elements,
				$this->filter_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->presentation_attributes,
				$this->general_attributes,
				$this->circle_attributes
			)
		);

		$def->addElement(
		  	'svg',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements,
				$this->structural_elements,
				$this->gradient_elements,
				$this->common_elements,
				$this->filter_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->presentation_attributes,
				$this->general_attributes,
				$this->circle_attributes,
				$this->root_node_attributes
			)
		);

		$def->addElement(
		  	'symbol',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements,
				$this->shape_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->presentation_attributes,
				$this->general_attributes,
				$this->circle_attributes
			)
		);

		$def->addElement(
		  	'use',
			'Block',
			'Optional: ' . join( array_merge(
				$this->animation_elements,
				$this->descriptive_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->conditional_processing_attributes,
				$this->core_attributes,
				$this->presentation_attributes,
				$this->general_attributes,
				$this->circle_attributes,
				$this->use_tag_attributes
			)
		);
	}

	/**
	 * Define Gradient Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsGradientElements( &$def ){
		// add gradient elements
		$def->addElement(
		  	'lineargradient',
			'Block',
			'Optional: ' . join( array_merge(
				$this->descriptive_elements, $this->animation_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->linear_gradient_attributes
			)
		);
		$def->addElement(
		  	'radialgradient',
			'Block',
			'Optional: ' . join( array_merge(
				$this->descriptive_elements,
				$this->animation_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->general_attributes,
				$this->xlink_attributes,
				$this->radial_gradient_attributes
			)
		);
	}

	/**
	 * Define Filter Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsFilterElements( &$def ){

		// add filter elements
		$def->addElement(
		  	'filter',
			'Block',
			'Optional: ' . join( array_merge(
				$this->descriptive_elements,
				$this->filter_elements,
				$this->animation_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->xlink_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'feblend',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fecolormatrix',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fecomponenttransfer',
			'Block',
			'Optional: ' . join( array_merge( $this->filter_function_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fecomposite',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'feconvolvematrix',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fediffuselighting',
			'Block',
			'Optional: ' . join( array_merge(
				$this->descriptive_elements, $this->filter_lightsource_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fedisplacementmap',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'feflood',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fegaussianblur',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'feimage',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes,
				$this->xlink_attributes
			)
		);
		$def->addElement(
		  	'fefunca',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fefuncb',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fefuncg',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fefuncr',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'femerge',
			'Block',
			'Optional: feMergeNode',
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'femergenode',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'femorphology',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'feoffset',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fetile',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'feturbulence',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->filter_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes
			)
		);
		$def->addElement(
		  	'fedistantlight',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->filter_primitive_attributes,
				$this->filter_lightsource_attributes
			)
		);
		$def->addElement(
		  	'fepointlight',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->filter_primitive_attributes,
				$this->filter_lightsource_attributes
			)
		);
		$def->addElement(
		  	'fespotlight',
			'Block',
			'Optional: ' . join( array_merge( $this->animation_elements ), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->filter_primitive_attributes,
				$this->filter_lightsource_attributes
			)
		);
		$def->addElement(
		  	'fespecularlighting',
			'Block',
			'Optional: ' . join( array_merge(
				$this->descriptive_elements, $this->filter_lightsource_elements
			), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->presentation_attributes,
				$this->general_attributes,
				$this->filter_primitive_attributes,
				$this->filter_lightsource_attributes
			)
		);
	}

	/**
	 * Define Font-face Elements
	 * @param &$def HTMLPurifier configuration definition
	 */
	function addDefinitionsFontFaceElements( &$def ){
		// add font-face elements
		$def->addElement(
		  	'font-face',
			'Block',
			'Optional: ' . join( array_merge( $this->descriptive_elements, array('font-face-src')), ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->font_attributes
			)
		);
		$def->addElement(
		  	'font-face-src',
			'Block',
			'Optional: ' . join( $this->font_face_elements, ' | ' ),
			'Common',
			array_merge(
				$this->core_attributes,
				$this->font_attributes
			)
		);
		$def->addElement(
		  	'font-face-format',
			'Block',
			'Empty',
			'Common',
			array_merge(
				$this->core_attributes,
				$this->font_face_format_attributes
			)
		);
		$def->addElement(
		  	'font-face-name',
			'Block',
			'Empty',
			'Common',
			array_merge(
				$this->core_attributes,
				$this->font_face_name_attributes
			)
		);
		$def->addElement(
		  	'font-face-uri',
			'Block',
			'Optional: font-face-format',
			'Common',
			array_merge(
				$this->core_attributes,
				$this->xlink_attributes,
				$this->font_face_name_attributes
			)
		);
	}

	/**
	 * Define allowed elements, by logical groups. Allowed attibutes
	 * for each are mapped in the addDefinitionsXX functions above.
	 */
	private function defineElements() {
		// NODELIST
		// animation elements
		$this->animation_elements = array(
			'animate',
			'animatecolor',
			'animatemotion',
			'animatetransform',
			'set',
			'stop'
		);
		// descriptive elements
		$this->descriptive_elements = array(
			'desc',
			'metadata',
			'title'
		);
		// shape elements
		$this->shape_elements = array(
			'circle',
			'ellipse',
			'line',
			'mpath',
			'textpath',
			'path',
			'polygon',
			'polyline',
			'rect'
		);
		// structural elements
		$this->structural_elements = array(
			'defs',
			'g',
			'svg',
			'symbol',
			'use'
		);
		// gradient elements
		$this->gradient_elements = array(
			'lineargradient',
			'radialgradient'
		);
		// common elements
		$this->common_elements = array(
			'a',
			'clippath',
			'color-profile',
			'cursor',
			'filter',
			'font',
			'font-face',
			#'foreignobject', DANGEROUS
			'image',
			'marker',
			'mask',
			'pattern',
			#'script', DANGEROUS
			'style',
			'switch',
			'text',
			'view'
		);
		$this->font_face_elements = array(
			'font-face-format',
			'font-face-name',
			'font-face-src',
			'font-face-uri'
		);
		// glyph elements
		$this->glyph_elements = array(
			'glyph',
			'altglyph',
			'altglyphdef',
			'altglyphitem',
			'glyphref',
			'missing-glyph'
		);
		// filter elements
		$this->filter_elements = array(
			'feblend',
			'fecolormatrix',
			'fecomponenttransfer',
			'fecomposite',
			'feconvolvematrix',
			'fedisplacementmap',
			'feflood',
			'fefunca',
			'fefuncb',
			'fefuncg',
			'fefuncr',
			'fegaussianblur',
			'feimage',
			'femerge',
			'femergenode',
			'femorphology',
			'feoffset',
			'fetile',
			'feturbulence'
		);
		// filter function elements
		$this->filter_function_elements = array(
			'fefunca',
			'fefuncb',
			'fefuncg',
			'fefuncr',
		);
		// filter lightsource elements
		$this->filter_lightsource_elements = array(
			'fediffuselighting',
			'fedistantlight',
			'fepointlight',
			'fespecularlighting',
			'fespotlight'
		);
		// text elements
		$this->text_elements = array(
			'tref',
			'tspan',
			'hkern',
			'vkern'
		);

	}

	/**
	 * Define allowed attibutes, by logical groups.
	 */
	private function defineAttributes() {

		// ATTLIST

		/* KEEP ATTRIBUTE NAMES AND ENUM DATA IN LOWERCASE */

		// root node attributes
		$this->root_node_attributes = array(
			'xmlns' => 'URI',
			'xmlns:xlink' => 'URI',
			'xmlns:svg' => 'URI',
			'height' => 'CDATA',
			'width' => 'CDATA',
			'viewbox' => 'CDATA'
		);

		// conditional processing attributes
		$this->conditional_processing_attributes = array(
			'requiredfeatures' => 'CDATA',
			'requiredextensions' => 'CDATA',
			'systemlanguage' => 'CDATA'
		);
		// core attributes
		$this->core_attributes = array(
			'id' => 'CDATA',
			'xml:base' => 'CDATA',
			'xml:lang' => 'LanguageCode',
			'xml:space' => 'Enum#default,preserve',
			'style' => 'CDATA'
		);

		// xlink attributes
		$this->xlink_attributes = array(
			'xmlns:xlink' => 'URI',
			'xlink:href' => 'URI',
			'xlink:show' => 'Enum#new,replace,embed,other,none',
			'xlink:actuate' => 'Enum#onload',
			'xlink:type' => 'Enum#simple',
			'xlink:role' => 'CDATA',
			'xlink:arcrole' => 'CDATA',
			'xlink:title' => 'CDATA'
		);

		// presentation attributes
		$this->presentation_attributes = array(
			'alignment-baseline' => 'Enum#auto,baseline,before-edge,text-before-edge,middle,central,after-edge,text-after-edge,ideographic,alphabetic,hanging,mathematical,inherit',
			'baseline-shift' => 'CDATA',
			'clip' => 'CDATA',
			'clip-path' => 'CDATA',
			'clip-rule' => 'Enum#nonzero,evenodd,inherit',
			'color' => 'CDATA',
			'color-interpolation' => 'Enum#auto,srgb,linearrgb,inherit',
			'color-interpolation-filters' => 'Enum#auto,srgb,linearrgb,inherit',
			'color-profile' => 'CDATA',
			'color-rendering' => 'Enum#auto,optimizeSpeed,optimizequality,inherit',
			'cursor' => 'CDATA',
			'cx' => 'CDATA',
			'cy' => 'CDATA',
			'direction' => 'Enum#ltr,rtl,inherit',
			'display' => 'Enum#inline,block,list-item,run-in,compact,marker,table,inline-table,table-row-group,table-header-group,table-footer-group,table-row,table-column-group,table-column,table-cell,table-caption,none,inherit',
			'dominant-baseline' => 'Enum#auto,use-script,no-change,reset-size,ideographic,alphabetic,hanging,mathematical,central,middle,text-after-edge,text-before-edge,inherit',
			'enable-background' => 'CDATA',
			'fill' => 'CDATA',
			'fill-opacity' => 'CDATA',
			'fill-rule' => 'Enum#nonzero,evenodd,inherit',
			'filter' => 'CDATA',
			'flood-color' => 'Color',
			'flood-opacity' => 'CDATA',
			'font-family' => 'CDATA',
			'font-size' => 'CDATA',
			'font-size-adjust' => 'CDATA',
			'font-stretch' => 'Enum#normal,wider,narrower,ultra-condensed,extra-condensed,condensed,semi-condensed,semi-expanded,expanded,extra-expanded,ultra-expanded,inherit',
			'font-style' => 'Enum#normal,italic,oblique,inherit',
			'font-variant' => 'Enum#normal,small-caps,inherit',
			'font-weight' => 'Enum#normal,bold,bolder,lighter,100,200,300,400,500,600,700,800,900,inherit',
			'glyph-orientation-horizontal' => 'CDATA',
			'glyph-orientation-vertical' => 'CDATA',
			'image-rendering' => 'Enum#auto,optimizespeed,optimizequality,inherit',
			'kerning' => 'CDATA',
			'letter-spacing' => 'CDATA',
			'lighting-color' => 'Color',
			'marker-end' => 'CDATA',
			'marker-mid' => 'CDATA',
			'marker-start' => 'CDATA',
			'mask' => 'CDATA',
			'opacity' => 'CDATA',
			'overflow' => 'Enum#visible,hidden,scroll,auto,inherit',
			'pointer-events' => 'Enum#visiblepainted,visiblefill,visiblestroke,visible,painted,fill,stroke,all,none,inherit',
			'points' => 'CDATA',
			'rx' => 'CDATA',
			'ry' => 'CDATA',
			'shape-rendering' => 'Enum#auto,optimizespeed,crispedges,geometricprecision,inherit',
			'stop-color' => 'CDATA',
			'stop-opacity' => 'CDATA',
			'stroke' => 'CDATA',
			'stroke-dasharray' => 'CDATA',
			'stroke-dashoffset' => 'CDATA',
			'stroke-linecap' => 'Enum#butt,round,square,inherit',
			'stroke-linejoin' => 'Enum#miter,round,bevel,inherit',
			'stroke-miterlimit' => 'Number',
			'stroke-opacity' => 'Number',
			'stroke-width' => 'CDATA',
			'text-anchor' => 'Enum#start,middle,end,inherit',
			'text-decoration' => 'CDATA',
			'text-rendering' => 'Enum#auto,optimizespeed,optimizelegibility,geometricprecision,inherit',
			'transform' => 'CDATA',
			'unicode-bidi' => 'Enum#normal,embed,bidi-override,inherit',
			'visibility' => 'Enum#visible,hidden,collapse,inherit',
			'word-spacing' => 'CDATA',
			'writing-mode' => 'Enum#lr-tb,rl-tb,tb-rl,lr,rl,tb,inherit'
		);
		// general attributes
		$this->general_attributes = array(
			'class' => 'Class',
			'externalresourcesrequired' => 'Enum#true,false',
			'transform' => 'CDATA'
		);
		// dimensioning attributes
		$this->dimensioning_attributes = array(
			'x' => 'CDATA',
			'y' => 'CDATA',
			'height' => 'CDATA',
			'width' => 'CDATA'
		);
		// circle attributes
		$this->circle_attributes = array(
			'r' => 'CDATA',
			'cx' => 'CDATA',
			'cy' => 'CDATA',
			'fill' => 'CDATA'
		);
		// path attributes
		$this->path_attributes = array(
			'd' => 'CDATA',
			'pathlength' => 'CDATA'
		);
		// line attributes
		$this->line_attributes = array(
			'x1' => 'CDATA',
			'y1' => 'CDATA',
			'x2' => 'CDATA',
			'y2' => 'CDATA'
		);
		// polyline and polygon attributes
		$this->polyline_polygon_attributes = array(
			'points' => 'CDATA'
		);
		// rect attributes
		$this->rect_attributes = array(
			'rx' => 'CDATA',
			'ry' => 'CDATA'
		);
		// ellipse attributes
		$this->ellipse_attributes = array(
			'r' => 'CDATA',
			'cx' => 'CDATA',
			'cy' => 'CDATA',
			'rx' => 'CDATA',
			'ry' => 'CDATA'
		);
		// animation attribute target attributes
		$this->animation_attribute_target_attributes = array(
			'attributetype' => 'Enum#css,xml,auto',
			'attributename' => 'Enum#'.join( array_keys( $this->presentation_attributes ), ',' )
		);
		// animation timing attributes
		$this->animation_timing_attributes = array(
			'begin' => 'CDATA',
			'dur' => 'CDATA',
			'end' => 'CDATA',
			'min' => 'CDATA',
			'max' => 'CDATA',
			'restart' => 'Enum#always,whennotactive,never',
			'repeatcount' => 'CDATA',
			'repeatdur' => 'CDATA',
			'fill' => 'Enum#freeze|remove'
		);
		// animation value attributes
		$this->animation_value_attributes = array(
			'calcmode' => 'Enum#discrete,linear,paced,spline',
			'values' => 'CDATA',
			'keytimes' => 'CDATA',
			'keysplines' => 'CDATA',
			'from' => 'CDATA',
			'to' => 'CDATA',
			'by' => 'CDATA'
		);
		// animation addition attributes
		$this->animation_addition_attributes = array(
			'additive' => 'Enum#replace,sum',
			'accumulate' => 'Enum#none,sum'
		);
		// animate motion attributes
		$this->animate_motion_attributes = array(
			'path' => 'CDATA',
			'keypoints' => 'CDATA',
			'rotate' => 'Number',
			'origin' => 'Enum#default'
		);
		// linear gradient attributes
		$this->linear_gradient_attributes = array(
			'x1' => 'CDATA',
			'x2' => 'CDATA',
			'y1' => 'CDATA',
			'y2' => 'CDATA',
			'gradientunits' => 'Enum#userspaceonuse,objectboundingbox',
			'gradienttransform' => 'CDATA',
			'spreadmethod' => 'Enum#pad,reflect,repeat'
		);
		// radial gradient attributes
		$this->radial_gradient_attributes = array(
			'cx' => 'CDATA',
			'cy' => 'CDATA',
			'r' => 'CDATA',
			'fx' => 'CDATA',
			'fy' => 'CDATA',
			'gradientunits' => 'Enum#userspaceonuse,objectboundingbox',
			'gradienttransform' => 'CDATA',
			'spreadmethod' => 'Enum#pad,reflect,repeat'
		);
		// filter primitive attributes
		$this->filter_primitive_attributes = array(
			'x' => 'CDATA',
			'y' => 'CDATA',
			'z' => 'CDATA',
			'width' => 'CDATA',
			'height' => 'CDATA',
			'filterres' => 'Number',
			'filterunits' => 'Enum#userspaceonuse,objectboundingbox',
			'primitiveunits' => 'Enum#userspaceonuse,objectboundingbox'
		);
		// filter attributes
		$this->filter_attributes = array(
			'k1' => 'CDATA',
			'k2' => 'CDATA',
			'k3' => 'CDATA',
			'k4' => 'CDATA',
			'operator' => 'Enum#over,in,out,atop,xor,arithmetic,erode,dilate',
			'result' => 'CDATA',
			'in' => 'CDATA',
			'in2' => 'CDATA',
			'order' => 'Number',
			'kernelmatrix' => 'CDATA',
			'divisor' => 'Number',
			'bias' => 'Number',
			'targetX' => 'Number',
			'targetY' => 'Number',
			'edgemode' => 'Enum#duplicate,wrap,none',
			'kernelunitlength' => 'Number',
			'preservealpha' => 'Enum#true,false',
			'surfacescale' => 'Number',
			'diffuseconstant' => 'Number',
			'scale' => 'Number',
			'xchannelselector' => 'Enum#r,g,b,a',
			'ychannelselector' => 'Enum#r,g,b,a',
			'stddeviation' => 'CDATA',
			'preserveaspectratio' => 'CDATA',
			'tablevalues' => 'CDATA',
			'slope' => 'Number',
			'intercept' => 'Number',
			'amplitude' => 'Number',
			'exponent' => 'Number',
			'offset' => 'CDATA',
			'type' => 'CDATA',
			'radius' => 'Number',
			'dx' => 'CDATA',
			'dy' => 'CDATA',
			'basefrequency' => 'Number',
			'numoctaves' => 'Number',
			'seed' => 'Number',
			'stitchtiles' => 'Enum#stitch,nostitch'
		);

		// filter lightsource attributes
		$this->filter_lightsource_attributes = array(
			'azimuth' => 'Number',
			'elevation' => 'Number',
			'pointsatx' => 'Number',
			'pointsaty' => 'Number',
			'pointsatz' => 'Number',
			'specularexponent' => 'Number',
			'limitingconeangle' => 'Number',
			'in' => 'CDATA',
			'surfacescale' => 'Number',
			'specularconstant' => 'Number',
			'specularexponent' => 'Number',
			'kernelunitlength' => 'Number'
		);
		// glyph attributes
		$this->glyph_attributes = array(
			'x' => 'CDATA',
			'y' => 'CDATA',
			'dx' => 'CDATA',
			'dy' => 'CDATA',
			'glyphref' => 'CDATA',
			'format' => 'CDATA',
			'rotate' => 'Number',
			'd' => 'CDATA',
			'horiz-adv-x' => 'Number',
			'vert-origin-x' => 'CDATA',
			'vert-origin-y' => 'CDATA',
			'vert-adv-y' => 'Number',
			'unicode' => 'CDATA',
			'glyph-name' => 'CDATA',
			'orientation' => 'Enum#h,v',
			'arabic-form' => 'Enum#initial,medial,terminal,isolated',
			'lang' => 'CDATA'
		);
		// clip path attributes
		$this->clip_path_attributes = array(
			'transform'	=> 'CDATA',
			'clippathunits' => 'Enum#userspaceonuse,objectboundingbox'
		);
		// color profile attributes
		$this->color_profile_attributes = array(
			'local' => 'CDATA',
			'name' => 'CDATA',
			'rendering-intent' => 'Enum#auto,perceptual,relative-colorimetric,saturation,absolute-colorimetric'
		);
		// cursor attributes
		$this->cursor_attributes = array(
			'x' => 'CDATA',
			'y' => 'CDATA'
		);
		// font attributes
		$this->font_attributes = array(
			'font-family' => 'CDATA',
			'font-size' => 'CDATA',
			'font-size-adjust' => 'CDATA',
			'font-stretch' => 'Enum#normal,wider,narrower,ultra-condensed,extra-condensed,condensed,semi-condensed,semi-expanded,expanded,extra-expanded,ultra-expanded,inherit',
			'font-style' => 'Enum#normal,italic,oblique,inherit',
			'font-variant' => 'Enum#normal,small-caps,inherit',
			'font-weight' => 'Enum#normal,bold,bolder,lighter,100,200,300,400,500,600,700,800,900,inherit',
			'unicode-range' => 'CDATA',
			'units-per-em' => 'Number',
			'panose-1' => 'Number',
			'stemv' => 'Number',
			'stemh' => 'Number',
			'slope' => 'Number',
			'cap-height' => 'Number',
			'x-height' => 'Number',
			'accent-height' => 'Number',
			'ascent' => 'Number',
			'descent' => 'Number',
			'widths' => 'CDATA',
			'bbox' => 'CDATA',
			'ideographic' => 'Number',
			'alphabetic' => 'Number',
			'mathematical' => 'Number',
			'hanging' => 'Number',
			'v-ideographic' => 'Number',
			'v-alphabetic' => 'Number',
			'v-mathematical' => 'Number',
			'v-hanging' => 'Number',
			'underline-position' => 'Number',
			'underline-thickness' => 'Number',
			'strikethrough-position' => 'Number',
			'strikethrough-thickness' => 'Number',
			'overline-position' => 'Number',
			'overline-thickness' => 'Number'
		);
		// font face format attributes
		$this->font_face_format_attributes = array(
			'string' => 'CDATA'
		);
		// font face name attributes
		$this->font_face_name_attributes = array(
			'string' => 'CDATA'
		);
		// marker attributes
		$this->marker_attributes = array(
			'viewbox' => 'CDATA',
			'preserveaspectratio' => 'CDATA',
			'refx' => 'CDATA',
			'refy' => 'CDATA',
			'markerunits' => 'Enum#strokewidth,userspaceonuse',
			'markerwidth' => 'Length',
			'markerheight' => 'Length',
			'orient' => 'Enum#auto,angle',
		);
		// mask attributes
		$this->mask_attributes = array(
			'maskunits' => 'Enum#userspaceonuse,objectboundingbox',
			'maskcontentunits' => 'Enum#userspaceonuse,objectboundingbox',
		);
		// pattern attributes
		$this->pattern_attributes = array(
			'patternunits' => 'Enum#userspaceonuse,objectboundingbox',
			'patterncontentunits' => 'Enum#userspaceonuse,objectboundingbox',
			'patterntransform' => 'CDATA'
		);
		// style attributes
		$this->style_attributes = array(
			'type' => 'CDATA',
			'media' => 'CDATA',
			'title' => 'CDATA'
		);
		// text attributes
		$this->text_attributes = array(
			'lengthadjust' => 'Enum#spacing,spacingandglyphs',
			'x' => 'CDATA',
			'y' => 'CDATA',
			'dx' => 'CDATA',
			'dy' => 'CDATA',
			'rotate' => 'Number',
			'textlength' => 'Length'
		);
		// view attributes
		$this->view_attributes = array(
			'viewbox' => 'CDATA',
			'preserveaspectratio' => 'CDATA',
			'zoomandpan' => 'Enum#disable,magnify',
			'viewtarget' => 'CDATA'
		);
		// textpath attributes
		$this->textpath_attributes = array(
			'startoffset' => 'Length',
			'method' => 'Enum#align,stretch',
			'spacing' => 'Enum#auto,exact'
		);
		// kerning attributes
		$this->kerning_attributes = array(
			'u1' => 'CDATA',
			'g1' => 'CDATA',
			'u2' => 'CDATA',
			'g2' => 'CDATA',
			'k' => 'Number'
		);
		// use tag attributes
		$this->use_tag_attributes = array(
			'xlink:href' => 'URI',
			'x' => 'CDATA',
			'y' => 'CDATA'
		);
	}
}
