<?php
/**
 * Skin file for the fallback skin.
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

	public function getDefaultModules() {
		$modules = parent::getDefaultModules();
		$modules['styles']['skin'][] = 'mediawiki.skinning.interface';
		return $modules;
	}

	/**
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$out->enableClientCache( false );
	}
}
