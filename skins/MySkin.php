<?php
/**
 * MySkin: Monobook without the CSS. The idea is that you
 * customise it using user or site CSS
 *
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinMySkin extends SkinTemplate {
	function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$this->skinname  = 'myskin';
		$this->stylename = 'myskin';
		$this->template  = 'MonoBookTemplate';
	}
}
