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

        function doAfterContent() {
                global $wgUser, $wgOut, $wgContLang;
                $fname =  'SkinStandard::doAfterContent';
                wfProfileIn( $fname );
                wfProfileIn( $fname.'-1' );

                $s = "\n</div><br style=\"clear:both\" />\n";
                $s .= "\n<div id='footer'>";
                $s .= '<table border="0" cellspacing="0"><tr>';

                wfProfileOut( $fname.'-1' );
                wfProfileIn( $fname.'-2' );

                $qb = $this->qbSetting();
                $shove = ($qb != 0);
                $left = ($qb == 1 || $qb == 3);
                if($wgContLang->isRTL()) $left = !$left;

                if ( $shove && $left ) { # Left
                        $s .= $this->getQuickbarCompensator();
                }
                wfProfileOut( $fname.'-2' );
                wfProfileIn( $fname.'-3' );
                $l = $wgContLang->isRTL() ? 'right' : 'left';
                $s .= "<td class='bottom' align='$l' valign='top'>";

                $s .= $this->bottomLinks();
                $s .= "\n<br />" . $this->mainPageLink()
                  . ' | ' . $this->aboutLink()
                  . ' | ' . $this->specialLink( 'recentchanges' )
                  . ' | ' . $this->searchForm()
                  . '<br /><span id="pagestats">' . $this->pageStats() . '</span>';

                $s .= "</td>";
                if ( $shove && !$left ) { # Right
                        $s .= $this->getQuickbarCompensator();
                }
                $s .= "</tr></table>\n</div>\n</div>\n";

                wfProfileOut( $fname.'-3' );
                wfProfileIn( $fname.'-4' );
                if ( 0 != $qb ) { $s .= $this->quickBar(); }
                wfProfileOut( $fname.'-4' );
                wfProfileOut( $fname );
                return $s;
        }

}

?>
