<?php
# See skin.doc

class SkinStandard extends Skin {

	function getHeadScripts()
	{
		global $wgStyleSheetPath;

		$s = parent::getHeadScripts();
		if ( 3 == $this->qbSetting() ) { # Floating left
			$s .= "<script language='javascript' type='text/javascript' " .
			  "src='{$wgStyleSheetPath}/sticky.js'></script>\n";
		}
		return $s;
	}

	function getUserStyles()
	{
		global $wgStyleSheetPath;

		$s = parent::getUserStyles();
		if ( 3 == $this->qbSetting() ) { # Floating left
			$s .= "<style type='text/css'>\n" .
			  "@import '{$wgStyleSheetPath}/quickbar.css';\n</style>\n";
		}
		return $s;
	}

	function doGetUserStyles()
	{
		global $wgUser, $wgOut, $wgStyleSheetPath;

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

	function getBodyOptions()
	{
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
