<?php
/**
 * @defgroup Skins Skins
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

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
	 * This will be called immediately after the <body> tag.  Split into
	 * two functions to make it easier to subclass.
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
		  "<table border='0' cellspacing='0' width='100%'>\n<tr>\n";

		if ( $this->getSkin()->qbSetting() == 0 ) {
			$s .= "<td class='top' align='left' valign='top' rowspan='{$rows}'>\n" .
				$this->getSkin()->logoText( $wgLang->alignStart() ) . '</td>';
		}

		$l = $wgLang->alignStart();
		$s .= "<td {$borderhack} align='$l' valign='top'>\n";

		$s .= $this->topLinks();
		$s .= '<p class="subtitle">' . $this->pageTitleLinks() . "</p>\n";

		$r = $wgLang->alignEnd();
		$s .= "</td>\n<td {$borderhack} valign='top' align='$r' nowrap='nowrap'>";
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
	 * This gets called shortly before the </body> tag.
	 * @return String HTML to be put before </body>
	 */
	function afterContent() {
		return $this->doAfterContent();
	}

	/** overloaded by derived classes */
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
		  . '<input type="submit" name="go" value="' . wfMsg( 'searcharticle' ) . '" />';

		if ( $wgUseTwoButtonsSearchForm ) {
			$s .= '&#160;<input type="submit" name="fulltext" value="' . wfMsg( 'searchbutton' ) . "\" />\n";
		} else {
			$s .= ' <a href="' . $this->getSkin()->escapeSearchLink() . '" rel="search">' . wfMsg( 'powersearch-legend' ) . "</a>\n";
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
		return implode( $s, wfMsgExt( 'pipe-separator', 'escapenoentities' ) . "\n" );
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
					'<a href="' . htmlspecialchars( $title->getLocalURL( 'variant=' . $code ) ) . '">' . htmlspecialchars( $varname ) . '</a>'
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

			$out = wfMsgExt( 'pipe-separator' , 'escapenoentities' );
			$out .= $wgLang->pipeList( $s );
		}

		return $out;
	}

	function bottomLinks() {
		global $wgOut, $wgUser;
		$sep = wfMsgExt( 'pipe-separator', 'escapenoentities' ) . "\n";

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

			if ( $title->getArticleId() ) {
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

	function otherLanguages() {
		global $wgOut, $wgLang, $wgContLang, $wgHideInterlanguageLinks;

		if ( $wgHideInterlanguageLinks ) {
			return '';
		}

		$a = $wgOut->getLanguageLinks();

		if ( 0 == count( $a ) ) {
			return '';
		}

		$s = wfMsg( 'otherlanguages' ) . wfMsg( 'colon-separator' );
		$first = true;

		if ( $wgLang->isRTL() ) {
			$s .= '<span dir="ltr">';
		}

		foreach ( $a as $l ) {
			if ( !$first ) {
				$s .= wfMsgExt( 'pipe-separator', 'escapenoentities' );
			}

			$first = false;

			$nt = Title::newFromText( $l );
			$text = $wgContLang->getLanguageName( $nt->getInterwiki() );

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

		return Html::rawElement( 'form', array( 'id' => 'specialpages', 'method' => 'get',
			'action' => $wgScript ), $select->getHTML() . Xml::submitButton( wfMsg( 'go' ) ) );
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
				$name = $title->getDBkey();
				$image = wfFindFile( $title );

				if ( $image ) {
					$link = htmlspecialchars( $image->getURL() );
					$style = Linker::getInternalLinkAttributes( $link, $name );
					$s[] = "<a href=\"{$link}\"{$style}>{$name}</a>";
				}
			}
		}

		if ( 'history' == $action || isset( $diff ) || isset( $oldid ) ) {
			$s[] .= Linker::linkKnown(
					$title,
					wfMsg( 'currentrev' )
			);
		}

		if ( $wgUser->getNewtalk() ) {
			# do not show "You have new messages" text when we are viewing our
			# own talk page
			if ( !$title->equals( $wgUser->getTalkPage() ) ) {
				$tl = Linker::linkKnown(
					$wgUser->getTalkPage(),
					wfMsgHtml( 'newmessageslink' ),
					array(),
					array( 'redirect' => 'no' )
				);

				$dl = Linker::linkKnown(
					$wgUser->getTalkPage(),
					wfMsgHtml( 'newmessagesdifflink' ),
					array(),
					array( 'diff' => 'cur' )
				);
				$s[] = '<strong>' . wfMsg( 'youhavenewmessages', $tl, $dl ) . '</strong>';
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
			$sub = wfMsgExt( 'tagline', 'parsemag' ) . $wgExtraSubtitle;
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
			$s[] = "<a href=\"$printurl\" rel=\"alternate\">" . wfMsg( 'printableversion' ) . '</a>';
		}

		if ( $wgOut->isSyndicated() ) {
			foreach ( $wgOut->getSyndicationLinks() as $format => $link ) {
				$feedurl = htmlspecialchars( $link );
				$s[] = "<a href=\"$feedurl\" rel=\"alternate\" type=\"application/{$format}+xml\""
						. " class=\"feedlink\">" . wfMsgHtml( "feed-$format" ) . "</a>";
			}
		}
		return $wgLang->pipeList( $s );
	}

	/**
	 * @deprecated in 1.19
	 */
	function getQuickbarCompensator( $rows = 1 ) {
		wfDeprecated( __METHOD__, '1.19' );
		return "<td width='152' rowspan='{$rows}'>&#160;</td>";
	}

	function editThisPage() {
		global $wgOut;

		if ( !$wgOut->isArticleRelated() ) {
			$s = wfMsg( 'protectedpage' );
		} else {
			$title = $this->getSkin()->getTitle();
			if ( $title->quickUserCan( 'edit' ) && $title->exists() ) {
				$t = wfMsg( 'editthispage' );
			} elseif ( $title->quickUserCan( 'create' ) && !$title->exists() ) {
				$t = wfMsg( 'create-this-page' );
			} else {
				$t = wfMsg( 'viewsource' );
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

		if ( $title->getArticleId() && ( !$diff ) && $wgUser->isAllowed( 'delete' ) ) {
			$t = wfMsg( 'deletethispage' );

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

		if ( $title->getArticleId() && ( ! $diff ) && $wgUser->isAllowed( 'protect' ) ) {
			if ( $title->isProtected() ) {
				$text = wfMsg( 'unprotectthispage' );
				$query = array( 'action' => 'unprotect' );
			} else {
				$text = wfMsg( 'protectthispage' );
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
			if ( $title->userIsWatching() ) {
				$text = wfMsg( 'unwatchthispage' );
				$query = array(
					'action' => 'unwatch',
					'token' => UnwatchAction::getUnwatchToken( $title, $wgUser ),
				);
				$id = 'mw-unwatch-link' . $this->mWatchLinkNum;
			} else {
				$text = wfMsg( 'watchthispage' );
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
			$s = wfMsg( 'notanarticle' );
		}

		return $s;
	}

	function moveThisPage() {
		if ( $this->getSkin()->getTitle()->quickUserCan( 'move' ) ) {
			return Linker::linkKnown(
				SpecialPage::getTitleFor( 'Movepage' ),
				wfMsg( 'movethispage' ),
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
			wfMsgHtml( 'history' ),
			array( 'rel' => 'archives' ),
			array( 'action' => 'history' )
		);
	}

	function whatLinksHere() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Whatlinkshere', $this->getSkin()->getTitle()->getPrefixedDBkey() ),
			wfMsgHtml( 'whatlinkshere' )
		);
	}

	function userContribsLink() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Contributions', $this->getSkin()->getTitle()->getDBkey() ),
			wfMsgHtml( 'contributions' )
		);
	}

	function emailUserLink() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Emailuser', $this->getSkin()->getTitle()->getDBkey() ),
			wfMsgHtml( 'emailuser' )
		);
	}

	function watchPageLinksLink() {
		global $wgOut;

		if ( !$wgOut->isArticleRelated() ) {
			return '(' . wfMsg( 'notanarticle' ) . ')';
		} else {
			return Linker::linkKnown(
				SpecialPage::getTitleFor( 'Recentchangeslinked', $this->getSkin()->getTitle()->getPrefixedDBkey() ),
				wfMsgHtml( 'recentchangeslinked-toolbox' )
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
					$text = wfMsg( 'articlepage' );
					break;
				case NS_USER:
					$text = wfMsg( 'userpage' );
					break;
				case NS_PROJECT:
					$text = wfMsg( 'projectpage' );
					break;
				case NS_FILE:
					$text = wfMsg( 'imagepage' );
					# Make link known if image exists, even if the desc. page doesn't.
					if ( wfFindFile( $link ) )
						$linkOptions[] = 'known';
					break;
				case NS_MEDIAWIKI:
					$text = wfMsg( 'mediawikipage' );
					break;
				case NS_TEMPLATE:
					$text = wfMsg( 'templatepage' );
					break;
				case NS_HELP:
					$text = wfMsg( 'viewhelppage' );
					break;
				case NS_CATEGORY:
					$text = wfMsg( 'categorypage' );
					break;
				default:
					$text = wfMsg( 'articlepage' );
			}
		} else {
			$link = $title->getTalkPage();
			$text = wfMsg( 'talkpage' );
		}

		$s = Linker::link( $link, $text, array(), array(), $linkOptions );

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
			wfMsg( 'postcomment' ),
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
			return Linker::makeExternalLink( $wgUploadNavigationUrl, wfMsgHtml( 'upload' ), false, null, array( 'class' => '' ) );
		} else {
			return Linker::linkKnown(
				SpecialPage::getTitleFor( 'Upload' ),
				wfMsgHtml( 'upload' )
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

				$ret .= "$name ($talkLink)";
			} else {
				$ret .= wfMsg( 'notloggedin' );
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
				wfMsg( $loginlink ), array(), $query
			);
		} else {
			$talkLink = Linker::link( $wgUser->getTalkPage(),
				$wgLang->getNsText( NS_TALK ) );

			$ret .= Linker::link( $wgUser->getUserPage(),
				htmlspecialchars( $wgUser->getName() ) );
			$ret .= " ($talkLink)<br />";
			$ret .= $wgLang->pipeList( array(
				Linker::link(
					SpecialPage::getTitleFor( 'Userlogout' ), wfMsg( 'logout' ),
					array(), array( 'returnto' => $returnTo->getPrefixedDBkey() )
				),
				Linker::specialLink( 'Preferences' ),
			) );
		}

		$ret = $wgLang->pipeList( array(
			$ret,
			Linker::link(
				Title::newFromText( wfMsgForContent( 'helppage' ) ),
				wfMsg( 'help' )
			),
		) );

		return $ret;
	}

}

