<?php
/**
 * See skin.doc
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

/** */
if( defined( 'MEDIAWIKI' ) ) {

global $wgUsePHPTal;
if ($wgUsePHPTal) {
require_once('includes/SkinPHPTal.php');

/**
 * Inherit everything from SkinPHPTal
 * This is a dummy skin as MonoBook is the default PHPTal skin.
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinMonoBookTal extends SkinPHPTal {
	/** Using monobook. */
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname  = 'monobooktal';
		$this->stylename = 'monobook';
		$this->template  = 'MonoBook';
	}
}

}

}
?>
