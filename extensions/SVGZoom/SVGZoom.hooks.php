<?php
/**
 * Hooks for Usability Initiative extensions
 *
 * @file
 * @ingroup Extensions
 */

class SVGZoomHooks {

	/* Static Members */
	
	/**
	 * BeforePageDisplay hook
	 * Adds scripts
	 */
	public static function addResources( $out ) {
		global $wgTitle, $wgJsMimeType, $wgScriptPath, $wgSVGZoomScriptVersion;
		
		/*
		 * We should probably check if we are in the image namespace, and if the $wgTitle->getText() ends in .svg
		 */
		
		$out->addScript(
			Xml::element(
				'script',
				array(
					'type' => $wgJsMimeType,
					'src' => "{$wgScriptPath}/extensions/SVGZoom/SVGZoom.js?{$wgSVGZoomScriptVersion}",
				),
				'',
				false
			)
		);
		return true;
	}
}