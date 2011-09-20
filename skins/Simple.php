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

	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		$out->addModuleStyles( 'skins.simple' );

		/* Add some userprefs specific CSS styling */
		global $wgUser;
		$rules = array();
		$underline = "";

		if ( $wgUser->getOption( 'underline' ) < 2 ) {
			$underline = "text-decoration: " . $wgUser->getOption( 'underline' ) ? 'underline !important' : 'none' . ";";
		}

		/* Also inherits from resourceloader */
		if( !$wgUser->getOption( 'highlightbroken' ) ) {
			$rules[] = "a.new, a.stub { color: inherit; text-decoration: inherit;}";
			$rules[] = "a.new:after { color: #CC2200; $underline;}";
			$rules[] = "a.stub:after { $underline; }";
		}
		$style = implode( "\n", $rules );
		$out->addInlineStyle( $style, 'flip' );

	}
}
