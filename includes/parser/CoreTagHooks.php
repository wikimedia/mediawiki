<?php
/**
 * Tag hooks provided by MediaWiki core
 *
 * @file
 */

/**
 * Various tag hooks, registered in Parser::firstCallInit()
 * @ingroup Parser
 */
class CoreTagHooks {
	/**
	 * @param $parser Parser
	 * @return void
	 */
	static function register( $parser ) {
		global $wgRawHtml;
		$parser->setHook( 'pre', array( __CLASS__, 'pre' ) );
		$parser->setHook( 'nowiki', array( __CLASS__, 'nowiki' ) );
		$parser->setHook( 'gallery', array( __CLASS__, 'gallery' ) );
		if ( $wgRawHtml ) {
			$parser->setHook( 'html', array( __CLASS__, 'html' ) );
		}
	}

	static function pre( $text, $attribs, $parser ) {
		// Backwards-compatibility hack
		$content = StringUtils::delimiterReplace( '<nowiki>', '</nowiki>', '$1', $text, 'i' );

		$attribs = Sanitizer::validateTagAttributes( $attribs, 'pre' );
		return Xml::openElement( 'pre', $attribs ) .
			Xml::escapeTagsOnly( $content ) .
			'</pre>';
	}

	static function html( $content, $attributes, $parser ) {
		global $wgRawHtml;
		if( $wgRawHtml ) {
			return array( $content, 'markerType' => 'nowiki' );
		} else {
			throw new MWException( '<html> extension tag encountered unexpectedly' );
		}
	}

	static function nowiki( $content, $attributes, $parser ) {
		$content = strtr( $content, array( '-{' => '-&#123;', '}-' => '&#125;-' ) );
		return array( Xml::escapeTagsOnly( $content ), 'markerType' => 'nowiki' );
	}

	/**
	 * @param  $content
	 * @param  $attributes
	 * @param $parser Parser
	 * @return
	 */
	static function gallery( $content, $attributes, $parser ) {
		return $parser->renderImageGallery( $content, $attributes );
	}
}
