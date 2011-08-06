<?php
/**
 * Chick: A lightweight Monobook skin with no sidebar, the sidebar links are
 * given at the bottom of the page instead, as in the unstyled MySkin.
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
class SkinChick extends SkinTemplate {
	var $skinname = 'chick', $stylename = 'chick',
	$template = 'MonoBookTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );

		$out->addModuleStyles( 'skins.chick' );

		// TODO: Migrate all of these to RL
		$out->addStyle( 'chick/IE50Fixes.css', 'screen,handheld', 'lt IE 5.5000' );
		$out->addStyle( 'chick/IE55Fixes.css', 'screen,handheld', 'IE 5.5000' );
		$out->addStyle( 'chick/IE60Fixes.css', 'screen,handheld', 'IE 6' );
	}
}
