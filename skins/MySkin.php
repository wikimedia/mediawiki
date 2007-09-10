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
class SkinMySkin extends SkinTemplate {
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'myskin';
		$this->stylename = 'myskin';
		$this->template  = 'MonoBookTemplate';
	}
}

?>
