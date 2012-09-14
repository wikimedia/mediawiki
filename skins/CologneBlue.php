<?php
/**
 * Cologne Blue: A nicer-looking alternative to Standard.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
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

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'skins.cologneblue' );

		$qb = $this->qbSetting();
		$rules = array();

		if ( 2 == $qb ) { # Right
			$rules[] = "/* @noflip */#quickbar { position: absolute; right: 4px; }";
			$rules[] = "/* @noflip */#article { margin-left: 4px; margin-right: 148px; }";
			$rules[] = "/* @noflip */#footer { margin-right: 152px; }";
		} elseif ( 1 == $qb ) {
			$rules[] = "/* @noflip */#quickbar { position: absolute; left: 4px; }";
			$rules[] = "/* @noflip */#article { margin-left: 148px; margin-right: 4px; }";
			$rules[] = "/* @noflip */#footer { margin-left: 152px; }";
		} elseif ( 3 == $qb ) { # Floating left
			$rules[] = "/* @noflip */#quickbar { position:absolute; left:4px }";
			$rules[] = "/* @noflip */#topbar { margin-left: 148px }";
			$rules[] = "/* @noflip */#article { margin-left:148px; margin-right: 4px; }";
			$rules[] = "/* @noflip */body>#quickbar { position:fixed; left:4px; top:4px; overflow:auto; bottom:4px;}"; # Hides from IE
			$rules[] = "/* @noflip */#footer { margin-left: 152px; }";
		} elseif ( 4 == $qb ) { # Floating right
			$rules[] = "/* @noflip */#quickbar { position: fixed; right: 4px; }";
			$rules[] = "/* @noflip */#topbar { margin-right: 148px }";
			$rules[] = "/* @noflip */#article { margin-right: 148px; margin-left: 4px; }";
			$rules[] = "/* @noflip */body>#quickbar { position: fixed; right: 4px; top: 4px; overflow: auto; bottom:4px;}"; # Hides from IE
			$rules[] = "/* @noflip */#footer { margin-right: 152px; }";
		}
		$style = implode( "\n", $rules );
		$out->addInlineStyle( $style, 'flip' );
	}

}

class CologneBlueTemplate extends LegacyTemplate {

	/**
	 * @return string
	 */
	function doBeforeContent() {
		$mainPageObj = Title::newMainPage();

		$s = "\n<div id='content'>\n<div id='topbar'>" .
		  '<table width="100%" cellspacing="0" cellpadding="8"><tr>';

		$s .= '<td class="top" nowrap="nowrap">';
		$s .= '<a href="' . htmlspecialchars( $mainPageObj->getLocalURL() ) . '">';
		$s .= '<span id="sitetitle">' . wfMessage( 'sitetitle' )->escaped() . '</span></a>';

		$s .= '</td><td class="top" id="top-syslinks" width="100%">';
		$s .= $this->sysLinks();
		$s .= '</td></tr><tr><td class="top-subheader">';

		$s .= '<font size="-1"><span id="sitesub">';
		$s .= wfMessage( 'sitesubtitle' )->escaped() . '</span></font>';
		$s .= '</td><td class="top-linkcollection">';

		$s .= '<font size="-1"><span id="langlinks">';
		$s .= str_replace( '<br />', '', $this->otherLanguages() );

		$s .= $this->getSkin()->getCategories();

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

	/**
	 * @return string
	 */
	function doAfterContent(){
		$s = "\n</div><br clear='all' />\n";

		$s .= "\n<div id='footer'>";
		$s .= '<table width="98%" cellspacing="0"><tr>';

		$s .= '<td class="bottom">';

		$s .= $this->bottomLinks();
		$s .= $this->getSkin()->getLanguage()->pipeList( array(
			"\n<br />" . Linker::linkKnown(
				Title::newMainPage()
			),
			$this->getSkin()->aboutLink(),
			$this->searchForm( wfMessage( 'qbfind' )->text() )
		) );

		$s .= "\n<br />" . $this->pageStats();

		$s .= '</td>';
		$s .= "</tr></table>\n</div>\n</div>\n";

		if ( $this->getSkin()->qbSetting() != 0 ) {
			$s .= $this->quickBar();
		}
		return $s;
	}

	/**
	 * @return string
	 */
	function sysLinks() {
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
			Linker::linkKnown(
				Title::newFromText( wfMessage( 'aboutpage' )->inContentLanguage()->text() ),
				wfMessage( 'about' )->text()
			),
			Linker::linkKnown(
				Title::newFromText( wfMessage( 'helppage' )->inContentLanguage()->text() ),
				wfMessage( 'help' )->text()
			),
			Linker::linkKnown(
				Title::newFromText( wfMessage( 'faqpage' )->inContentLanguage()->text() ),
				wfMessage( 'faq' )->text()
			),
			Linker::specialLink( 'Specialpages' )
		);

		/* show links to different language variants */
		if( $this->variantLinks() ) {
			$s[] = $this->variantLinks();
		}
		if( $this->extensionTabLinks() ) {
			$s[] = $this->extensionTabLinks();
		}
		if ( $this->data['loggedin'] ) {
			$s[] = Linker::linkKnown(
				$lo,
				wfMessage( 'logout' )->text(),
				array(),
				$q
			);
		} else {
			$s[] = Linker::linkKnown(
				$li,
				wfMessage( 'login' )->text(),
				array(),
				$q
			);
		}

		return $this->getSkin()->getLanguage()->pipeList( $s );
	}

	/**
	 * Compute the sidebar
	 * @access private
	 *
	 * @return string
	 */
	function quickBar(){
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
			if ( $barnumber > 1 ) {
				$headingMsg = wfMessage( $heading );
				if ( $headingMsg->exists() ) {
					$h = $headingMsg->text();
				} else {
					$h = $heading;
				}
				$s .= "\n<h6>" . htmlspecialchars( $h ) . "</h6>";
			}
			if( is_array( $browseLinks ) ) {
				foreach ( $browseLinks as $link ) {
					if ( $link['text'] != '-' ) {
						$s .= "<a href=\"{$link['href']}\">" .
							htmlspecialchars( $link['text'] ) . '</a>' . $sep;
					}
				}
			}
			$barnumber++;
		}

		$user = $this->getSkin()->getUser();

		if ( $this->data['isarticle'] ) {
			$s .= $this->menuHead( 'qbedit' );
			$s .= '<strong>' . $this->editThisPage() . '</strong>';

			$s .= $sep . Linker::linkKnown(
				Title::newFromText( wfMessage( 'edithelppage' )->inContentLanguage()->text() ),
				wfMessage( 'edithelp' )->text()
			);

			if( $this->data['loggedin'] ) {
				$s .= $sep . $this->moveThisPage();
			}
			if ( $user->isAllowed( 'delete' ) ) {
				$dtp = $this->deleteThisPage();
				if ( $dtp != '' ) {
					$s .= $sep . $dtp;
				}
			}
			if ( $user->isAllowed( 'protect' ) ) {
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
			if ( $this->data['loggedin'] ) {
				$s .= $sep . $this->watchThisPage();
			}

			$s .= $sep;

			$s .= $this->menuHead( 'qbpageinfo' )
					. $this->historyLink()
					. $sep . $this->whatLinksHere()
					. $sep . $this->watchPageLinksLink();

			$title = $this->getSkin()->getTitle();
			$tns = $title->getNamespace();
			if ( $tns == NS_USER || $tns == NS_USER_TALK ) {
				$id = User::idFromName( $title->getText() );
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
		if ( $this->data['loggedin'] ) {
			$tl = Linker::linkKnown(
				$user->getTalkPage(),
				wfMessage( 'mytalk' )->escaped()
			);
			if ( $user->getNewtalk() ) {
				$tl .= ' *';
			}

			$s .= Linker::linkKnown(
					$user->getUserPage(),
					wfMessage( 'mypage' )->escaped()
				) . $sep . $tl . $sep . Linker::specialLink( 'Watchlist' )
					. $sep .
				Linker::linkKnown(
					SpecialPage::getSafeTitleFor( 'Contributions', $user->getName() ),
					wfMessage( 'mycontris' )->escaped()
				) . $sep . Linker::specialLink( 'Preferences' )
				. $sep . Linker::specialLink( 'Userlogout' );
		} else {
			$s .= Linker::specialLink( 'Userlogin' );
		}

		$s .= $this->menuHead( 'qbspecialpages' )
			. Linker::specialLink( 'Newpages' )
			. $sep . Linker::specialLink( 'Listfiles' )
			. $sep . Linker::specialLink( 'Statistics' );
		if( UploadBase::isEnabled() && UploadBase::isAllowed( $user ) === true ) {
			$s .= $sep . $this->getUploadLink();
		}

		global $wgSiteSupportPage;

		if( $wgSiteSupportPage ) {
			$s .= $sep . '<a href="' . htmlspecialchars( $wgSiteSupportPage ) . '" class="internal">'
					. wfMessage( 'sitesupport' )->escaped() . '</a>';
		}

		$s .= $sep . Linker::linkKnown(
			SpecialPage::getTitleFor( 'Specialpages' ),
			wfMessage( 'moredotdotdot' )->text()
		);

		$s .= $sep . "\n</div>\n";
		return $s;
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function menuHead( $key ) {
		$s = "\n<h6>" . wfMessage( $key )->text() . "</h6>";
		return $s;
	}

	/**
	 * @param $label string
	 * @return string
	 */
	function searchForm( $label = '' ) {
		global $wgUseTwoButtonsSearchForm;

		$search = $this->getSkin()->getRequest()->getText( 'search' );
		$action = $this->data['searchaction'];
		$s = "<form id=\"searchform{$this->searchboxes}\" method=\"get\" class=\"inline\" action=\"$action\">";
		if( $label != '' ) {
			$s .= "{$label}: ";
		}

		$s .= "<input type='text' id=\"searchInput{$this->searchboxes}\" class=\"mw-searchInput\" name=\"search\" size=\"14\" value=\""
			. htmlspecialchars( substr( $search, 0, 256 ) ) . "\" /><br />"
			. "<input type='submit' id=\"searchGoButton{$this->searchboxes}\" class=\"searchButton\" name=\"go\" value=\"" . wfMessage( 'searcharticle' )->escaped() . "\" />";

		if( $wgUseTwoButtonsSearchForm ) {
			$s .= "<input type='submit' id=\"mw-searchButton{$this->searchboxes}\" class=\"searchButton\" name=\"fulltext\" value=\"" . wfMessage( 'search' )->escaped() . "\" />\n";
		} else {
			$s .= '<div><a href="' . $action . '" rel="search">' . wfMessage( 'powersearch-legend' )->escaped() . "</a></div>\n";
		}

		$s .= '</form>';

		// Ensure unique id's for search boxes made after the first
		$this->searchboxes = $this->searchboxes == '' ? 2 : $this->searchboxes + 1;

		return $s;
	}
}
