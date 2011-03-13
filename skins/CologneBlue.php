<?php
/**
 * Cologne Blue: A nicer-looking alternative to Standard.
 *
 * @todo document
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
class SkinCologneBlue extends SkinLegacy {
	var $skinname = 'cologneblue', $stylename = 'cologneblue',
		$template = 'CologneBlueTemplate';

	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'skins.cologneblue' );

		global $wgContLang;
		$qb = $this->qbSetting();
		$rules = array();

		if ( 2 == $qb ) { # Right
			$rules[] = "#quickbar { position: absolute; right: 4px; }";
			$rules[] = "#article { margin-left: 4px; margin-right: 148px; }";
		} elseif ( 1 == $qb ) {
			$rules[] = "#quickbar { position: absolute; left: 4px; }";
			$rules[] = "#article { margin-left: 148px; margin-right: 4px; }";
		} elseif ( 3 == $qb ) { # Floating left
			$rules[] = "#quickbar { position:absolute; left:4px }";
			$rules[] = "#topbar { margin-left: 148px }";
			$rules[] = "#article { margin-left:148px; margin-right: 4px; }";
			$rules[] = "body>#quickbar { position:fixed; left:4px; top:4px; overflow:auto ;bottom:4px;}"; # Hides from IE
		} elseif ( 4 == $qb ) { # Floating right
			$rules[] = "#quickbar { position: fixed; right: 4px; }";
			$rules[] = "#topbar { margin-right: 148px }";
			$rules[] = "#article { margin-right: 148px; margin-left: 4px; }";
			$rules[] = "body>#quickbar { position: fixed; right: 4px; top: 4px; overflow: auto ;bottom:4px;}"; # Hides from IE
		}
		$style = implode( "\n", $rules );
 		if ( $wgContLang->getDir() === 'rtl' ) {
 			$style = CSSJanus::transform( $style, true, false );
		}
		$out->addInlineStyle( $style );
	}

}

class CologneBlueTemplate extends LegacyTemplate {

	function doBeforeContent() {
		$mainPageObj = Title::newMainPage();

		$s = "\n<div id='content'>\n<div id='topbar'>" .
		  '<table width="100%" border="0" cellspacing="0" cellpadding="8"><tr>';

		$s .= '<td class="top" nowrap="nowrap">';
		$s .= '<a href="' . $mainPageObj->escapeLocalURL() . '">';
		$s .= '<span id="sitetitle">' . wfMsg( 'sitetitle' ) . '</span></a>';

		$s .= '</td><td class="top" id="top-syslinks" width="100%">';
		$s .= $this->sysLinks();
		$s .= '</td></tr><tr><td class="top-linkcollection">';

		$s .= '<font size="-1"><span id="sitesub">';
		$s .= htmlspecialchars( wfMsg( 'sitesubtitle' ) ) . '</span></font>';
		$s .= '</td><td class="top-linkcollection">';

		$s .= '<font size="-1"><span id="langlinks">';
		$s .= str_replace( '<br />', '', $this->otherLanguages() );
		$cat = $this->getSkin()->getCategoryLinks();
		if( $cat ) {
			$s .= "<br />$cat\n";
		}
		$s .= '<br />' . $this->pageTitleLinks();
		$s .= '</span></font>';

		$s .= "</td></tr></table>\n";

		$s .= "\n</div>\n<div id='article'>";

		$notice = $this->getSkin()->getSiteNotice();
		if( $notice ) {
			$s .= "\n<div id='siteNotice'>$notice</div>\n";
		}
		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n";
		return $s;
	}

	function doAfterContent(){
		global $wgLang;

		$s = "\n</div><br clear='all' />\n";

		$s .= "\n<div id='footer'>";
		$s .= '<table width="98%" border="0" cellspacing="0"><tr>';

		$qb = $this->getSkin()->qbSetting();
		if ( 1 == $qb || 3 == $qb ) { # Left
			$s .= $this->getQuickbarCompensator();
		}
		$s .= '<td class="bottom">';

		$s .= $this->bottomLinks();
		$s .= $wgLang->pipeList( array(
			"\n<br />" . $this->getSkin()->link(
				Title::newMainPage(),
				null,
				array(),
				array(),
				array( 'known', 'noclasses' )
			),
			$this->getSkin()->aboutLink(),
			$this->searchForm( wfMsg( 'qbfind' ) )
		) );

		$s .= "\n<br />" . $this->pageStats();

		$s .= '</td>';
		if ( 2 == $qb ) { # Right
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "</tr></table>\n</div>\n</div>\n";

		if ( 0 != $qb ) {
			$s .= $this->quickBar();
		}
		return $s;
	}

	function sysLinks() {
		global $wgUser, $wgLang;
		$li = SpecialPage::getTitleFor( 'Userlogin' );
		$lo = SpecialPage::getTitleFor( 'Userlogout' );

		$rt = $this->getSkin()->getTitle()->getPrefixedURL();
		if ( 0 == strcasecmp( urlencode( $lo ), $rt ) ) {
			$q = array();
		} else {
			$q = array( 'returnto' => $rt );
		}

		$s = array(
			$this->getSkin()->mainPageLink(),
			$this->getSkin()->linkKnown(
				Title::newFromText( wfMsgForContent( 'aboutpage' ) ),
				wfMsg( 'about' )
			),
			$this->getSkin()->linkKnown(
				Title::newFromText( wfMsgForContent( 'helppage' ) ),
				wfMsg( 'help' )
			),
			$this->getSkin()->linkKnown(
				Title::newFromText( wfMsgForContent( 'faqpage' ) ),
				wfMsg( 'faq' )
			),
			$this->getSkin()->specialLink( 'Specialpages' )
		);

		/* show links to different language variants */
		if( $this->variantLinks() ) {
			$s[] = $this->variantLinks();
		}
		if( $this->extensionTabLinks() ) {
			$s[] = $this->extensionTabLinks();
		}
		if ( $wgUser->isLoggedIn() ) {
			$s[] = $this->getSkin()->linkKnown(
				$lo,
				wfMsg( 'logout' ),
				array(),
				$q
			);
		} else {
			$s[] = $this->getSkin()->linkKnown(
				$li,
				wfMsg( 'login' ),
				array(),
				$q
			);
		}

		return $wgLang->pipeList( $s );
	}

	/**
	 * Compute the sidebar
	 * @access private
	 */
	function quickBar(){
		global $wgOut, $wgUser;

		$tns = $this->getSkin()->getTitle()->getNamespace();

		$s = "\n<div id='quickbar'>";

		$sep = '<br />';
		$s .= $this->menuHead( 'qbfind' );
		$s .= $this->searchForm();

		$s .= $this->menuHead( 'qbbrowse' );

		# Use the first heading from the Monobook sidebar as the "browse" section
		$bar = $this->getSkin()->buildSidebar();
		unset( $bar['SEARCH'] );
		unset( $bar['LANGUAGES'] );
		unset( $bar['TOOLBOX'] );

		$barnumber = 1;
		foreach ( $bar as $heading => $browseLinks ) {
			$heading_text = wfMsg ( $heading );
			if ( $barnumber > 1 ) {
				if ( wfEmptyMsg( $heading, $heading_text ) ) {
					$h = $heading;
				} else {
					$h = $heading_text;
				}
				$s .= "\n<h6>" . htmlspecialchars( $h ) . "</h6>";
			}
			foreach ( $browseLinks as $link ) {
				if ( $link['text'] != '-' ) {
					$s .= "<a href=\"{$link['href']}\">" .
						htmlspecialchars( $link['text'] ) . '</a>' . $sep;
				}
			}
			$barnumber = $barnumber + 1;
		}

		if ( $wgOut->isArticle() ) {
			$s .= $this->menuHead( 'qbedit' );
			$s .= '<strong>' . $this->editThisPage() . '</strong>';

			$s .= $sep . $this->getSkin()->linkKnown(
				Title::newFromText( wfMsgForContent( 'edithelppage' ) ),
				wfMsg( 'edithelp' )
			);

			if( $wgUser->isLoggedIn() ) {
				$s .= $sep . $this->moveThisPage();
			}
			if ( $wgUser->isAllowed( 'delete' ) ) {
				$dtp = $this->deleteThisPage();
				if ( $dtp != '' ) {
					$s .= $sep . $dtp;
				}
			}
			if ( $wgUser->isAllowed( 'protect' ) ) {
				$ptp = $this->protectThisPage();
				if ( $ptp != '' ) {
					$s .= $sep . $ptp;
				}
			}
			$s .= $sep;

			$s .= $this->menuHead( 'qbpageoptions' );
			$s .= $this->talkLink()
					. $sep . $this->commentLink()
					. $sep . $this->printableLink();
			if ( $wgUser->isLoggedIn() ) {
				$s .= $sep . $this->watchThisPage();
			}

			$s .= $sep;

			$s .= $this->menuHead( 'qbpageinfo' )
					. $this->historyLink()
					. $sep . $this->whatLinksHere()
					. $sep . $this->watchPageLinksLink();

			if( $tns == NS_USER || $tns == NS_USER_TALK ) {
				$id = User::idFromName( $this->getSkin()->getTitle()->getText() );
				if( $id != 0 ) {
					$s .= $sep . $this->userContribsLink();
					if( $this->getSkin()->showEmailUser( $id ) ) {
						$s .= $sep . $this->emailUserLink();
					}
				}
			}
			$s .= $sep;
		}

		$s .= $this->menuHead( 'qbmyoptions' );
		if ( $wgUser->isLoggedIn() ) {
			$tl = $this->getSkin()->link(
				$wgUser->getTalkPage(),
				wfMsg( 'mytalk' ),
				array(),
				array(),
				array( 'known', 'noclasses' )
			);
			if ( $wgUser->getNewtalk() ) {
				$tl .= ' *';
			}

			$s .= $this->getSkin()->link(
					$wgUser->getUserPage(),
					wfMsg( 'mypage' ),
					array(),
					array(),
					array( 'known', 'noclasses' )
				) . $sep . $tl . $sep . $this->getSkin()->specialLink( 'Watchlist' )
					. $sep .
				$this->getSkin()->link(
					SpecialPage::getSafeTitleFor( 'Contributions', $wgUser->getName() ),
					wfMsg( 'mycontris' ),
					array(),
					array(),
					array( 'known', 'noclasses' )
				) . $sep . $this->getSkin()->specialLink( 'Preferences' )
				. $sep . $this->getSkin()->specialLink( 'Userlogout' );
		} else {
			$s .= $this->getSkin()->specialLink( 'Userlogin' );
		}

		$s .= $this->menuHead( 'qbspecialpages' )
			. $this->getSkin()->specialLink( 'Newpages' )
			. $sep . $this->getSkin()->specialLink( 'Listfiles' )
			. $sep . $this->getSkin()->specialLink( 'Statistics' );
		if( UploadBase::isEnabled() && UploadBase::isAllowed( $wgUser ) === true ) {
			$s .= $sep . $this->getUploadLink();
		}

		global $wgSiteSupportPage;

		if( $wgSiteSupportPage ) {
			$s .= $sep . '<a href="' . htmlspecialchars( $wgSiteSupportPage ) . '" class="internal">'
					. wfMsg( 'sitesupport' ) . '</a>';
		}

		$s .= $sep . $this->getSkin()->link(
			SpecialPage::getTitleFor( 'Specialpages' ),
			wfMsg( 'moredotdotdot' ),
			array(),
			array(),
			array( 'known', 'noclasses' )
		);

		$s .= $sep . "\n</div>\n";
		return $s;
	}

	function menuHead( $key ) {
		$s = "\n<h6>" . wfMsg( $key ) . "</h6>";
		return $s;
	}

	function searchForm( $label = '' ) {
		global $wgRequest, $wgUseTwoButtonsSearchForm;

		$search = $wgRequest->getText( 'search' );
		$action = $this->data['searchaction'];
		$s = "<form id=\"searchform{$this->searchboxes}\" method=\"get\" class=\"inline\" action=\"$action\">";
		if( $label != '' ) {
			$s .= "{$label}: ";
		}

		$s .= "<input type='text' id=\"searchInput{$this->searchboxes}\" class=\"mw-searchInput\" name=\"search\" size=\"14\" value=\""
			. htmlspecialchars( substr( $search, 0, 256 ) ) . "\" /><br />"
			. "<input type='submit' id=\"searchGoButton{$this->searchboxes}\" class=\"searchButton\" name=\"go\" value=\"" . htmlspecialchars( wfMsg( 'searcharticle' ) ) . "\" />";

		if( $wgUseTwoButtonsSearchForm ) {
			$s .= "<input type='submit' id=\"mw-searchButton{$this->searchboxes}\" class=\"searchButton\" name=\"fulltext\" value=\"" . htmlspecialchars( wfMsg( 'search' ) ) . "\" />\n";
		} else {
			$s .= '<div><a href="' . $action . '" rel="search">' . wfMsg( 'powersearch-legend' ) . "</a></div>\n";
		}

		$s .= '</form>';

		// Ensure unique id's for search boxes made after the first
		$this->searchboxes = $this->searchboxes == '' ? 2 : $this->searchboxes + 1;

		return $s;
	}
}
