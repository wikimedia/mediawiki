<?php
/**
 * Nostalgia: A skin which looks like Wikipedia did in its first year (2001).
 *
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

/**
 * @todo document
 * @ingroup Skins
 */
class SkinNostalgia extends Skin {

	function getStylesheet() {
		return 'common/nostalgia.css';
	}

	function getSkinName() {
		return 'nostalgia';
	}

	function doBeforeContent() {
		$s = "\n<div id='content'>\n<div id='top'>\n";
		$s .= '<div id="logo">' . $this->logoText( 'right' ) . '</div>';

		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n";

		$s .= '<div id="topbar">';
		$s .= $this->topLinks() . "\n<br />";

		$notice = wfGetSiteNotice();
		if( $notice ) {
			$s .= "\n<div id='siteNotice'>$notice</div>\n";
		}
		$s .= $this->pageTitleLinks();

		$ol = $this->otherLanguages();
		if( $ol ) {
			$s .= '<br />' . $ol;
		}

		$cat = $this->getCategoryLinks();
		if( $cat ) {
			$s .= '<br />' . $cat;
		}

		$s .= "<br clear='all' /></div><hr />\n</div>\n";
		$s .= "\n<div id='article'>";

		return $s;
	}

	function topLinks() {
		global $wgOut, $wgUser, $wgEnableUploads;
		$sep = " |\n";

		$s = $this->mainPageLink() . $sep
		  . $this->specialLink( 'recentchanges' );

		if ( $wgOut->isArticle() ) {
			$s .= $sep . '<strong>' . $this->editThisPage() . '</strong>' . $sep . $this->historyLink();
		}

		/* show links to different language variants */
		$s .= $this->variantLinks();
		$s .= $this->extensionTabLinks();
		if ( $wgUser->isAnon() ) {
			$s .= $sep . $this->specialLink( 'userlogin' );
		} else {
			$name = $wgUser->getName();
			/* show user page and user talk links */
			$s .= $sep . $this->link( $wgUser->getUserPage(), wfMsgHtml( 'mypage' ) );
			$s .= $sep . $this->link( $wgUser->getTalkPage(), wfMsgHtml( 'mytalk' ) );
			if ( $wgUser->getNewtalk() ) {
				$s .= ' *';
			}
			/* show watchlist link */
			$s .= $sep . $this->specialLink( 'watchlist' );
			/* show my contributions link */
			$s .= $sep . $this->link(
				SpecialPage::getSafeTitleFor( 'Contributions', $wgUser->getName() ),
				wfMsgHtml( 'mycontris' ) );
			/* show my preferences link */
			$s .= $sep . $this->specialLink( 'preferences' );
			/* show upload file link */
			if( UploadBase::isEnabled() && UploadBase::isAllowed( $wgUser ) === true ) {
				$s .= $sep . $this->getUploadLink();
			}

			/* show log out link */
			$s .= $sep . $this->specialLink( 'userlogout' );
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
				. ' | ' . $this->aboutLink()
				. ' | ' . $this->searchForm();

		$s .= "\n</div>\n</div>\n";

		return $s;
	}
}
