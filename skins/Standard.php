<?php
/**
 * See skin.doc
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinStandard extends Skin {

	/**
	 *
	 */
	function getHeadScripts() {
		global $wgStylePath;

		$s = parent::getHeadScripts();
		if ( 3 == $this->qbSetting() ) { # Floating left
			$s .= "<script language='javascript' type='text/javascript' " .
			  "src='{$wgStylePath}/common/sticky.js'></script>\n";
		}
		return $s;
	}

	/**
	 *
	 */
	function getUserStyles() {
		global $wgStylePath;
		$s = '';
		if ( 3 == $this->qbSetting() ) { # Floating left
			$s .= "<style type='text/css'>\n" .
			  "@import '{$wgStylePath}/common/quickbar.css';\n</style>\n";
		}
		$s .= parent::getUserStyles();
		return $s;
	}

	/**
	 *
	 */
	function doGetUserStyles() {
		global $wgUser, $wgOut, $wgStylePath;

		$s = parent::doGetUserStyles();
		$qb = $this->qbSetting();

		if ( 2 == $qb ) { # Right
			$s .= "#quickbar { position: absolute; top: 4px; right: 4px; " .
			  "border-left: 2px solid #000000; }\n" .
			  "#article { margin-left: 4px; margin-right: 152px; }\n";
		} else if ( 1 == $qb || 3 == $qb ) {
			$s .= "#quickbar { position: absolute; top: 4px; left: 4px; " .
			  "border-right: 1px solid gray; }\n" .
			  "#article { margin-left: 152px; margin-right: 4px; }\n";
		}
		return $s;
	}

	/**
	 *
	 */
	function getBodyOptions() {
		$a = parent::getBodyOptions();

		if ( 3 == $this->qbSetting() ) { # Floating left
			$qb = "setup(\"quickbar\")";
			if($a["onload"]) {
				$a["onload"] .= ";$qb";
			} else {
				$a["onload"] = $qb;
			}
		}
		return $a;
	}
}

?>
