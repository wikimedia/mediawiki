<?php
/**
 * Amethyst skin
 * Original design by Sorkhiri and Sorwena members of the EverQuest
 * <Amethyst> guild
 * Ported to MediaWiki by Ashar Voultoiz
 *
 * See skin.doc for documentation
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

/** */
if ($wgUsePHPTal) {
require_once('includes/SkinPHPTal.php');

/**
 * See skin.doc
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinAmethyst extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname  = 'amethyst';
		$this->stylename = 'amethyst';
		$this->template  = 'Amethyst';
	}
}

}
?>
