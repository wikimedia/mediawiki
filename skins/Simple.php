<?php
/**
 * Simple: A lightweight skin with a simple white-background sidebar and no
 * top bar.
 *
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once( dirname(__FILE__) . '/MonoBook.php' );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinSimple extends SkinTemplate {
	var $skinname = 'simple', $stylename = 'simple',
		$template = 'MonoBookTemplate', $useHeadElement = true;

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		$out->addModuleStyles( 'skins.simple' );

		/* Add some userprefs specific CSS styling */
		$rules = array();
		$underline = "";

		if ( $this->getUser()->getOption( 'underline' ) < 2 ) {
			$underline = "text-decoration: " . $this->getUser()->getOption( 'underline' ) ? 'underline !important' : 'none' . ";";
		}
		$style = implode( "\n", $rules );
		$out->addInlineStyle( $style, 'flip' );

	}
}
