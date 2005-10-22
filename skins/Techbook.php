<?php
/**
 * Techbook
 *
 * (c) Nikerabbit
 *
 * License: GPL
 *
 * Reuse the MonoBook template (originally a plone style
 * ported on MediaWiki by Gabriel Wicke).
 *
 * @todo document
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die();

/** Skin reuse monobook template */
require_once('MonoBook.php');

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @subpackage Skins
 */
class SkinTechbook extends SkinTemplate {
	/** Using monobook. */
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'techbook';
		$this->stylename = 'techbook';
		$this->template  = 'MonobookTemplate';
	}
}

?>
