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
	var $skinname = 'myskin', $stylename = 'myskin',
		$template = 'MonoBookTemplate', $useHeadElement = true;
}
