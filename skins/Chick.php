<?php
/**
 * See docs/skin.txt
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once( dirname(__FILE__) . '/MonoBook.php' );

/**
 * @todo document
 * @ingroup Skins
 */
class SkinChick extends SkinTemplate {
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'chick';
		$this->stylename = 'chick';
		$this->template  = 'MonoBookTemplate';
		$this->fixfiles  = array( 'IE50', 'IE55', 'IE60' );
	}
}


