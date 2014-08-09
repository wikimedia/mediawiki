<?php
/**
 * Skin file for the fallback skin.
 *
 * The structure is copied from the example skin (mediawiki/skins/Example).
 *
 * @since 1.24
 * @file
 */

/**
 * SkinTemplate class for the fallback skin
 */
class SkinFallback extends SkinTemplate {
	var $skinname = 'fallback', $template = 'SkinFallbackTemplate';

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'mediawiki.skinning.interface' );
	}
}
