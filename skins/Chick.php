<?php
/**
 * See skin.txt
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once('MonoBook.php');

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinChick extends SkinTemplate {
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'chick';
		$this->stylename = 'chick';
		$this->template  = 'MonoBookTemplate';
	}
}

?>
