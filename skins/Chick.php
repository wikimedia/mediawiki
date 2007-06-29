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
class SkinChick extends SkinTemplate {
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'chick';
		$this->stylename = 'chick';
		$this->template  = 'MonoBookTemplate';
	}
}


