<?php
/**
 * Base class for legacy skins.
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
 * @file
 */

class SkinLegacy extends SkinTemplate {
	var $useHeadElement = true;
	protected $mWatchLinkNum = 0; // Appended to end of watch link id's

	/**
	 * Add skin specific stylesheets
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		$out->addModuleStyles( 'mediawiki.legacy.shared' );
		$out->addModuleStyles( 'mediawiki.legacy.oldshared' );
	}

	public function commonPrintStylesheet() {
		return true;
	}

	/**
	 * This was for the old skins and for users with 640x480 screen.
	 * Please note old skins are still used and might prove useful for
	 * users having old computers or visually impaired.
	 */
	var $mSuppressQuickbar = false;

	/**
	 * Suppress the quickbar from the output, only for skin supporting
	 * the quickbar
	 */
	public function suppressQuickbar() {
		$this->mSuppressQuickbar = true;
	}

	/**
	 * Return whether the quickbar should be suppressed from the output
	 *
	 * @return Boolean
	 */
	public function isQuickbarSuppressed() {
		return $this->mSuppressQuickbar;
	}

	function qbSetting() {
		global $wgUser;
		if ( $this->isQuickbarSuppressed() ) {
			return 0;
		}
		$q = $wgUser->getOption( 'quickbar', 0 );
		if( $q == 5 ) {
			# 5 is the default, which chooses the setting
			# depending on the directionality of your interface language
			global $wgLang;
			return $wgLang->isRTL() ? 2 : 1;
		}
		return $q;
	}

}

class LegacyTemplate extends BaseTemplate {

	// How many search boxes have we made?  Avoid duplicate id's.
	protected $searchboxes = '';

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
	 * This will be called immediately after the "<body>" tag.  Split into
	 * two functions to make it easier to subclass.
	 * @return string
	 */
	function beforeContent() {
		return $this->doBeforeContent();
	}

	function doBeforeContent() {
		global $wgLang;
		wfProfileIn( __METHOD__ );

		$s = '';

		$langlinks = $this->otherLanguages();
		if ( $langlinks ) {
			$rows = 2;
			$borderhack = '';
		} else {
			$rows = 1;
			$langlinks = false;
			$borderhack = 'class="top"';
		}

		$s .= "\n<div id='content'>\n<div id='topbar'>\n" .
			"<table cellspacing='0' style='width: 100%;'>\n<tr>\n";

		if ( $this->getSkin()->qbSetting() == 0 ) {
			$s .= "<td class='top' style='text-align: left; vertical-align: top;' rowspan='{$rows}'>\n" .
				$this->getSkin()->logoText( $wgLang->alignStart() ) . '</td>';
		}

		$l = $wgLang->alignStart();
		$s .= "<td {$borderhack} style='text-align: $l; vertical-align: top;'>\n";

		$s .= $this->topLinks();
		$s .= '<p class="subtitle">' . $this->pageTitleLinks() . "</p>\n";

		$r = $wgLang->alignEnd();
		$s .= "</td>\n<td {$borderhack} style='text-align: $r; vertical-align: top;' nowrap='nowrap'>";
		$s .= $this->nameAndLogin();
		$s .= "\n<br />" . $this->searchForm() . '</td>';

		if ( $langlinks ) {
			$s .= "</tr>\n<tr>\n<td class='top' colspan=\"2\">$langlinks</td>\n";
		}

		$s .= "</tr>\n</table>\n</div>\n";
		$s .= "\n<div id='article'>\n";

		$notice = $this->getSkin()->getSiteNotice();

		if ( $notice ) {
			$s .= "\n<div id='siteNotice'>$notice</div>\n";
		}
		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle();
		$s .= $this->getSkin()->getCategories();

		wfProfileOut( __METHOD__ );
		return $s;
	}

	/**
	 * This gets called shortly before the "</body>" tag.
	 * @return String HTML to be put before "</body>"
	 */
	function afterContent() {
		return $this->doAfterContent();
	}

	/** overloaded by derived classes
	 * @return string
	 */
	function doAfterContent() {
		return '</div></div>';
	}

	function searchForm() {
		global $wgRequest, $wgUseTwoButtonsSearchForm;

		$search = $wgRequest->getText( 'search' );

		$s = '<form id="searchform' . $this->searchboxes . '" name="search" class="inline" method="post" action="'
			. $this->getSkin()->escapeSearchLink() . "\">\n"
			. '<input type="text" id="searchInput' . $this->searchboxes . '" name="search" size="19" value="'
			. htmlspecialchars( substr( $search, 0, 256 ) ) . "\" />\n"
			. '<input type="submit" name="go" value="' . wfMessage( 'searcharticle' )->text() . '" />';

		if ( $wgUseTwoButtonsSearchForm ) {
			$s .= '&#160;<input type="submit" name="fulltext" value="' . wfMessage( 'searchbutton' )->text() . "\" />\n";
		} else {
			$s .= ' <a href="' . $this->getSkin()->escapeSearchLink() . '" rel="search">' . wfMessage( 'powersearch-legend' )->text() . "</a>\n";
		}

		$s .= '</form>';

		// Ensure unique id's for search boxes made after the first
		$this->searchboxes = $this->searchboxes == '' ? 2 : $this->searchboxes + 1;

		return $s;
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

	function topLinks() {
		global $wgOut;

		$s = array(
			$this->getSkin()->mainPageLink(),
			Linker::specialLink( 'Recentchanges' )
		);

		if ( $wgOut->isArticleRelated() ) {
			$s[] = $this->editThisPage();
			$s[] = $this->historyLink();
		}

		# Many people don't like this dropdown box
		# $s[] = $this->specialPagesList();

		if ( $this->variantLinks() ) {
			$s[] = $this->variantLinks();
		}

		if ( $this->extensionTabLinks() ) {
			$s[] = $this->extensionTabLinks();
		}

		// @todo FIXME: Is using Language::pipeList impossible here? Do not quite understand the use of the newline
		return implode( $s, wfMessage( 'pipe-separator' )->escaped() . "\n" );
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

		if ( !$wgDisableLangConversion && count( $variants ) > 1
			&& !$title->isSpecialPage() ) {
			foreach ( $variants as $code ) {
				$varname = $lang->getVariantname( $code );

				if ( $varname == 'disable' ) {
					continue;
				}
				$s = $wgLang->pipeList( array(
					$s,
					'<a href="' . htmlspecialchars( $title->getLocalURL( 'variant=' . $code ) ) . '" lang="' . $code . '" hreflang="' . $code . '">' . htmlspecialchars( $varname ) . '</a>'
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

				if ( $wgUser->isAllowed( 'protect' ) && $title->getRestrictionTypes() ) {
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

	/**
	 * Show a drop-down box of special pages
	 * @return string
	 */
	function specialPagesList() {
		global $wgScript;

		$select = new XmlSelect( 'title' );
		$pages = SpecialPageFactory::getUsablePages();
		array_unshift( $pages, SpecialPageFactory::getPage( 'SpecialPages' ) );
		foreach ( $pages as $obj ) {
			$select->addOption( $obj->getDescription(),
				$obj->getTitle()->getPrefixedDBkey() );
		}

		return Html::rawElement( 'form',
			array( 'id' => 'specialpages', 'method' => 'get', 'action' => $wgScript ),
			$select->getHTML() . Xml::submitButton( wfMessage( 'go' )->text() ) );
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
	 * @deprecated in 1.19
	 * @return string
	 */
	function getQuickbarCompensator( $rows = 1 ) {
		wfDeprecated( __METHOD__, '1.19' );
		return "<td style='width: 152px;' rowspan='{$rows}'>&#160;</td>";
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

		if ( $title->getArticleID() && ( ! $diff ) && $wgUser->isAllowed( 'protect' ) && $title->getRestrictionTypes() ) {
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

		# __NEWSECTIONLINK___ changes behavior here
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

	function nameAndLogin() {
		global $wgUser, $wgLang, $wgRequest;

		$returnTo = $this->getSkin()->getTitle();
		$ret = '';

		if ( $wgUser->isAnon() ) {
			if ( $this->getSkin()->showIPinHeader() ) {
				$name = $wgRequest->getIP();

				$talkLink = Linker::link( $wgUser->getTalkPage(),
					$wgLang->getNsText( NS_TALK ) );
				$talkLink = wfMessage( 'parentheses' )->rawParams( $talkLink )->escaped();

				$ret .= "$name $talkLink";
			} else {
				$ret .= wfMessage( 'notloggedin' )->text();
			}

			$query = array();

			if ( !$returnTo->isSpecial( 'Userlogout' ) ) {
				$query['returnto'] = $returnTo->getPrefixedDBkey();
			}

			$loginlink = $wgUser->isAllowed( 'createaccount' )
				? 'nav-login-createaccount'
				: 'login';
			$ret .= "\n<br />" . Linker::link(
				SpecialPage::getTitleFor( 'Userlogin' ),
				wfMessage( $loginlink )->text(), array(), $query
			);
		} else {
			$talkLink = Linker::link( $wgUser->getTalkPage(),
				$wgLang->getNsText( NS_TALK ) );
			$talkLink = wfMessage( 'parentheses' )->rawParams( $talkLink )->escaped();

			$ret .= Linker::link( $wgUser->getUserPage(),
				htmlspecialchars( $wgUser->getName() ) );
			$ret .= " $talkLink<br />";
			$ret .= $wgLang->pipeList( array(
				Linker::link(
					SpecialPage::getTitleFor( 'Userlogout' ), wfMessage( 'logout' )->text(),
					array(), array( 'returnto' => $returnTo->getPrefixedDBkey() )
				),
				Linker::specialLink( 'Preferences' ),
			) );
		}

		$ret = $wgLang->pipeList( array(
			$ret,
			Linker::link(
				Title::newFromText( wfMessage( 'helppage' )->inContentLanguage()->text() ),
				wfMessage( 'help' )->text()
			),
		) );

		return $ret;
	}
}
