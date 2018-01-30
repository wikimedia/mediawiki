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

	public $skinname = 'fallback';
	public $template = SkinFallbackTemplate::class;

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param OutputPage $out
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'mediawiki.skinning.interface' );
	}

	/**
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$out->enableClientCache( false );
	}
}
