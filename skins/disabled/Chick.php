<?php
/**
 * See skin.doc
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

/** */
require_once('includes/SkinPHPTal.php');
if( class_exists( 'SkinPHPTal' ) ) {

/**
 * See skin.doc
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinChick extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'chick';
		$this->template = 'Chick';
	}
	function suppressUrlExpansion() { return true; }
	function printSource() { return ''; }
}

}
?>
