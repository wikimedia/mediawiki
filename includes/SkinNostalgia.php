<?php
# See skin.doc

class SkinNostalgia extends Skin {

	function initPage()
	{
		# ...
	}

	function getStylesheet()
	{
		return "nostalgia.css";
	}

	function doBeforeContent()
	{
		global $wgUser, $wgOut, $wgTitle;

		$s = "\n<div id='content'>\n<div id='topbar'>";
		$s .= $this->logoText( "right" );

		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n";

		$s .= $this->topLinks() . "\n<br />";
		$s .= $this->pageTitleLinks();

		$ol = $this->otherLanguages();
		if($ol) $s .= "<br />" . $ol;

		$s .= "<br clear='all' /><hr />\n</div>\n";
		$s .= "\n<div id='article'>";

		return $s;
	}

	function topLinks()
	{
		global $wgOut, $wgUser;
		$sep = " |\n";

		$s = $this->mainPageLink() . $sep
		  . $this->specialLink( "recentchanges" );

		if ( $wgOut->isArticle() ) {
			$s .=  $sep . $this->editThisPage()
			  . $sep . $this->historyLink();
		}
		if ( 0 == $wgUser->getID() ) {
			$s .= $sep . $this->specialLink( "userlogin" );
		} else {
			$s .= $sep . $this->specialLink( "userlogout" );
		}
		$s .= $sep . $this->specialPagesList();

		return $s;
	}

	function doAfterContent()
	{
		global $wgUser, $wgOut;

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
