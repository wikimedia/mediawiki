<?php
/**
 * Hooks for EditSectionHiliteLink extension
 *
 * @file
 * @ingroup Extensions
 */

class EditSectionHiliteLinkHooks {

	/* Static Functions */

	/**
	 * interceptLink hook
	 */
	public static function interceptLink($this, $nt, $section, $tooltip, &$result)  {
		global $wgSectionContainers;
		
		if ( $wgSectionContainers ) {
			$section = $section -1;
			$section_name = 'section_' . $section . '_container';
			$result = preg_replace('/(\D+)( title=)(\D+)/', '${1} onmouseover="editSectionHiliteOn(\'' . $section_name . '\')" onmouseout="editSectionHiliteOff(\'' . $section_name . '\')" title=$3', $result);
		}
		return true;
	}

	/**
	 * AjaxAddScript hook
	 * Add ajax support script
	 */
	public static function addJS(
		$out
	) {
		global $wgScriptPath, $wgJsMimeType, $wgEditSectionHiliteLinkStyleVersion;
		// FIXME: assumes standard dir structure
		// Add javascript to support section edit link highlighting saving
		$out->addScript(
			Xml::element(
				'script',
				array(
					'type' => $wgJsMimeType,
					'src' => $wgScriptPath . '/extensions/EditSectionHiliteLink/EditSectionHiliteLink.js?' .
						$wgEditSectionHiliteLinkStyleVersion
				),
				'',
				false
			)
		);
		// Continue
		return true;
	}

	/**
	 * BeforePageDisplay hook
	 * Add css style sheet
	 */
	public static function addCSS(
		$out
	) {
		global $wgScriptPath, $wgEditSectionHiliteLinkStyleVersion;
		// FIXME: assumes standard dir structure
		// Add css for various styles
		$out->addLink(
			array(
				'rel' => 'stylesheet',
				'type' => 'text/css',
				'href' => $wgScriptPath . '/extensions/EditSectionHiliteLink/EditSectionHiliteLink.css?' .
					$wgEditSectionHiliteLinkStyleVersion,
			)
		);
		// Continue
		return true;
	}
}
