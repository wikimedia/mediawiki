<?php
/**
 * See skin.txt
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die();

/** */
require_once('MonoBook.php');

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinSimple extends SkinTemplate {
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'simple';
		$this->stylename = 'simple';
		$this->template  = 'MonoBookTemplate';
	}
}

?>
