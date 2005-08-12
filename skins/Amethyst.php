<?php
/**
 * MonoBook nouveau
 *
 * Translated from gwicke's previous TAL template version to remove
 * dependency on PHPTAL.
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
