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

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'skins.nostalgia' );
	}

}

class NostalgiaTemplate extends LegacyTemplate {

	/**
	 * @return string
	 */
	function doBeforeContent() {
		$s = "\n<div id='content'>\n<div id='top'>\n";
		$s .= '<div id="logo">' . $this->getSkin()->logoText( 'right' ) . '</div>';

		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n";

		$s .= '<div id="topbar">';
		$s .= $this->topLinks() . "\n<br />";

		$notice = $this->getSkin()->getSiteNotice();
		if( $notice ) {
			$s .= "\n<div id='siteNotice'>$notice</div>\n";
		}
		$s .= $this->pageTitleLinks();

		$ol = $this->otherLanguages();
		if( $ol ) {
			$s .= '<br />' . $ol;
		}

		$s .= $this->getSkin()->getCategories();

		$s .= "<br clear='all' /></div><hr />\n</div>\n";
		$s .= "\n<div id='article'>";

		return $s;
	}

	/**
	 * @return string
	 */
	function topLinks() {
		$sep = " |\n";

		$s = $this->getSkin()->mainPageLink() . $sep
		  . Linker::specialLink( 'Recentchanges' );

		if ( $this->data['isarticle'] ) {
			$s .= $sep . '<strong>' . $this->editThisPage() . '</strong>' . $sep . $this->talkLink() .
					$sep . $this->historyLink();
		}

		/* show links to different language variants */
		$s .= $this->variantLinks();
		$s .= $this->extensionTabLinks();
		if ( !$this->data['loggedin'] ) {
			$s .= $sep . Linker::specialLink( 'Userlogin' );
		} else {
			/* show user page and user talk links */
			$user = $this->getSkin()->getUser();
			$s .= $sep . Linker::link( $user->getUserPage(), wfMsgHtml( 'mypage' ) );
			$s .= $sep . Linker::link( $user->getTalkPage(), wfMsgHtml( 'mytalk' ) );
			if ( $user->getNewtalk() ) {
				$s .= ' *';
			}
			/* show watchlist link */
			$s .= $sep . Linker::specialLink( 'Watchlist' );
			/* show my contributions link */
			$s .= $sep . Linker::link(
				SpecialPage::getSafeTitleFor( 'Contributions', $this->data['username'] ),
				wfMsgHtml( 'mycontris' ) );
			/* show my preferences link */
			$s .= $sep . Linker::specialLink( 'Preferences' );
			/* show upload file link */
			if( UploadBase::isEnabled() && UploadBase::isAllowed( $user ) === true ) {
				$s .= $sep . $this->getUploadLink();
			}

			/* show log out link */
			$s .= $sep . Linker::specialLink( 'Userlogout' );
		}

		$s .= $sep . $this->specialPagesList();

		return $s;
	}

	/**
	 * @return string
	 */
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
