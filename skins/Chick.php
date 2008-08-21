<?php
/**
 * See docs/skin.txt
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once( dirname(__FILE__) . '/MonoBook.php' );

/**
 * @todo document
 * @ingroup Skins
 */
class SkinChick extends SkinTemplate {
	function initPage( OutputPage $out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'chick';
		$this->stylename = 'chick';
		$this->template  = 'MonoBookTemplate';
	}

	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		// Append to the default screen common & print styles...
		$out->addStyle( 'chick/main.css', 'screen,handheld' );
		$out->addStyle( 'chick/IE50Fixes.css', 'screen,handheld', 'lt IE 5.5000' );
		$out->addStyle( 'chick/IE55Fixes.css', 'screen,handheld', 'IE 5.5000' );
		$out->addStyle( 'chick/IE60Fixes.css', 'screen,handheld', 'IE 6' );
	}
}


