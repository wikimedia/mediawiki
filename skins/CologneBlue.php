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
class SkinCologneBlue extends SkinTemplate {
	var $skinname = 'cologneblue', $stylename = 'cologneblue',
		$template = 'CologneBlueTemplate';
	var $useHeadElement = true;

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ){
		$out->addModuleStyles( 'mediawiki.legacy.shared' );
		$out->addModuleStyles( 'mediawiki.legacy.oldshared' );
		$out->addModuleStyles( 'skins.cologneblue' );
	}

}

class CologneBlueTemplate extends BaseTemplate {
	protected $mWatchLinkNum = 0; // Appended to end of watch link id's
	
	function execute() {
		$this->html( 'headelement' );
		echo $this->beforeContent();
		$this->html( 'bodytext' );
		echo "\n";
		echo $this->afterContent();
		$this->html( 'dataAfterContent' );
		$this->printTrail();
		echo "\n</body></html>";
	}
	

	/**
	 * Language/charset variant links for classic-style skins
	 * @return string
	 */
	function variantLinks() {
		$s = '';

		/* show links to different language variants */
		global $wgDisableLangConversion, $wgLang;

		$title = $this->getSkin()->getTitle();
		$lang = $title->getPageLanguage();
		$variants = $lang->getVariants();

		if ( !$wgDisableLangConversion && sizeof( $variants ) > 1
			&& !$title->isSpecialPage() ) {
			foreach ( $variants as $code ) {
				$varname = $lang->getVariantname( $code );

				if ( $varname == 'disable' ) {
					continue;
				}
				$s = $wgLang->pipeList( array(
					$s,
					'<a href="' . htmlspecialchars( $title->getLocalURL( 'variant=' . $code ) ) . '" lang="' . $code . '" hreflang="' . $code .  '">' . htmlspecialchars( $varname ) . '</a>'
				) );
			}
		}

		return $s;
	}

	/**
	 * Compatibility for extensions adding functionality through tabs.
	 * Eventually these old skins should be replaced with SkinTemplate-based
	 * versions, sigh...
	 * @return string
	 * @todo Exterminate! ...that, and replace it with normal SkinTemplate stuff
	 */
	function extensionTabLinks() {
		$tabs = array();
		$out = '';
		$s = array();
		wfRunHooks( 'SkinTemplateTabs', array( $this->getSkin(), &$tabs ) );
		foreach ( $tabs as $tab ) {
			$s[] = Xml::element( 'a',
				array( 'href' => $tab['href'] ),
				$tab['text'] );
		}

		if ( count( $s ) ) {
			global $wgLang;

			$out = wfMessage( 'pipe-separator' )->escaped();
			$out .= $wgLang->pipeList( $s );
		}

		return $out;
	}
	
	function otherLanguages() {
		global $wgOut, $wgLang, $wgHideInterlanguageLinks;

		if ( $wgHideInterlanguageLinks ) {
			return '';
		}

		$a = $wgOut->getLanguageLinks();

		if ( 0 == count( $a ) ) {
			return '';
		}

		$s = wfMessage( 'otherlanguages' )->text() . wfMessage( 'colon-separator' )->text();
		$first = true;

		if ( $wgLang->isRTL() ) {
			$s .= '<span dir="ltr">';
		}

		foreach ( $a as $l ) {
			if ( !$first ) {
				$s .= wfMessage( 'pipe-separator' )->escaped();
			}

			$first = false;

			$nt = Title::newFromText( $l );
			$text = Language::fetchLanguageName( $nt->getInterwiki() );

			$s .= Html::element( 'a',
				array( 'href' => $nt->getFullURL(), 'title' => $nt->getText(), 'class' => "external" ),
				$text == '' ? $l : $text );
		}

		if ( $wgLang->isRTL() ) {
			$s .= '</span>';
		}

		return $s;
	}

	function pageTitleLinks() {
		global $wgOut, $wgUser, $wgRequest, $wgLang;

		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		$action = $wgRequest->getText( 'action' );

		$skin = $this->getSkin();
		$title = $skin->getTitle();

		$s[] = $this->printableLink();
		$disclaimer = $skin->disclaimerLink(); # may be empty

		if ( $disclaimer ) {
			$s[] = $disclaimer;
		}

		$privacy = $skin->privacyLink(); # may be empty too

		if ( $privacy ) {
			$s[] = $privacy;
		}

		if ( $wgOut->isArticleRelated() ) {
			if ( $title->getNamespace() == NS_FILE ) {
				$image = wfFindFile( $title );

				if ( $image ) {
					$href = $image->getURL();
					$s[] = Html::element( 'a', array( 'href' => $href,
						'title' => $href ), $title->getText() );

				}
			}
		}

		if ( 'history' == $action || isset( $diff ) || isset( $oldid ) ) {
			$s[] .= Linker::linkKnown(
				$title,
				wfMessage( 'currentrev' )->text()
			);
		}

		if ( $wgUser->getNewtalk() ) {
			# do not show "You have new messages" text when we are viewing our
			# own talk page
			if ( !$title->equals( $wgUser->getTalkPage() ) ) {
				$tl = Linker::linkKnown(
					$wgUser->getTalkPage(),
					wfMessage( 'newmessageslink' )->escaped(),
					array(),
					array( 'redirect' => 'no' )
				);

				$dl = Linker::linkKnown(
					$wgUser->getTalkPage(),
					wfMessage( 'newmessagesdifflink' )->escaped(),
					array(),
					array( 'diff' => 'cur' )
				);
				$s[] = '<strong>' . wfMessage( 'youhavenewmessages', $tl, $dl )->text() . '</strong>';
				# disable caching
				$wgOut->setSquidMaxage( 0 );
				$wgOut->enableClientCache( false );
			}
		}

		$undelete = $skin->getUndeleteLink();

		if ( !empty( $undelete ) ) {
			$s[] = $undelete;
		}

		return $wgLang->pipeList( $s );
	}

	function printableLink() {
		global $wgOut, $wgRequest, $wgLang;

		$s = array();

		if ( !$wgOut->isPrintable() ) {
			$printurl = htmlspecialchars( $this->getSkin()->getTitle()->getLocalUrl(
				$wgRequest->appendQueryValue( 'printable', 'yes', true ) ) );
			$s[] = "<a href=\"$printurl\" rel=\"alternate\">"
				. wfMessage( 'printableversion' )->text() . '</a>';
		}

		if ( $wgOut->isSyndicated() ) {
			foreach ( $wgOut->getSyndicationLinks() as $format => $link ) {
				$feedurl = htmlspecialchars( $link );
				$s[] = "<a href=\"$feedurl\" rel=\"alternate\" type=\"application/{$format}+xml\""
						. " class=\"feedlink\">" . wfMessage( "feed-$format" )->escaped() . "</a>";
			}
		}
		return $wgLang->pipeList( $s );
	}

	/**
	 * Gets the h1 element with the page title.
	 * @return string
	 */
	function pageTitle() {
		global $wgOut;
		$s = '<h1 class="pagetitle"><span dir="auto">' . $wgOut->getPageTitle() . '</span></h1>';
		return $s;
		}

	function pageSubtitle() {
		global $wgOut;

		$sub = $wgOut->getSubtitle();

		if ( $sub == '' ) {
			global $wgExtraSubtitle;
			$sub = wfMessage( 'tagline' )->parse() . $wgExtraSubtitle;
		}

		$subpages = $this->getSkin()->subPageSubtitle();
		$sub .= !empty( $subpages ) ? "</p><p class='subpages'>$subpages" : '';
		$s = "<p class='subtitle'>{$sub}</p>\n";

		return $s;
	}

	function bottomLinks() {
		global $wgOut, $wgUser;
		$sep = wfMessage( 'pipe-separator' )->escaped() . "\n";

		$s = '';
		if ( $wgOut->isArticleRelated() ) {
			$element[] = '<strong>' . $this->editThisPage() . '</strong>';

			if ( $wgUser->isLoggedIn() ) {
				$element[] = $this->watchThisPage();
			}

			$element[] = $this->talkLink();
			$element[] = $this->historyLink();
			$element[] = $this->whatLinksHere();
			$element[] = $this->watchPageLinksLink();

			$title = $this->getSkin()->getTitle();

			if (
				$title->getNamespace() == NS_USER ||
				$title->getNamespace() == NS_USER_TALK
			) {
				$id = User::idFromName( $title->getText() );
				$ip = User::isIP( $title->getText() );

				# Both anons and non-anons have contributions list
				if ( $id || $ip ) {
					$element[] = $this->userContribsLink();
				}

				if ( $this->getSkin()->showEmailUser( $id ) ) {
					$element[] = $this->emailUserLink();
				}
			}

			$s = implode( $element, $sep );

			if ( $title->getArticleID() ) {
				$s .= "\n<br />";

				// Delete/protect/move links for privileged users
				if ( $wgUser->isAllowed( 'delete' ) ) {
					$s .= $this->deleteThisPage();
				}

				if ( $wgUser->isAllowed( 'protect' ) ) {
					$s .= $sep . $this->protectThisPage();
				}

				if ( $wgUser->isAllowed( 'move' ) ) {
					$s .= $sep . $this->moveThisPage();
				}
			}

			$s .= "<br />\n" . $this->otherLanguages();
		}

		return $s;
	}

	function editThisPage() {
		global $wgOut;

		if ( !$wgOut->isArticleRelated() ) {
			$s = wfMessage( 'protectedpage' )->text();
		} else {
			$title = $this->getSkin()->getTitle();
			if ( $title->quickUserCan( 'edit' ) && $title->exists() ) {
				$t = wfMessage( 'editthispage' )->text();
			} elseif ( $title->quickUserCan( 'create' ) && !$title->exists() ) {
				$t = wfMessage( 'create-this-page' )->text();
			} else {
				$t = wfMessage( 'viewsource' )->text();
			}

			$s = Linker::linkKnown(
				$title,
				$t,
				array(),
				$this->getSkin()->editUrlOptions()
			);
		}

		return $s;
	}

	function deleteThisPage() {
		global $wgUser, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		$title = $this->getSkin()->getTitle();

		if ( $title->getArticleID() && ( !$diff ) && $wgUser->isAllowed( 'delete' ) ) {
			$t = wfMessage( 'deletethispage' )->text();

			$s = Linker::linkKnown(
				$title,
				$t,
				array(),
				array( 'action' => 'delete' )
			);
		} else {
			$s = '';
		}

		return $s;
	}

	function protectThisPage() {
		global $wgUser, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		$title = $this->getSkin()->getTitle();

		if ( $title->getArticleID() && ( ! $diff ) && $wgUser->isAllowed( 'protect' ) ) {
			if ( $title->isProtected() ) {
				$text = wfMessage( 'unprotectthispage' )->text();
				$query = array( 'action' => 'unprotect' );
			} else {
				$text = wfMessage( 'protectthispage' )->text();
				$query = array( 'action' => 'protect' );
			}

			$s = Linker::linkKnown(
				$title,
				$text,
				array(),
				$query
			);
		} else {
			$s = '';
		}

		return $s;
	}

	function watchThisPage() {
		global $wgOut, $wgUser;
		++$this->mWatchLinkNum;

		// Cache
		$title = $this->getSkin()->getTitle();

		if ( $wgOut->isArticleRelated() ) {
			if ( $wgUser->isWatched( $title ) ) {
				$text = wfMessage( 'unwatchthispage' )->text();
				$query = array(
					'action' => 'unwatch',
					'token' => UnwatchAction::getUnwatchToken( $title, $wgUser ),
				);
				$id = 'mw-unwatch-link' . $this->mWatchLinkNum;
			} else {
				$text = wfMessage( 'watchthispage' )->text();
				$query = array(
					'action' => 'watch',
					'token' => WatchAction::getWatchToken( $title, $wgUser ),
				);
				$id = 'mw-watch-link' . $this->mWatchLinkNum;
			}

			$s = Linker::linkKnown(
				$title,
				$text,
				array( 'id' => $id ),
				$query
			);
		} else {
			$s = wfMessage( 'notanarticle' )->text();
		}

		return $s;
	}

	function moveThisPage() {
		if ( $this->getSkin()->getTitle()->quickUserCan( 'move' ) ) {
			return Linker::linkKnown(
				SpecialPage::getTitleFor( 'Movepage' ),
				wfMessage( 'movethispage' )->text(),
				array(),
				array( 'target' => $this->getSkin()->getTitle()->getPrefixedDBkey() )
			);
		} else {
			// no message if page is protected - would be redundant
			return '';
		}
	}

	function historyLink() {
		return Linker::link(
			$this->getSkin()->getTitle(),
			wfMessage( 'history' )->escaped(),
			array( 'rel' => 'archives' ),
			array( 'action' => 'history' )
		);
	}

	function whatLinksHere() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Whatlinkshere', $this->getSkin()->getTitle()->getPrefixedDBkey() ),
			wfMessage( 'whatlinkshere' )->escaped()
		);
	}

	function userContribsLink() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Contributions', $this->getSkin()->getTitle()->getDBkey() ),
			wfMessage( 'contributions' )->escaped()
		);
	}

	function emailUserLink() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Emailuser', $this->getSkin()->getTitle()->getDBkey() ),
			wfMessage( 'emailuser' )->escaped()
		);
	}

	function watchPageLinksLink() {
		global $wgOut;

		if ( !$wgOut->isArticleRelated() ) {
			return wfMessage( 'parentheses', wfMessage( 'notanarticle' )->text() )->escaped();
		} else {
			return Linker::linkKnown(
				SpecialPage::getTitleFor( 'Recentchangeslinked', $this->getSkin()->getTitle()->getPrefixedDBkey() ),
				wfMessage( 'recentchangeslinked-toolbox' )->escaped()
			);
		}
	}

	function talkLink() {
		$title = $this->getSkin()->getTitle();
		if ( NS_SPECIAL == $title->getNamespace() ) {
			# No discussion links for special pages
			return '';
		}

		$linkOptions = array();

		if ( $title->isTalkPage() ) {
			$link = $title->getSubjectPage();
			switch( $link->getNamespace() ) {
				case NS_MAIN:
					$text = wfMessage( 'articlepage' );
					break;
				case NS_USER:
					$text = wfMessage( 'userpage' );
					break;
				case NS_PROJECT:
					$text = wfMessage( 'projectpage' );
					break;
				case NS_FILE:
					$text = wfMessage( 'imagepage' );
					# Make link known if image exists, even if the desc. page doesn't.
					if ( wfFindFile( $link ) )
						$linkOptions[] = 'known';
					break;
				case NS_MEDIAWIKI:
					$text = wfMessage( 'mediawikipage' );
					break;
				case NS_TEMPLATE:
					$text = wfMessage( 'templatepage' );
					break;
				case NS_HELP:
					$text = wfMessage( 'viewhelppage' );
					break;
				case NS_CATEGORY:
					$text = wfMessage( 'categorypage' );
					break;
				default:
					$text = wfMessage( 'articlepage' );
			}
		} else {
			$link = $title->getTalkPage();
			$text = wfMessage( 'talkpage' );
		}

		$s = Linker::link( $link, $text->text(), array(), array(), $linkOptions );

		return $s;
	}

	function commentLink() {
		global $wgOut;

		$title = $this->getSkin()->getTitle();
		if ( $title->isSpecialPage() ) {
			return '';
		}

		# __NEWSECTIONLINK___ changes behaviour here
		# If it is present, the link points to this page, otherwise
		# it points to the talk page
		if ( !$title->isTalkPage() && !$wgOut->showNewSectionLink() ) {
			$title = $title->getTalkPage();
		}

		return Linker::linkKnown(
			$title,
			wfMessage( 'postcomment' )->text(),
			array(),
			array(
				'action' => 'edit',
				'section' => 'new'
			)
		);
	}

	function getUploadLink() {
		global $wgUploadNavigationUrl;

		if ( $wgUploadNavigationUrl ) {
			# Using an empty class attribute to avoid automatic setting of "external" class
			return Linker::makeExternalLink( $wgUploadNavigationUrl,
				wfMessage( 'upload' )->escaped(),
				false, null, array( 'class' => '' ) );
		} else {
			return Linker::linkKnown(
				SpecialPage::getTitleFor( 'Upload' ),
				wfMessage( 'upload' )->escaped()
			);
		}
	}

	function pageStats() {
		$ret = array();
		$items = array( 'viewcount', 'credits', 'lastmod', 'numberofwatchingusers', 'copyright' );

		foreach( $items as $item ) {
			if ( $this->data[$item] !== false ) {
				$ret[] = $this->data[$item];
			}
		}

		return implode( ' ', $ret );
	}




	
	/**
	 * @return string
	 */
	function beforeContent() {
		$mainPageObj = Title::newMainPage();

		$s = "\n<div id='content'>\n<div id='topbar'>" .
		  '<table style="width: 100%;" cellspacing="0" cellpadding="8"><tr>';

		$s .= '<td class="top" nowrap="nowrap">';
		$s .= '<a href="' . htmlspecialchars( $mainPageObj->getLocalURL() ) . '">';
		$s .= '<span id="sitetitle">' . wfMessage( 'sitetitle' )->escaped() . '</span></a>';

		$s .= '</td><td class="top" id="top-syslinks" style="width: 100%;">';
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
	function afterContent(){
		$s = "\n</div><br clear='all' />\n";

		$s .= "\n<div id='footer'>";
		$s .= '<table style="width: 98%;" cellspacing="0"><tr>';

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

		$s .= $this->quickBar();
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
