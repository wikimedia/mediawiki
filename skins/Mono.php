<?php
/**
 * See skin.doc
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

/** */
if ($wgUsePHPTal) {
require_once('includes/SkinPHPTal.php');

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinMono extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'mono';
		$this->stylename = 'monobook';
		$this->template = 'MonoBook';
	}
}

}
?>
