<?php
/**
 * See skin.txt
 *
 * @todo document
 * @addtogroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once( dirname(__FILE__) . '/MonoBook.php' );

/**
 * @todo document
 * @addtogroup Skins
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
