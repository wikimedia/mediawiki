<?php
/**
 * Amethyst
 *
 * Reuse the MonoBook template (originally a plone style
 * ported on MediaWiki by Gabriel Wicke).
 *
 * @todo document
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** Skin reuse monobook template */
require_once('MonoBook.php');

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @subpackage Skins
 */
class SkinAmethyst extends SkinTemplate {
	/** Using monobook. */
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'amethyst';
		$this->stylename = 'amethyst';
		$this->template  = 'MonobookTemplate';
	}
}

?>
