<?php
/**
 * See skin.txt
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinNostalgia extends Skin {

	function getStylesheet() {
		return 'common/nostalgia.css';
	}
	function getSkinName() {
		return "nostalgia";
	}

	function doBeforeContent() {

		$s = "\n<div id='content'>\n<div id='topbar'>";
		$s .= $this->logoText( "right" );

		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n";

		$s .= $this->topLinks() . "\n<br />";

		$notice = wfGetSiteNotice();
		if( $notice ) {
			$s .= "\n<div id='siteNotice'>$notice</div>\n";
		}
		$s .= $this->pageTitleLinks();

		$ol = $this->otherLanguages();
		if($ol) $s .= "<br />" . $ol;

		$cat = $this->getCategoryLinks();
		if($cat) $s .= "<br />" . $cat;

		$s .= "<br clear='all' /><hr />\n</div>\n";
		$s .= "\n<div id='article'>";

		return $s;
	}

	function topLinks() {
		global $wgOut, $wgUser;
		$sep = " |\n";

		$s = $this->mainPageLink() . $sep
		  . $this->specialLink( "recentchanges" );

		if ( $wgOut->isArticle() ) {
			$s .=  $sep . $this->editThisPage()
			  . $sep . $this->historyLink();
		}
		
		/* show links to different language variants */
		$s .= $this->variantLinks();
		$s .= $this->extensionTabLinks();
		
		if ( $wgUser->isAnon() ) {
			$s .= $sep . $this->specialLink( "userlogin" );
		} else {
			$s .= $sep . $this->specialLink( "userlogout" );
		}
		
		$s .= $sep . $this->specialPagesList();

		return $s;
	}

	function doAfterContent() {
		$s = "\n</div><br clear='all' />\n";

		$s .= "\n<div id='footer'><hr />";

		$s .= $this->bottomLinks();
		$s .= "\n<br />" . $this->pageStats();
		$s .= "\n<br />" . $this->mainPageLink()
		  . " | " . $this->aboutLink()
		  . " | " . $this->searchForm();

		$s .= "\n</div>\n</div>\n";

		return $s;
	}
}

?>
