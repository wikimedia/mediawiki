<?php
/**
 * Amethyst skin
 * Original design by Sorkhiri and Sorwena members of the EverQuest
 * <Amethyst> guild
 * Ported to MediaWiki by Ashar Voultoiz
 *
 * See skin.txt for documentation
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die();

/** */
require_once('includes/SkinPHPTal.php');
if( class_exists( 'SkinPHPTal' ) ) {

/**
 * See skin.txt
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
