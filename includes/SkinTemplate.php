<?php
/**
 * Base class for template-based skins
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

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

/**
 * Wrapper object for MediaWiki's localization functions,
 * to be passed to the template engine.
 *
 * @private
 * @ingroup Skins
 */
class MediaWiki_I18N {
	var $_context = array();

	function set( $varName, $value ) {
		$this->_context[$varName] = $value;
	}

	function translate( $value ) {
		wfProfileIn( __METHOD__ );

		// Hack for i18n:attributes in PHPTAL 1.0.0 dev version as of 2004-10-23
		$value = preg_replace( '/^string:/', '', $value );

		$value = wfMsg( $value );
		// interpolate variables
		$m = array();
		while( preg_match( '/\$([0-9]*?)/sm', $value, $m ) ) {
			list( $src, $var ) = $m;
			wfSuppressWarnings();
			$varValue = $this->_context[$var];
			wfRestoreWarnings();
			$value = str_replace( $src, $varValue, $value );
		}
		wfProfileOut( __METHOD__ );
		return $value;
	}
}

/**
 * Template-filler skin base class
 * Formerly generic PHPTal (http://phptal.sourceforge.net/) skin
 * Based on Brion's smarty skin
 * @copyright Copyright © Gabriel Wicke -- http://www.aulinx.de/
 *
 * @todo Needs some serious refactoring into functions that correspond
 * to the computations individual esi snippets need. Most importantly no body
 * parsing for most of those of course.
 *
 * @ingroup Skins
 */
class SkinTemplate extends Skin {
	/**#@+
	 * @private
	 */

	/**
	 * Name of our skin, it probably needs to be all lower case.  Child classes
	 * should override the default.
	 */
	var $skinname = 'monobook';

	/**
	 * Stylesheets set to use.  Subdirectory in skins/ where various stylesheets
	 * are located.  Child classes should override the default.
	 */
	var $stylename = 'monobook';

	/**
	 * For QuickTemplate, the name of the subclass which will actually fill the
	 * template.  Child classes should override the default.
	 */
	var $template = 'QuickTemplate';

	/**
	 * Whether this skin use OutputPage::headElement() to generate the <head>
	 * tag
	 */
	var $useHeadElement = false;

	/**#@-*/

	/**
	 * Add specific styles for this skin
	 *
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ){
		$out->addModuleStyles( array( 'mediawiki.legacy.shared', 'mediawiki.legacy.commonPrint' ) );
	}

	/**
	 * Create the template engine object; we feed it a bunch of data
	 * and eventually it spits out some HTML. Should have interface
	 * roughly equivalent to PHPTAL 0.7.
	 *
	 * @param $classname string (or file)
	 * @param $repository string: subdirectory where we keep template files
	 * @param $cache_dir string
	 * @return object
	 * @private
	 */
	function setupTemplate( $classname, $repository = false, $cache_dir = false ) {
		return new $classname();
	}

	/**
	 * initialize various variables and generate the template
	 *
	 * @param $out OutputPage
	 */
	function outputPage( OutputPage $out ) {
		global $wgArticle, $wgUser, $wgLang, $wgContLang;
		global $wgScript, $wgStylePath, $wgLanguageCode;
		global $wgMimeType, $wgJsMimeType, $wgOutputEncoding, $wgRequest;
		global $wgXhtmlDefaultNamespace, $wgXhtmlNamespaces, $wgHtml5Version;
		global $wgDisableCounters, $wgLogo, $wgHideInterlanguageLinks;
		global $wgMaxCredits, $wgShowCreditsIfMax;
		global $wgPageShowWatchingUsers;
		global $wgUseTrackbacks, $wgUseSiteJs, $wgDebugComments;
		global $wgArticlePath, $wgScriptPath, $wgServer, $wgProfiler;

		wfProfileIn( __METHOD__ );
		if ( is_object( $wgProfiler ) ) {
			$wgProfiler->setTemplated( true );
		}

		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		$action = $wgRequest->getVal( 'action', 'view' );

		wfProfileIn( __METHOD__ . '-init' );
		$this->initPage( $out );

		$this->setMembers();
		$tpl = $this->setupTemplate( $this->template, 'skins' );

		#if ( $wgUseDatabaseMessages ) { // uncomment this to fall back to GetText
		$tpl->setTranslator( new MediaWiki_I18N() );
		#}
		wfProfileOut( __METHOD__ . '-init' );

		wfProfileIn( __METHOD__ . '-stuff' );
		$this->thispage = $this->mTitle->getPrefixedDBkey();
		$this->thisurl = $this->mTitle->getPrefixedURL();
		$query = array();
		if ( !$wgRequest->wasPosted() ) {
			$query = $wgRequest->getValues();
			unset( $query['title'] );
			unset( $query['returnto'] );
			unset( $query['returntoquery'] );
		}
		$this->thisquery = wfUrlencode( wfArrayToCGI( $query ) );
		$this->loggedin = $wgUser->isLoggedIn();
		$this->iscontent = ( $this->mTitle->getNamespace() != NS_SPECIAL );
		$this->iseditable = ( $this->iscontent and !( $action == 'edit' or $action == 'submit' ) );
		$this->username = $wgUser->getName();

		if ( $wgUser->isLoggedIn() || $this->showIPinHeader() ) {
			$this->userpageUrlDetails = self::makeUrlDetails( $this->userpage );
		} else {
			# This won't be used in the standard skins, but we define it to preserve the interface
			# To save time, we check for existence
			$this->userpageUrlDetails = self::makeKnownUrlDetails( $this->userpage );
		}

		$this->titletxt = $this->mTitle->getPrefixedText();
		wfProfileOut( __METHOD__ . '-stuff' );

		wfProfileIn( __METHOD__ . '-stuff-head' );
		if ( $this->useHeadElement ) {
			$pagecss = $this->setupPageCss();
			if( $pagecss )
				$out->addInlineStyle( $pagecss );
		} else {
			$this->setupUserCss( $out );

			$tpl->set( 'pagecss', $this->setupPageCss() );
			$tpl->setRef( 'usercss', $this->usercss );

			$this->userjs = $this->userjsprev = false;
			$this->setupUserJs( $out->isUserJsAllowed() );
			$tpl->setRef( 'userjs', $this->userjs );
			$tpl->setRef( 'userjsprev', $this->userjsprev );

			if( $wgUseSiteJs ) {
				$jsCache = $this->loggedin ? '&smaxage=0' : '';
				$tpl->set( 'jsvarurl',
						  self::makeUrl( '-',
										"action=raw$jsCache&gen=js&useskin=" .
										urlencode( $this->getSkinName() ) ) );
			} else {
				$tpl->set( 'jsvarurl', false );
			}

			$tpl->setRef( 'xhtmldefaultnamespace', $wgXhtmlDefaultNamespace );
			$tpl->set( 'xhtmlnamespaces', $wgXhtmlNamespaces );
			$tpl->set( 'html5version', $wgHtml5Version );
			$tpl->set( 'headlinks', $out->getHeadLinks( $this ) );
			$tpl->set( 'csslinks', $out->buildCssLinks() );

			if( $wgUseTrackbacks && $out->isArticleRelated() ) {
				$tpl->set( 'trackbackhtml', $out->getTitle()->trackbackRDF() );
			} else {
				$tpl->set( 'trackbackhtml', null );
			}
		}
		wfProfileOut( __METHOD__ . '-stuff-head' );

		wfProfileIn( __METHOD__ . '-stuff2' );
		$tpl->set( 'title', $out->getPageTitle() );
		$tpl->set( 'pagetitle', $out->getHTMLTitle() );
		$tpl->set( 'displaytitle', $out->mPageLinkTitle );
		$tpl->set( 'pageclass', $this->getPageClasses( $this->mTitle ) );
		$tpl->set( 'skinnameclass', ( 'skin-' . Sanitizer::escapeClass( $this->getSkinName() ) ) );

		$nsname = MWNamespace::exists( $this->mTitle->getNamespace() ) ?
					MWNamespace::getCanonicalName( $this->mTitle->getNamespace() ) :
					$this->mTitle->getNsText();

		$tpl->set( 'nscanonical', $nsname );
		$tpl->set( 'nsnumber', $this->mTitle->getNamespace() );
		$tpl->set( 'titleprefixeddbkey', $this->mTitle->getPrefixedDBKey() );
		$tpl->set( 'titletext', $this->mTitle->getText() );
		$tpl->set( 'articleid', $this->mTitle->getArticleId() );
		$tpl->set( 'currevisionid', isset( $wgArticle ) ? $wgArticle->getLatest() : 0 );

		$tpl->set( 'isarticle', $out->isArticle() );

		$tpl->setRef( 'thispage', $this->thispage );
		$subpagestr = $this->subPageSubtitle();
		$tpl->set(
			'subtitle', !empty( $subpagestr ) ?
			'<span class="subpages">' . $subpagestr . '</span>' . $out->getSubtitle() :
			$out->getSubtitle()
		);
		$undelete = $this->getUndeleteLink();
		$tpl->set(
			'undelete', !empty( $undelete ) ?
			'<span class="subpages">' . $undelete . '</span>' :
			''
		);

		$tpl->set( 'catlinks', $this->getCategories() );
		if( $out->isSyndicated() ) {
			$feeds = array();
			foreach( $out->getSyndicationLinks() as $format => $link ) {
				$feeds[$format] = array(
					'text' => wfMsg( "feed-$format" ),
					'href' => $link
				);
			}
			$tpl->setRef( 'feeds', $feeds );
		} else {
			$tpl->set( 'feeds', false );
		}

		$tpl->setRef( 'mimetype', $wgMimeType );
		$tpl->setRef( 'jsmimetype', $wgJsMimeType );
		$tpl->setRef( 'charset', $wgOutputEncoding );
		$tpl->setRef( 'wgScript', $wgScript );
		$tpl->setRef( 'skinname', $this->skinname );
		$tpl->set( 'skinclass', get_class( $this ) );
		$tpl->setRef( 'stylename', $this->stylename );
		$tpl->set( 'printable', $out->isPrintable() );
		$tpl->set( 'handheld', $wgRequest->getBool( 'handheld' ) );
		$tpl->setRef( 'loggedin', $this->loggedin );
		$tpl->set( 'notspecialpage', $this->mTitle->getNamespace() != NS_SPECIAL );
		/* XXX currently unused, might get useful later
		$tpl->set( 'editable', ( $this->mTitle->getNamespace() != NS_SPECIAL ) );
		$tpl->set( 'exists', $this->mTitle->getArticleID() != 0 );
		$tpl->set( 'watch', $this->mTitle->userIsWatching() ? 'unwatch' : 'watch' );
		$tpl->set( 'protect', count( $this->mTitle->isProtected() ) ? 'unprotect' : 'protect' );
		$tpl->set( 'helppage', wfMsg( 'helppage' ) );
		*/
		$tpl->set( 'searchaction', $this->escapeSearchLink() );
		$tpl->set( 'searchtitle', SpecialPage::getTitleFor( 'Search' )->getPrefixedDBKey() );
		$tpl->set( 'search', trim( $wgRequest->getVal( 'search' ) ) );
		$tpl->setRef( 'stylepath', $wgStylePath );
		$tpl->setRef( 'articlepath', $wgArticlePath );
		$tpl->setRef( 'scriptpath', $wgScriptPath );
		$tpl->setRef( 'serverurl', $wgServer );
		$tpl->setRef( 'logopath', $wgLogo );

		$lang = wfUILang();
		$tpl->set( 'lang', $lang->getCode() );
		$tpl->set( 'dir', $lang->getDir() );
		$tpl->set( 'rtl', $lang->isRTL() );

		$tpl->set( 'capitalizeallnouns', $wgLang->capitalizeAllNouns() ? ' capitalize-all-nouns' : '' );
		$tpl->set( 'showjumplinks', $wgUser->getOption( 'showjumplinks' ) );
		$tpl->set( 'username', $wgUser->isAnon() ? null : $this->username );
		$tpl->setRef( 'userpage', $this->userpage );
		$tpl->setRef( 'userpageurl', $this->userpageUrlDetails['href'] );
		$tpl->set( 'userlang', $wgLang->getCode() );

		// Users can have their language set differently than the
		// content of the wiki. For these users, tell the web browser
		// that interface elements are in a different language.
		$tpl->set( 'userlangattributes', '' );
		$tpl->set( 'specialpageattributes', '' );

		$lang = $wgLang->getCode();
		$dir  = $wgLang->getDir();
		if ( $lang !== $wgContLang->getCode() || $dir !== $wgContLang->getDir() ) {
			$attrs = " lang='$lang' dir='$dir'";

			$tpl->set( 'userlangattributes', $attrs );

			// The content of SpecialPages should be presented in the
			// user's language. Content of regular pages should not be touched.
			if( $this->mTitle->isSpecialPage() ) {
				$tpl->set( 'specialpageattributes', $attrs );
			}
		}

		$newtalks = $this->getNewtalks();

		wfProfileOut( __METHOD__ . '-stuff2' );

		wfProfileIn( __METHOD__ . '-stuff3' );
		$tpl->setRef( 'newtalk', $newtalks );
		$tpl->setRef( 'skin', $this );
		$tpl->set( 'logo', $this->logoText() );
		if ( $out->isArticle() && ( !isset( $oldid ) || isset( $diff ) ) &&
			$wgArticle && 0 != $wgArticle->getID() ){
			if ( !$wgDisableCounters ) {
				$viewcount = $wgLang->formatNum( $wgArticle->getCount() );
				if ( $viewcount ) {
					$tpl->set( 'viewcount', wfMsgExt( 'viewcount', array( 'parseinline' ), $viewcount ) );
				} else {
					$tpl->set( 'viewcount', false );
				}
			} else {
				$tpl->set( 'viewcount', false );
			}

			if( $wgPageShowWatchingUsers ) {
				$dbr = wfGetDB( DB_SLAVE );
				$res = $dbr->select( 'watchlist',
					array( 'COUNT(*) AS n' ),
					array( 'wl_title' => $dbr->strencode( $this->mTitle->getDBkey() ), 'wl_namespace' => $this->mTitle->getNamespace() ),
					__METHOD__
				);
				$x = $dbr->fetchObject( $res );
				$numberofwatchingusers = $x->n;
				if( $numberofwatchingusers > 0 ) {
					$tpl->set( 'numberofwatchingusers',
						wfMsgExt( 'number_of_watching_users_pageview', array( 'parseinline' ),
						$wgLang->formatNum( $numberofwatchingusers ) )
					);
				} else {
					$tpl->set( 'numberofwatchingusers', false );
				}
			} else {
				$tpl->set( 'numberofwatchingusers', false );
			}

			$tpl->set( 'copyright', $this->getCopyright() );

			$this->credits = false;

			if( $wgMaxCredits != 0 ){
				$this->credits = Credits::getCredits( $wgArticle, $wgMaxCredits, $wgShowCreditsIfMax );
			} else {
				$tpl->set( 'lastmod', $this->lastModified() );
			}

			$tpl->setRef( 'credits', $this->credits );

		} elseif ( isset( $oldid ) && !isset( $diff ) ) {
			$tpl->set( 'copyright', $this->getCopyright() );
			$tpl->set( 'viewcount', false );
			$tpl->set( 'lastmod', false );
			$tpl->set( 'credits', false );
			$tpl->set( 'numberofwatchingusers', false );
		} else {
			$tpl->set( 'copyright', false );
			$tpl->set( 'viewcount', false );
			$tpl->set( 'lastmod', false );
			$tpl->set( 'credits', false );
			$tpl->set( 'numberofwatchingusers', false );
		}
		wfProfileOut( __METHOD__ . '-stuff3' );

		wfProfileIn( __METHOD__ . '-stuff4' );
		$tpl->set( 'copyrightico', $this->getCopyrightIcon() );
		$tpl->set( 'poweredbyico', $this->getPoweredBy() );
		$tpl->set( 'disclaimer', $this->disclaimerLink() );
		$tpl->set( 'privacy', $this->privacyLink() );
		$tpl->set( 'about', $this->aboutLink() );

		$tpl->set( 'footerlinks', array(
			'info' => array(
				'lastmod',
				'viewcount',
				'numberofwatchingusers',
				'credits',
				'copyright',
			),
			'places' => array(
				'privacy',
				'about',
				'disclaimer',
			),
		) );

		global $wgFooterIcons;
		$tpl->set( 'footericons', $wgFooterIcons );
		foreach ( $tpl->data['footericons'] as $footerIconsKey => &$footerIconsBlock ) {
			if ( count( $footerIconsBlock ) > 0 ) {
				foreach ( $footerIconsBlock as &$footerIcon ) {
					if ( isset( $footerIcon['src'] ) ) {
						if ( !isset( $footerIcon['width'] ) ) {
							$footerIcon['width'] = 88;
						}
						if ( !isset( $footerIcon['height'] ) ) {
							$footerIcon['height'] = 31;
						}
					}
				}
			} else {
				unset( $tpl->data['footericons'][$footerIconsKey] );
			}
		}

		if ( $wgDebugComments ) {
			$tpl->setRef( 'debug', $out->mDebugtext );
		} else {
			$tpl->set( 'debug', '' );
		}

		$tpl->set( 'reporttime', wfReportTime() );
		$tpl->set( 'sitenotice', wfGetSiteNotice() );
		$tpl->set( 'bottomscripts', $this->bottomScripts( $out ) );

		$printfooter = "<div class=\"printfooter\">\n" . $this->printSource() . "</div>\n";
		global $wgBetterDirectionality;
		if ( $wgBetterDirectionality ) {
			$realBodyAttribs = array( 'lang' => $wgLanguageCode, 'dir' => $wgContLang->getDir() );
			$out->mBodytext = Html::rawElement( 'div', $realBodyAttribs, $out->mBodytext );
		}
		$out->mBodytext .= $printfooter . $this->generateDebugHTML();
		$tpl->setRef( 'bodytext', $out->mBodytext );

		# Language links
		$language_urls = array();

		if ( !$wgHideInterlanguageLinks ) {
			foreach( $out->getLanguageLinks() as $l ) {
				$tmp = explode( ':', $l, 2 );
				$class = 'interwiki-' . $tmp[0];
				unset( $tmp );
				$nt = Title::newFromText( $l );
				if ( $nt ) {
					$language_urls[] = array(
						'href' => $nt->getFullURL(),
						'text' => ( $wgContLang->getLanguageName( $nt->getInterwiki() ) != '' ?
									$wgContLang->getLanguageName( $nt->getInterwiki() ) : $l ),
						'title' => $nt->getText(),
						'class' => $class
					);
				}
			}
		}
		if( count( $language_urls ) ) {
			$tpl->setRef( 'language_urls', $language_urls );
		} else {
			$tpl->set( 'language_urls', false );
		}
		wfProfileOut( __METHOD__ . '-stuff4' );

		wfProfileIn( __METHOD__ . '-stuff5' );
		# Personal toolbar
		$tpl->set( 'personal_urls', $this->buildPersonalUrls() );
		$content_actions = $this->buildContentActionUrls();
		$tpl->setRef( 'content_actions', $content_actions );

		$tpl->set( 'sidebar', $this->buildSidebar() );
		$tpl->set( 'nav_urls', $this->buildNavUrls() );

		// Set the head scripts near the end, in case the above actions resulted in added scripts
		if ( $this->useHeadElement ) {
			$tpl->set( 'headelement', $out->headElement( $this ) );
		} else {
			$tpl->set( 'headscripts', $out->getScript() );
		}

		// original version by hansm
		if( !wfRunHooks( 'SkinTemplateOutputPageBeforeExec', array( &$this, &$tpl ) ) ) {
			wfDebug( __METHOD__ . ": Hook SkinTemplateOutputPageBeforeExec broke outputPage execution!\n" );
		}

		// allow extensions adding stuff after the page content.
		// See Skin::afterContentHook() for further documentation.
		$tpl->set( 'dataAfterContent', $this->afterContentHook() );
		wfProfileOut( __METHOD__ . '-stuff5' );

		// execute template
		wfProfileIn( __METHOD__ . '-execute' );
		$res = $tpl->execute();
		wfProfileOut( __METHOD__ . '-execute' );

		// result may be an error
		$this->printOrError( $res );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Output the string, or print error message if it's
	 * an error object of the appropriate type.
	 * For the base class, assume strings all around.
	 *
	 * @param $str Mixed
	 * @private
	 */
	function printOrError( $str ) {
		echo $str;
	}

	/**
	 * build array of urls for personal toolbar
	 * @return array
	 * @private
	 */
	function buildPersonalUrls() {
		global $wgOut, $wgRequest;

		$title = $wgOut->getTitle();
		$pageurl = $title->getLocalURL();
		wfProfileIn( __METHOD__ );

		/* set up the default links for the personal toolbar */
		$personal_urls = array();
		$page = $wgRequest->getVal( 'returnto', $this->thisurl );
		$query = $wgRequest->getVal( 'returntoquery', $this->thisquery );
		$returnto = "returnto=$page";
		if( $this->thisquery != '' ) {
			$returnto .= "&returntoquery=$query";
		}
		if( $this->loggedin ) {
			$personal_urls['userpage'] = array(
				'text' => $this->username,
				'href' => &$this->userpageUrlDetails['href'],
				'class' => $this->userpageUrlDetails['exists'] ? false : 'new',
				'active' => ( $this->userpageUrlDetails['href'] == $pageurl )
			);
			$usertalkUrlDetails = $this->makeTalkUrlDetails( $this->userpage );
			$personal_urls['mytalk'] = array(
				'text' => wfMsg( 'mytalk' ),
				'href' => &$usertalkUrlDetails['href'],
				'class' => $usertalkUrlDetails['exists'] ? false : 'new',
				'active' => ( $usertalkUrlDetails['href'] == $pageurl )
			);
			$href = self::makeSpecialUrl( 'Preferences' );
			$personal_urls['preferences'] = array(
				'text' => wfMsg( 'mypreferences' ),
				'href' => $href,
				'active' => ( $href == $pageurl )
			);
			$href = self::makeSpecialUrl( 'Watchlist' );
			$personal_urls['watchlist'] = array(
				'text' => wfMsg( 'mywatchlist' ),
				'href' => $href,
				'active' => ( $href == $pageurl )
			);

			# We need to do an explicit check for Special:Contributions, as we
			# have to match both the title, and the target (which could come
			# from request values or be specified in "sub page" form. The plot
			# thickens, because $wgTitle is altered for special pages, so doesn't
			# contain the original alias-with-subpage.
			$origTitle = Title::newFromText( $wgRequest->getText( 'title' ) );
			if( $origTitle instanceof Title && $origTitle->getNamespace() == NS_SPECIAL ) {
				list( $spName, $spPar ) =
					SpecialPage::resolveAliasWithSubpage( $origTitle->getText() );
				$active = $spName == 'Contributions'
					&& ( ( $spPar && $spPar == $this->username )
						|| $wgRequest->getText( 'target' ) == $this->username );
			} else {
				$active = false;
			}

			$href = self::makeSpecialUrlSubpage( 'Contributions', $this->username );
			$personal_urls['mycontris'] = array(
				'text' => wfMsg( 'mycontris' ),
				'href' => $href,
				'active' => $active
			);
			$personal_urls['logout'] = array(
				'text' => wfMsg( 'userlogout' ),
				'href' => self::makeSpecialUrl( 'Userlogout',
					$title->isSpecial( 'Preferences' ) ? '' : $returnto
				),
				'active' => false
			);
		} else {
			global $wgUser;
			$loginlink = $wgUser->isAllowed( 'createaccount' )
				? 'nav-login-createaccount'
				: 'login';

			# anonlogin & login are the same
			$login_url = array(
				'text' => wfMsg( $loginlink ),
				'href' => self::makeSpecialUrl( 'Userlogin', $returnto ),
				'active' => $title->isSpecial( 'Userlogin' )
			);
			global $wgProto, $wgSecureLogin;
			if( $wgProto === 'http' && $wgSecureLogin ) {
				$title = SpecialPage::getTitleFor( 'Userlogin' );
				$https_url = preg_replace( '/^http:/', 'https:', $title->getFullURL() );
				$login_url['href']  = $https_url;
				$login_url['class'] = 'link-https';  # FIXME class depends on skin
			}

			if( $this->showIPinHeader() ) {
				$href = &$this->userpageUrlDetails['href'];
				$personal_urls['anonuserpage'] = array(
					'text' => $this->username,
					'href' => $href,
					'class' => $this->userpageUrlDetails['exists'] ? false : 'new',
					'active' => ( $pageurl == $href )
				);
				$usertalkUrlDetails = $this->makeTalkUrlDetails( $this->userpage );
				$href = &$usertalkUrlDetails['href'];
				$personal_urls['anontalk'] = array(
					'text' => wfMsg( 'anontalk' ),
					'href' => $href,
					'class' => $usertalkUrlDetails['exists'] ? false : 'new',
					'active' => ( $pageurl == $href )
				);
				$personal_urls['anonlogin'] = $login_url;
			} else {
				$personal_urls['login'] = $login_url;
			}
		}

		wfRunHooks( 'PersonalUrls', array( &$personal_urls, &$title ) );
		wfProfileOut( __METHOD__ );
		return $personal_urls;
	}

	function tabAction( $title, $message, $selected, $query = '', $checkEdit = false ) {
		$classes = array();
		if( $selected ) {
			$classes[] = 'selected';
		}
		if( $checkEdit && !$title->isKnown() ) {
			$classes[] = 'new';
			$query = 'action=edit&redlink=1';
		}

		$text = wfMsg( $message );
		if ( wfEmptyMsg( $message, $text ) ) {
			global $wgContLang;
			$text = $wgContLang->getFormattedNsText( MWNamespace::getSubject( $title->getNamespace() ) );
		}

		$result = array();
		if( !wfRunHooks( 'SkinTemplateTabAction', array( &$this,
				$title, $message, $selected, $checkEdit,
				&$classes, &$query, &$text, &$result ) ) ) {
			return $result;
		}

		return array(
			'class' => implode( ' ', $classes ),
			'text' => $text,
			'href' => $title->getLocalUrl( $query ) );
	}

	function makeTalkUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		if( !is_object( $title ) ) {
			throw new MWException( __METHOD__ . " given invalid pagename $name" );
		}
		$title = $title->getTalkPage();
		self::checkTitle( $title, $name );
		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->getArticleID() != 0,
		);
	}

	function makeArticleUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		$title= $title->getSubjectPage();
		self::checkTitle( $title, $name );
		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->getArticleID() != 0,
		);
	}

	/**
	 * an array of edit links by default used for the tabs
	 * @return array
	 * @private
	 */
	function buildContentActionUrls() {
		global $wgContLang, $wgLang, $wgOut, $wgUser, $wgRequest, $wgArticle;

		wfProfileIn( __METHOD__ );

		$action = $wgRequest->getVal( 'action', 'view' );
		$section = $wgRequest->getVal( 'section' );
		$content_actions = array();
		$userCanRead = $this->mTitle->userCanRead();

		$prevent_active_tabs = false;
		wfRunHooks( 'SkinTemplatePreventOtherActiveTabs', array( &$this, &$prevent_active_tabs ) );

		if( $this->iscontent ) {
			$subjpage = $this->mTitle->getSubjectPage();
			$talkpage = $this->mTitle->getTalkPage();

			$nskey = $this->mTitle->getNamespaceKey();
			$content_actions[$nskey] = $this->tabAction(
				$subjpage,
				$nskey,
				!$this->mTitle->isTalkPage() && !$prevent_active_tabs,
				'', $userCanRead
			);

			$content_actions['talk'] = $this->tabAction(
				$talkpage,
				'talk',
				$this->mTitle->isTalkPage() && !$prevent_active_tabs,
				'',
				$userCanRead
			);

			wfProfileIn( __METHOD__ . '-edit' );
			if ( $userCanRead && $this->mTitle->quickUserCan( 'edit' ) && ( $this->mTitle->exists() || $this->mTitle->quickUserCan( 'create' ) ) ) {
				$istalk = $this->mTitle->isTalkPage();
				$istalkclass = $istalk?' istalk':'';
				$content_actions['edit'] = array(
					'class' => ( ( ( $action == 'edit' or $action == 'submit' ) and $section != 'new' ) ? 'selected' : '' ) . $istalkclass,
					'text' => ( $this->mTitle->exists() || ( $this->mTitle->getNamespace() == NS_MEDIAWIKI && !wfEmptyMsg( $this->mTitle->getText() ) ) )
						? wfMsg( 'edit' )
						: wfMsg( 'create' ),
					'href' => $this->mTitle->getLocalUrl( $this->editUrlOptions() )
				);

				// adds new section link if page is a current revision of a talk page or
				if ( ( $wgArticle && $wgArticle->isCurrent() && $istalk ) || $wgOut->showNewSectionLink() ) {
					if ( !$wgOut->forceHideNewSectionLink() ) {
						$content_actions['addsection'] = array(
							'class' => $section == 'new' ? 'selected' : false,
							'text' => wfMsg( 'addsection' ),
							'href' => $this->mTitle->getLocalUrl( 'action=edit&section=new' )
						);
					}
				}
			} elseif ( $this->mTitle->hasSourceText() && $userCanRead ) {
				$content_actions['viewsource'] = array(
					'class' => ($action == 'edit') ? 'selected' : false,
					'text' => wfMsg( 'viewsource' ),
					'href' => $this->mTitle->getLocalUrl( $this->editUrlOptions() )
				);
			}
			wfProfileOut( __METHOD__ . '-edit' );

			wfProfileIn( __METHOD__ . '-live' );
			if ( $this->mTitle->exists() && $userCanRead ) {

				$content_actions['history'] = array(
					'class' => ($action == 'history') ? 'selected' : false,
					'text' => wfMsg( 'history_short' ),
					'href' => $this->mTitle->getLocalUrl( 'action=history' ),
					'rel' => 'archives',
				);

				if( $wgUser->isAllowed( 'delete' ) ) {
					$content_actions['delete'] = array(
						'class' => ($action == 'delete') ? 'selected' : false,
						'text' => wfMsg( 'delete' ),
						'href' => $this->mTitle->getLocalUrl( 'action=delete' )
					);
				}
				if ( $this->mTitle->quickUserCan( 'move' ) ) {
					$moveTitle = SpecialPage::getTitleFor( 'Movepage', $this->thispage );
					$content_actions['move'] = array(
						'class' => $this->mTitle->isSpecial( 'Movepage' ) ? 'selected' : false,
						'text' => wfMsg( 'move' ),
						'href' => $moveTitle->getLocalUrl()
					);
				}

				if ( $this->mTitle->getNamespace() !== NS_MEDIAWIKI && $wgUser->isAllowed( 'protect' ) ) {
					if( !$this->mTitle->isProtected() ){
						$content_actions['protect'] = array(
							'class' => ($action == 'protect') ? 'selected' : false,
							'text' => wfMsg( 'protect' ),
							'href' => $this->mTitle->getLocalUrl( 'action=protect' )
						);

					} else {
						$content_actions['unprotect'] = array(
							'class' => ($action == 'unprotect') ? 'selected' : false,
							'text' => wfMsg( 'unprotect' ),
							'href' => $this->mTitle->getLocalUrl( 'action=unprotect' )
						);
					}
				}
			} else {
				//article doesn't exist or is deleted
				if( $wgUser->isAllowed( 'deletedhistory' ) && $wgUser->isAllowed( 'deletedtext' ) ) {
					$n = $this->mTitle->isDeleted();
					if( $n ) {
						$undelTitle = SpecialPage::getTitleFor( 'Undelete' );
						$content_actions['undelete'] = array(
							'class' => false,
							'text' => wfMsgExt( 'undelete_short', array( 'parsemag' ), $wgLang->formatNum( $n ) ),
							'href' => $undelTitle->getLocalUrl( 'target=' . urlencode( $this->thispage ) )
							#'href' => self::makeSpecialUrl( "Undelete/$this->thispage" )
						);
					}
				}

				if ( $this->mTitle->getNamespace() !== NS_MEDIAWIKI && $wgUser->isAllowed( 'protect' ) ) {
					if( !$this->mTitle->getRestrictions( 'create' ) ) {
						$content_actions['protect'] = array(
							'class' => ($action == 'protect') ? 'selected' : false,
							'text' => wfMsg( 'protect' ),
							'href' => $this->mTitle->getLocalUrl( 'action=protect' )
						);

					} else {
						$content_actions['unprotect'] = array(
							'class' => ($action == 'unprotect') ? 'selected' : false,
							'text' => wfMsg( 'unprotect' ),
							'href' => $this->mTitle->getLocalUrl( 'action=unprotect' )
						);
					}
				}
			}

			wfProfileOut( __METHOD__ . '-live' );

			if( $this->loggedin ) {
				if( !$this->mTitle->userIsWatching()) {
					$content_actions['watch'] = array(
						'class' => ($action == 'watch' or $action == 'unwatch') ? 'selected' : false,
						'text' => wfMsg( 'watch' ),
						'href' => $this->mTitle->getLocalUrl( 'action=watch' )
					);
				} else {
					$content_actions['unwatch'] = array(
						'class' => ($action == 'unwatch' or $action == 'watch') ? 'selected' : false,
						'text' => wfMsg( 'unwatch' ),
						'href' => $this->mTitle->getLocalUrl( 'action=unwatch' )
					);
				}
			}


			wfRunHooks( 'SkinTemplateTabs', array( $this, &$content_actions ) );
		} else {
			/* show special page tab */

			$content_actions[$this->mTitle->getNamespaceKey()] = array(
				'class' => 'selected',
				'text' => wfMsg('nstab-special'),
				'href' => $wgRequest->getRequestURL(), // @bug 2457, 2510
			);

			wfRunHooks( 'SkinTemplateBuildContentActionUrlsAfterSpecialPage', array( &$this, &$content_actions ) );
		}

		/* show links to different language variants */
		global $wgDisableLangConversion;
		$variants = $wgContLang->getVariants();
		if( !$wgDisableLangConversion && sizeof( $variants ) > 1 ) {
			$preferred = $wgContLang->getPreferredVariant();
			$vcount=0;
			foreach( $variants as $code ) {
				$varname = $wgContLang->getVariantname( $code );
				if( $varname == 'disable' )
					continue;
				$selected = ( $code == $preferred )? 'selected' : false;
				$content_actions['varlang-' . $vcount] = array(
					'class' => $selected,
					'text' => $varname,
					'href' => $this->mTitle->getLocalURL( '', $code )
				);
				$vcount ++;
			}
		}

		wfRunHooks( 'SkinTemplateContentActions', array( &$content_actions ) );

		wfProfileOut( __METHOD__ );
		return $content_actions;
	}

	/**
	 * build array of common navigation links
	 * @return array
	 * @private
	 */
	function buildNavUrls() {
		global $wgUseTrackbacks, $wgOut, $wgUser, $wgRequest;
		global $wgUploadNavigationUrl;

		wfProfileIn( __METHOD__ );

		$action = $wgRequest->getVal( 'action', 'view' );

		$nav_urls = array();
		$nav_urls['mainpage'] = array( 'href' => self::makeMainPageUrl() );
		if( $wgUploadNavigationUrl ) {
			$nav_urls['upload'] = array( 'href' => $wgUploadNavigationUrl );
		} elseif( UploadBase::isEnabled() && UploadBase::isAllowed( $wgUser ) === true ) {
			$nav_urls['upload'] = array( 'href' => self::makeSpecialUrl( 'Upload' ) );
		} else {
			$nav_urls['upload'] = false;
		}
		$nav_urls['specialpages'] = array( 'href' => self::makeSpecialUrl( 'Specialpages' ) );

		// default permalink to being off, will override it as required below.
		$nav_urls['permalink'] = false;

		// A print stylesheet is attached to all pages, but nobody ever
		// figures that out. :)  Add a link...
		if( $this->iscontent && ( $action == 'view' || $action == 'purge' ) ) {
			if ( !$wgOut->isPrintable() ) {
				$nav_urls['print'] = array(
					'text' => wfMsg( 'printableversion' ),
					'href' => $wgRequest->appendQuery( 'printable=yes' )
				);
			}

			// Also add a "permalink" while we're at it
			if ( $this->mRevisionId ) {
				$nav_urls['permalink'] = array(
					'text' => wfMsg( 'permalink' ),
					'href' => $wgOut->getTitle()->getLocalURL( "oldid=$this->mRevisionId" )
				);
			}

			// Copy in case this undocumented, shady hook tries to mess with internals
			$revid = $this->mRevisionId;
			wfRunHooks( 'SkinTemplateBuildNavUrlsNav_urlsAfterPermalink', array( &$this, &$nav_urls, &$revid, &$revid ) );
		}

		if( $this->mTitle->getNamespace() != NS_SPECIAL ) {
			$wlhTitle = SpecialPage::getTitleFor( 'Whatlinkshere', $this->thispage );
			$nav_urls['whatlinkshere'] = array(
				'href' => $wlhTitle->getLocalUrl()
			);
			if( $this->mTitle->getArticleId() ) {
				$rclTitle = SpecialPage::getTitleFor( 'Recentchangeslinked', $this->thispage );
				$nav_urls['recentchangeslinked'] = array(
					'href' => $rclTitle->getLocalUrl()
				);
			} else {
				$nav_urls['recentchangeslinked'] = false;
			}
			if( $wgUseTrackbacks )
				$nav_urls['trackbacklink'] = array(
					'href' => $wgOut->getTitle()->trackbackURL()
				);
		}

		if( $this->mTitle->getNamespace() == NS_USER || $this->mTitle->getNamespace() == NS_USER_TALK ) {
			$rootUser = strtok( $this->mTitle->getText(), '/' );
			$id = User::idFromName( $rootUser );
			$ip = User::isIP( $rootUser );
		} else {
			$id = 0;
			$ip = false;
		}

		if( $id || $ip ) { # both anons and non-anons have contribs list
			$nav_urls['contributions'] = array(
				'href' => self::makeSpecialUrlSubpage( 'Contributions', $rootUser )
			);

			if( $id ) {
				$logPage = SpecialPage::getTitleFor( 'Log' );
				$nav_urls['log'] = array(
					'href' => $logPage->getLocalUrl(
						array(
							'user' => $rootUser
						)
					)
				);
			} else {
				$nav_urls['log'] = false;
			}

			if ( $wgUser->isAllowed( 'block' ) ) {
				$nav_urls['blockip'] = array(
					'href' => self::makeSpecialUrlSubpage( 'Blockip', $rootUser )
				);
			} else {
				$nav_urls['blockip'] = false;
			}
		} else {
			$nav_urls['contributions'] = false;
			$nav_urls['log'] = false;
			$nav_urls['blockip'] = false;
		}
		$nav_urls['emailuser'] = false;
		if( $this->showEmailUser( $id ) ) {
			$nav_urls['emailuser'] = array(
				'href' => self::makeSpecialUrlSubpage( 'Emailuser', $rootUser )
			);
		}
		wfProfileOut( __METHOD__ );
		return $nav_urls;
	}

	/**
	 * Generate strings used for xml 'id' names
	 * @return string
	 * @private
	 */
	function getNameSpaceKey() {
		return $this->mTitle->getNamespaceKey();
	}

	/**
	 * @private
	 */
	function setupUserJs( $allowUserJs ) {
		global $wgRequest, $wgJsMimeType;
		wfProfileIn( __METHOD__ );

		$action = $wgRequest->getVal( 'action', 'view' );

		if( $allowUserJs && $this->loggedin ) {
			if( $this->mTitle->isJsSubpage() and $this->userCanPreview( $action ) ) {
				# XXX: additional security check/prompt?
				$this->userjsprev = '/*<![CDATA[*/ ' . $wgRequest->getText( 'wpTextbox1' ) . ' /*]]>*/';
			} else {
				$this->userjs = self::makeUrl( $this->userpage . '/' . $this->skinname . '.js', 'action=raw&ctype=' . $wgJsMimeType );
			}
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Code for extensions to hook into to provide per-page CSS, see
	 * extensions/PageCSS/PageCSS.php for an implementation of this.
	 *
	 * @private
	 */
	function setupPageCss() {
		wfProfileIn( __METHOD__ );
		$out = false;
		wfRunHooks( 'SkinTemplateSetupPageCss', array( &$out ) );
		wfProfileOut( __METHOD__ );
		return $out;
	}

	public function commonPrintStylesheet() {
		return false;
	}
}

/**
 * Generic wrapper for template functions, with interface
 * compatible with what we use of PHPTAL 0.7.
 * @ingroup Skins
 */
abstract class QuickTemplate {
	/**
	 * Constructor
	 */
	public function QuickTemplate() {
		$this->data = array();
		$this->translator = new MediaWiki_I18N();
	}

	/**
	 * Sets the value $value to $name
	 * @param $name
	 * @param $value
	 */
	public function set( $name, $value ) {
		$this->data[$name] = $value;
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public function setRef( $name, &$value ) {
		$this->data[$name] =& $value;
	}

	/**
	 * @param $t
	 */
	public function setTranslator( &$t ) {
		$this->translator = &$t;
	}

	/**
	 * Main function, used by classes that subclass QuickTemplate
	 * to show the actual HTML output
	 */
	abstract public function execute();

	/**
	 * @private
	 */
	function text( $str ) {
		echo htmlspecialchars( $this->data[$str] );
	}

	/**
	 * @private
	 */
	function jstext( $str ) {
		echo Xml::escapeJsString( $this->data[$str] );
	}

	/**
	 * @private
	 */
	function html( $str ) {
		echo $this->data[$str];
	}

	/**
	 * @private
	 */
	function msg( $str ) {
		echo htmlspecialchars( $this->translator->translate( $str ) );
	}

	/**
	 * @private
	 */
	function msgHtml( $str ) {
		echo $this->translator->translate( $str );
	}

	/**
	 * An ugly, ugly hack.
	 * @private
	 */
	function msgWiki( $str ) {
		global $wgParser, $wgOut;

		$text = $this->translator->translate( $str );
		$parserOutput = $wgParser->parse( $text, $wgOut->getTitle(),
			$wgOut->parserOptions(), true );
		echo $parserOutput->getText();
	}

	/**
	 * @private
	 */
	function haveData( $str ) {
		return isset( $this->data[$str] );
	}

	/**
	 * @private
	 */
	function haveMsg( $str ) {
		$msg = $this->translator->translate( $str );
		return ( $msg != '-' ) && ( $msg != '' ); # ????
	}
}

/**
 * New base template for a skin's template extended from QuickTemplate
 * this class features helper methods that provide common ways of interacting
 * with the data stored in the QuickTemplate
 */
abstract class BaseTemplate extends QuickTemplate {

	/**
	 * Create an array of common toolbox items from the data in the quicktemplate
	 * stored by SkinTemplate.
	 * The resulting array is built acording to a format intended to be passed
	 * through makeListItem to generate the html.
	 */
	function getToolbox() {
		wfProfileIn( __METHOD__ );

		$toolbox = array();
		if ( $this->data['notspecialpage'] ) {
			$toolbox['whatlinkshere'] = $this->data['nav_urls']['whatlinkshere'];
			$toolbox['whatlinkshere']['id'] = 't-whatlinkshere';
			if ( $this->data['nav_urls']['recentchangeslinked'] ) {
				$toolbox['recentchangeslinked'] = $this->data['nav_urls']['recentchangeslinked'];
				$toolbox['recentchangeslinked']['msg'] = 'recentchangeslinked-toolbox';
				$toolbox['recentchangeslinked']['id'] = 't-recentchangeslinked';
			}
		}
		if( isset( $this->data['nav_urls']['trackbacklink'] ) && $this->data['nav_urls']['trackbacklink'] ) {
			$toolbox['trackbacklink'] = $this->data['nav_urls']['trackbacklink'];
			$toolbox['trackbacklink']['id'] = 't-trackbacklink';
		}
		if ( $this->data['feeds'] ) {
			$toolbox['feeds']['id'] = 'feedlinks';
			$toolbox['feeds']['links'] = array();
			foreach ( $this->data['feeds'] as $key => $feed ) {
				$toolbox['feeds']['links'][$key] = $feed;
				$toolbox['feeds']['links'][$key]['id'] = "feed-$key";
				$toolbox['feeds']['links'][$key]['rel'] = 'alternate';
				$toolbox['feeds']['links'][$key]['type'] = "application/{$key}+xml";
				$toolbox['feeds']['links'][$key]['class'] = 'feedlink';
			}
		}
		foreach ( array( 'contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages' ) as $special ) {
			if ( $this->data['nav_urls'][$special] ) {
				$toolbox[$special] = $this->data['nav_urls'][$special];
				$toolbox[$special]['id'] = "t-$special";
			}
		}
		if ( !empty( $this->data['nav_urls']['print']['href'] ) ) {
			$toolbox['print'] = $this->data['nav_urls']['print'];
			$toolbox['print']['rel'] = 'alternate';
			$toolbox['print']['msg'] = 'printableversion';
		}
		if ( !empty( $this->data['nav_urls']['permalink']['href'] ) ) {
			$toolbox['permalink'] = $this->data['nav_urls']['permalink'];
			$toolbox['permalink']['id'] = 't-permalink';
		} elseif ( $this->data['nav_urls']['permalink']['href'] === '' ) {
			$toolbox['permalink'] = $this->data['nav_urls']['permalink'];
			unset( $toolbox['permalink']['href'] );
			$toolbox['ispermalink']['tooltiponly'] = true;
			$toolbox['ispermalink']['id'] = 't-ispermalink';
			$toolbox['ispermalink']['msg'] = 'permalink';
		}
		wfRunHooks( 'BaseTemplateToolbox', array( &$this, &$toolbox ) );
		wfProfileOut( __METHOD__ );
		return $toolbox;
	}

	/**
	 * Makes a link, usually used by makeListItem to generate a link for an item
	 * in a list used in navigation lists, portlets, portals, sidebars, etc...
	 *
	 * $key is a string, usually a key from the list you are generating this link from
	 * $item is an array containing some of a specific set of keys.
	 * The text of the link will be generated either from the contents of the "text"
	 * key in the $item array, if a "msg" key is present a message by that name will
	 * be used, and if neither of those are set the $key will be used as a message name.
	 * If a "href" key is not present makeLink will just output htmlescaped text.
	 * The href, id, class, rel, and type keys are used as attributes for the link if present.
	 * If an "id" or "single-id" (if you don't want the actual id to be output on the link)
	 * is present it will be used to generate a tooltip and accesskey for the link.
	 * If you don't want an accesskey, set $item['tooltiponly'] = true;
	 */
	function makeLink( $key, $item ) {
		if ( isset( $item['text'] ) ) {
			$text = $item['text'];
		} else {
			$text = $this->translator->translate( isset( $item['msg'] ) ? $item['msg'] : $key );
		}

		if ( !isset( $item['href'] ) ) {
			return htmlspecialchars( $text );
		}

		$attrs = array();
		foreach ( array( 'href', 'id', 'class', 'rel', 'type' ) as $attr ) {
			if ( isset( $item[$attr] ) ) {
				$attrs[$attr] = $item[$attr];
			}
		}

		if ( isset( $item['id'] ) ) {
			$item['single-id'] = $item['id'];
		}
		if ( isset( $item['single-id'] ) ) {
			if ( isset( $item['tooltiponly'] ) && $item['tooltiponly'] ) {
				$attrs['title'] = $this->skin->titleAttrib( $item['single-id'] );
				if ( $attrs['title'] === false ) {
					unset( $attrs['title'] );
				}
			} else {
				$attrs = array_merge(
					$attrs,
					$this->skin->tooltipAndAccesskeyAttribs( $item['single-id'] )
				);
			}
		}

		return Html::element( 'a', $attrs, $text );
	}

	/**
	 * Generates a list item for a navigation, portlet, portal, sidebar... etc list
	 * $key is a string, usually a key from the list you are generating this link from
	 * $item is an array of list item data containing some of a specific set of keys.
	 * The "id" and "class" keys will be used as attributes for the list item,
	 * if "active" contains a value of true a "active" class will also be appended to class.
	 * If you want something other than a <li> you can pass a tag name such as
	 * "tag" => "span" in the $options array to change the tag used.
	 * link/content data for the list item may come in one of two forms
	 * A "links" key may be used, in which case it should contain an array with
	 * a list of links to include inside the list item, see makeLink for the format
	 * of individual links array items.
	 * Otherwise the relevant keys from the list item $item array will be passed
	 * to makeLink instead. Note however that "id" and "class" are used by the
	 * list item directly so they will not be passed to makeLink
	 * (however the link will still support a tooltip and accesskey from it)
	 * If you need an id or class on a single link you should include a "links"
	 * array with just one link item inside of it.
	 */
	function makeListItem( $key, $item, $options = array() ) {
		if ( isset( $item['links'] ) ) {
			$html = '';
			foreach ( $item['links'] as $linkKey => $link ) {
				$html .= $this->makeLink( $linkKey, $link );
			}
		} else {
			$link = array();
			foreach ( array( 'text', 'msg', 'href', 'rel', 'type', 'tooltiponly' ) as $k ) {
				if ( isset( $item[$k] ) ) {
					$link[$k] = $item[$k];
				}
			}
			if ( isset( $item['id'] ) ) {
				// The id goes on the <li> not on the <a> for single links
				// but makeSidebarLink still needs to know what id to use when
				// generating tooltips and accesskeys.
				$link['single-id'] = $item['id'];
			}
			$html = $this->makeLink( $key, $link  );
		}

		$attrs = array();
		foreach ( array( 'id', 'class' ) as $attr ) {
			if ( isset( $item[$attr] ) ) {
				$attrs[$attr] = $item[$attr];
			}
		}
		if ( isset( $item['active'] ) && $item['active'] ) {
			$attrs['class'] .= ' active';
			$attrs['class'] = trim( $attrs['class'] );
		}
		return Html::rawElement( isset( $options['tag'] ) ? $options['tag'] : 'li', $attrs, $html );
	}

	function makeSearchInput( $attrs = array() ) {
		$realAttrs = array(
			'type' => 'search',
			'name' => 'search',
			'value' => isset( $this->data['search'] ) ? $this->data['search'] : '',
		);
		$realAttrs = array_merge( $realAttrs, $this->skin->tooltipAndAccesskeyAttribs( 'search' ), $attrs );
		return Html::element( 'input', $realAttrs );
	}

	function makeSearchButton( $mode, $attrs = array() ) {
		switch( $mode ) {
			case 'go':
			case 'fulltext':
				$realAttrs = array(
					'type' => 'submit',
					'name' => $mode,
					'value' => $this->translator->translate( $mode == 'go' ? 'searcharticle' : 'searchbutton' ),
				);
				$realAttrs = array_merge(
					$realAttrs,
					$this->skin->tooltipAndAccesskeyAttribs( "search-$mode" ),
					$attrs
				);
				return Html::element( 'input', $realAttrs );
			case 'image':
				$buttonAttrs = array(
					'type' => 'submit',
					'name' => 'button',
				);
				$buttonAttrs = array_merge(
					$buttonAttrs,
					$this->skin->tooltipAndAccesskeyAttribs( 'search-fulltext' ),
					$attrs
				);
				unset( $buttonAttrs['src'] );
				unset( $buttonAttrs['alt'] );
				$imgAttrs = array(
					'src' => $attrs['src'],
					'alt' => isset( $attrs['alt'] ) ? $attrs['alt'] : $this->translator->translate( 'searchbutton' ),
				);
				return Html::rawElement( 'button', $buttonAttrs, Html::element( 'img', $imgAttrs ) );
			default:
				throw new MWException( 'Unknown mode passed to BaseTemplate::makeSearchButton' );
		}
	}

	/**
	 * Returns an array of footerlinks trimmed down to only those footer links that
	 * are valid.
	 * If you pass "flat" as an option then the returned array will be a flat array
	 * of footer icons instead of a key/value array of footerlinks arrays broken
	 * up into categories.
	 */
	function getFooterLinks( $option = null ) {
		$footerlinks = $this->data['footerlinks'];

		// Reduce footer links down to only those which are being used
		$validFooterLinks = array();
		foreach( $footerlinks as $category => $links ) {
			$validFooterLinks[$category] = array();
			foreach( $links as $link ) {
				if( isset( $this->data[$link] ) && $this->data[$link] ) {
					$validFooterLinks[$category][] = $link;
				}
			}
			if ( count( $validFooterLinks[$category] ) <= 0 ) {
				unset( $validFooterLinks[$category] );
			}
		}

		if ( $option == 'flat' ) {
			// fold footerlinks into a single array using a bit of trickery
			$validFooterLinks = call_user_func_array(
				'array_merge',
				array_values( $validFooterLinks )
			);
		}

		return $validFooterLinks;
	}

	/**
	 * Returns an array of footer icons filtered down by options relevant to how
	 * the skin wishes to display them.
	 * If you pass "icononly" as the option all footer icons which do not have an
	 * image icon set will be filtered out.
	 * If you pass "nocopyright" then MediaWiki's copyright icon will not be included
	 * in the list of footer icons. This is mostly useful for skins which only
	 * display the text from footericons instead of the images and don't want a
	 * duplicate copyright statement because footerlinks already rendered one.
	 */
	function getFooterIcons( $option = null ) {
		// Generate additional footer icons
		$footericons = $this->data['footericons'];

		if ( $option == 'icononly' ) {
			// Unset any icons which don't have an image
			foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
				foreach ( $footerIconsBlock as $footerIconKey => $footerIcon ) {
					if ( !is_string( $footerIcon ) && !isset( $footerIcon['src'] ) ) {
						unset( $footerIconsBlock[$footerIconKey] );
					}
				}
			}
			// Redo removal of any empty blocks
			foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
				if ( count( $footerIconsBlock ) <= 0 ) {
					unset( $footericons[$footerIconsKey] );
				}
			}
		} elseif ( $option == 'nocopyright' ) {
			$footericons = $this->data['footericons'];
			unset( $footericons['copyright']['copyright'] );
			if ( count( $footericons['copyright'] ) <= 0 ) {
				unset( $footericons['copyright'] );
			}
		}

		return $footericons;
	}


}

