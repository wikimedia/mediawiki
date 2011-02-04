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
class SkinNostalgia extends SkinLegacy {
	var $skinname = 'nostalgia', $stylename = 'nostalgia',
		$template = 'NostalgiaTemplate';

	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'skins.nostalgia' );
	}

}

class NostalgiaTemplate extends LegacyTemplate {

	function doBeforeContent() {
		$s = "\n<div id='content'>\n<div id='top'>\n";
		$s .= '<div id="logo">' . $this->getSkin()->logoText( 'right' ) . '</div>';

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

		$cat = $this->getSkin()->getCategoryLinks();
		if( $cat ) {
			$s .= '<br />' . $cat;
		}

		$s .= "<br clear='all' /></div><hr />\n</div>\n";
		$s .= "\n<div id='article'>";

		return $s;
	}

	function topLinks() {
		global $wgOut, $wgUser;
		$sep = " |\n";

		$s = $this->getSkin()->mainPageLink() . $sep
		  . $this->getSkin()->specialLink( 'Recentchanges' );

		if ( $wgOut->isArticle() ) {
			$s .= $sep . '<strong>' . $this->editThisPage() . '</strong>' . $sep . $this->historyLink();
		}

		/* show links to different language variants */
		$s .= $this->variantLinks();
		$s .= $this->extensionTabLinks();
		if ( $wgUser->isAnon() ) {
			$s .= $sep . $this->getSkin()->specialLink( 'Userlogin' );
		} else {
			/* show user page and user talk links */
			$s .= $sep . $this->getSkin()->link( $wgUser->getUserPage(), wfMsgHtml( 'mypage' ) );
			$s .= $sep . $this->getSkin()->link( $wgUser->getTalkPage(), wfMsgHtml( 'mytalk' ) );
			if ( $wgUser->getNewtalk() ) {
				$s .= ' *';
			}
			/* show watchlist link */
			$s .= $sep . $this->getSkin()->specialLink( 'Watchlist' );
			/* show my contributions link */
			$s .= $sep . $this->getSkin()->link(
				SpecialPage::getSafeTitleFor( 'Contributions', $wgUser->getName() ),
				wfMsgHtml( 'mycontris' ) );
			/* show my preferences link */
			$s .= $sep . $this->getSkin()->specialLink( 'Preferences' );
			/* show upload file link */
			if( UploadBase::isEnabled() && UploadBase::isAllowed( $wgUser ) === true ) {
				$s .= $sep . $this->getUploadLink();
			}

			/* show log out link */
			$s .= $sep . $this->getSkin()->specialLink( 'Userlogout' );
		}

		$s .= $sep . $this->specialPagesList();

		return $s;
	}

	function doAfterContent() {
		$s = "\n</div><br clear='all' />\n";

		$s .= "\n<div id='footer'><hr />";

		$s .= $this->bottomLinks();
		$s .= "\n<br />" . $this->pageStats();
		$s .= "\n<br />" . $this->getSkin()->mainPageLink()
				. ' | ' . $this->getSkin()->aboutLink()
				. ' | ' . $this->searchForm();

		$s .= "\n</div>\n</div>\n";

		return $s;
	}
}
