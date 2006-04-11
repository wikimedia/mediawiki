<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die( -1 );

# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * Template-filler skin base class
 * Formerly generic PHPTal (http://phptal.sourceforge.net/) skin
 * Based on Brion's smarty skin
 * Copyright (C) Gabriel Wicke -- http://www.aulinx.de/
 *
 * Todo: Needs some serious refactoring into functions that correspond
 * to the computations individual esi snippets need. Most importantly no body
 * parsing for most of those of course.
 *
 * @package MediaWiki
 * @subpackage Skins
 */

require_once 'GlobalFunctions.php';

/**
 * Wrapper object for MediaWiki's localization functions,
 * to be passed to the template engine.
 *
 * @access private
 * @package MediaWiki
 */
class MediaWiki_I18N {
	var $_context = array();

	function set($varName, $value) {
		$this->_context[$varName] = $value;
	}

	function translate($value) {
		$fname = 'SkinTemplate-translate';
		wfProfileIn( $fname );

		// Hack for i18n:attributes in PHPTAL 1.0.0 dev version as of 2004-10-23
		$value = preg_replace( '/^string:/', '', $value );

		$value = wfMsg( $value );
		// interpolate variables
		while (preg_match('/\$([0-9]*?)/sm', $value, $m)) {
			list($src, $var) = $m;
			wfSuppressWarnings();
			$varValue = $this->_context[$var];
			wfRestoreWarnings();
			$value = str_replace($src, $varValue, $value);
		}
		wfProfileOut( $fname );
		return $value;
	}
}

/**
 *
 * @package MediaWiki
 */
class SkinTemplate extends Skin {
	/**#@+
	 * @access private
	 */

	/**
	 * Name of our skin, set in initPage()
	 * It probably need to be all lower case.
	 */
	var $skinname;

	/**
	 * Stylesheets set to use
	 * Sub directory in ./skins/ where various stylesheets are located
	 */
	var $stylename;

	/**
	 * For QuickTemplate, the name of the subclass which
	 * will actually fill the template.
	 */
	var $template;

	/**#@-*/

	/**
	 * Setup the base parameters...
	 * Child classes should override this to set the name,
	 * style subdirectory, and template filler callback.
	 *
	 * @param OutputPage $out
	 */
	function initPage( &$out ) {
		parent::initPage( $out );
		$this->skinname  = 'monobook';
		$this->stylename = 'monobook';
		$this->template  = 'QuickTemplate';
	}

	/**
	 * Create the template engine object; we feed it a bunch of data
	 * and eventually it spits out some HTML. Should have interface
	 * roughly equivalent to PHPTAL 0.7.
	 *
	 * @param string $callback (or file)
	 * @param string $repository subdirectory where we keep template files
	 * @param string $cache_dir
	 * @return object
	 * @access private
	 */
	function setupTemplate( $classname, $repository=false, $cache_dir=false ) {
		return new $classname();
	}

	/**
	 * initialize various variables and generate the template
	 *
	 * @param OutputPage $out
	 * @access public
	 */
	function outputPage( &$out ) {
		global $wgTitle, $wgArticle, $wgUser, $wgLang, $wgContLang, $wgOut;
		global $wgScript, $wgStylePath, $wgContLanguageCode;
		global $wgMimeType, $wgJsMimeType, $wgOutputEncoding, $wgRequest;
		global $wgDisableCounters, $wgLogo, $action, $wgFeedClasses, $wgHideInterlanguageLinks;
		global $wgMaxCredits, $wgShowCreditsIfMax;
		global $wgPageShowWatchingUsers;
		global $wgUseTrackbacks;
		global $wgDBname;

		$fname = 'SkinTemplate::outputPage';
		wfProfileIn( $fname );

		extract( $wgRequest->getValues( 'oldid', 'diff' ) );

		wfProfileIn( "$fname-init" );
		$this->initPage( $out );

		$this->mTitle =& $wgTitle;
		$this->mUser =& $wgUser;

		$tpl = $this->setupTemplate( $this->template, 'skins' );

		#if ( $wgUseDatabaseMessages ) { // uncomment this to fall back to GetText
		$tpl->setTranslator(new MediaWiki_I18N());
		#}
		wfProfileOut( "$fname-init" );

		wfProfileIn( "$fname-stuff" );
		$this->thispage = $this->mTitle->getPrefixedDbKey();
		$this->thisurl = $this->mTitle->getPrefixedURL();
		$this->loggedin = $wgUser->isLoggedIn();
		$this->iscontent = ($this->mTitle->getNamespace() != NS_SPECIAL );
		$this->iseditable = ($this->iscontent and !($action == 'edit' or $action == 'submit'));
		$this->username = $wgUser->getName();
		$userPage = $wgUser->getUserPage();
		$this->userpage = $userPage->getPrefixedText();

		if ( $wgUser->isLoggedIn() || $this->showIPinHeader() ) {
			$this->userpageUrlDetails = $this->makeUrlDetails($this->userpage);
		} else {
			# This won't be used in the standard skins, but we define it to preserve the interface
			# To save time, we check for existence
			$this->userpageUrlDetails = $this->makeKnownUrlDetails($this->userpage);
		}

		$this->usercss =  $this->userjs = $this->userjsprev = false;
		$this->setupUserCss();
		$this->setupUserJs();
		$this->titletxt = $this->mTitle->getPrefixedText();
		wfProfileOut( "$fname-stuff" );

		wfProfileIn( "$fname-stuff2" );
		$tpl->set( 'title', $wgOut->getPageTitle() );
		$tpl->set( 'pagetitle', $wgOut->getHTMLTitle() );
		$tpl->set( 'displaytitle', $wgOut->mPageLinkTitle );

		$tpl->setRef( "thispage", $this->thispage );
		$subpagestr = $this->subPageSubtitle();
		$tpl->set(
			'subtitle',  !empty($subpagestr)?
			'<span class="subpages">'.$subpagestr.'</span>'.$out->getSubtitle():
			$out->getSubtitle()
		);
		$undelete = $this->getUndeleteLink();
		$tpl->set(
			"undelete", !empty($undelete)?
			'<span class="subpages">'.$undelete.'</span>':
			''
		);

		$tpl->set( 'catlinks', $this->getCategories());
		if( $wgOut->isSyndicated() ) {
			$feeds = array();
			foreach( $wgFeedClasses as $format => $class ) {
				$feeds[$format] = array(
					'text' => $format,
					'href' => $wgRequest->appendQuery( "feed=$format" )
				);
			}
			$tpl->setRef( 'feeds', $feeds );
		} else {
			$tpl->set( 'feeds', false );
		}
		if ($wgUseTrackbacks && $out->isArticleRelated())
			$tpl->set( 'trackbackhtml', $wgTitle->trackbackRDF());

		$tpl->setRef( 'mimetype', $wgMimeType );
		$tpl->setRef( 'jsmimetype', $wgJsMimeType );
		$tpl->setRef( 'charset', $wgOutputEncoding );
		$tpl->set( 'headlinks', $out->getHeadLinks() );
		$tpl->set('headscripts', $out->getScript() );
		$tpl->setRef( 'wgScript', $wgScript );
		$tpl->setRef( 'skinname', $this->skinname );
		$tpl->setRef( 'stylename', $this->stylename );
		$tpl->set( 'printable', $wgRequest->getBool( 'printable' ) );
		$tpl->setRef( 'loggedin', $this->loggedin );
		$tpl->set('nsclass', 'ns-'.$this->mTitle->getNamespace());
		$tpl->set('notspecialpage', $this->mTitle->getNamespace() != NS_SPECIAL);
		/* XXX currently unused, might get useful later
		$tpl->set( "editable", ($this->mTitle->getNamespace() != NS_SPECIAL ) );
		$tpl->set( "exists", $this->mTitle->getArticleID() != 0 );
		$tpl->set( "watch", $this->mTitle->userIsWatching() ? "unwatch" : "watch" );
		$tpl->set( "protect", count($this->mTitle->isProtected()) ? "unprotect" : "protect" );
		$tpl->set( "helppage", wfMsg('helppage'));
		*/
		$tpl->set( 'searchaction', $this->escapeSearchLink() );
		$tpl->set( 'search', trim( $wgRequest->getVal( 'search' ) ) );
		$tpl->setRef( 'stylepath', $wgStylePath );
		$tpl->setRef( 'logopath', $wgLogo );
		$tpl->setRef( "lang", $wgContLanguageCode );
		$tpl->set( 'dir', $wgContLang->isRTL() ? "rtl" : "ltr" );
		$tpl->set( 'rtl', $wgContLang->isRTL() );
		$tpl->set( 'langname', $wgContLang->getLanguageName( $wgContLanguageCode ) );
		$tpl->set( 'showjumplinks', $wgUser->getOption( 'showjumplinks' ) );
		$tpl->setRef( 'username', $this->username );
		$tpl->setRef( 'userpage', $this->userpage);
		$tpl->setRef( 'userpageurl', $this->userpageUrlDetails['href']);
		$tpl->set( 'pagecss', $this->setupPageCss() );
		$tpl->setRef( 'usercss', $this->usercss);
		$tpl->setRef( 'userjs', $this->userjs);
		$tpl->setRef( 'userjsprev', $this->userjsprev);
		global $wgUseSiteJs;
		if ($wgUseSiteJs) {
			if($this->loggedin) {
				$tpl->set( 'jsvarurl', $this->makeUrl('-','action=raw&smaxage=0&gen=js') );
			} else {
				$tpl->set( 'jsvarurl', $this->makeUrl('-','action=raw&gen=js') );
			}
		} else {
			$tpl->set('jsvarurl', false);
		}
		$newtalks = $wgUser->getNewMessageLinks();

		if (count($newtalks) == 1 && $newtalks[0]["wiki"] === $wgDBname) {
			$usertitle = $this->mUser->getUserPage();
			$usertalktitle = $usertitle->getTalkPage();
			if( !$usertalktitle->equals( $this->mTitle ) ) {
				$ntl = wfMsg( 'youhavenewmessages',
					$this->makeKnownLinkObj(
						$usertalktitle,
						wfMsgHtml( 'newmessageslink' ),
						'redirect=no'
					),
					$this->makeKnownLinkObj(
						$usertalktitle,
						wfMsgHtml( 'newmessagesdifflink' ),
						'diff=cur'
					)
				);
				# Disable Cache
				$wgOut->setSquidMaxage(0);
			}
		} else if (count($newtalks)) {
			$sep = str_replace("_", " ", wfMsgHtml("newtalkseperator"));
			$msgs = array();
			foreach ($newtalks as $newtalk) {
				$msgs[] = wfElement("a",
					array('href' => $newtalk["link"]), $newtalk["wiki"]);
			}
			$parts = implode($sep, $msgs);
			$ntl = wfMsgHtml('youhavenewmessagesmulti', $parts);
			$wgOut->setSquidMaxage(0);
		} else {
			$ntl = '';
		}
		wfProfileOut( "$fname-stuff2" );

		wfProfileIn( "$fname-stuff3" );
		$tpl->setRef( 'newtalk', $ntl );
		$tpl->setRef( 'skin', $this);
		$tpl->set( 'logo', $this->logoText() );
		if ( $wgOut->isArticle() and (!isset( $oldid ) or isset( $diff )) and 0 != $wgArticle->getID() ) {
			if ( !$wgDisableCounters ) {
				$viewcount = $wgLang->formatNum( $wgArticle->getCount() );
				if ( $viewcount ) {
					$tpl->set('viewcount', wfMsg( "viewcount", $viewcount ));
				} else {
					$tpl->set('viewcount', false);
				}
			} else {
				$tpl->set('viewcount', false);
			}

			if ($wgPageShowWatchingUsers) {
				$dbr =& wfGetDB( DB_SLAVE );
				extract( $dbr->tableNames( 'watchlist' ) );
				$sql = "SELECT COUNT(*) AS n FROM $watchlist
					WHERE wl_title='" . $dbr->strencode($this->mTitle->getDBKey()) .
					"' AND  wl_namespace=" . $this->mTitle->getNamespace() ;
				$res = $dbr->query( $sql, 'SkinTemplate::outputPage');
				$x = $dbr->fetchObject( $res );
				$numberofwatchingusers = $x->n;
				if ($numberofwatchingusers > 0) {
					$tpl->set('numberofwatchingusers', wfMsg('number_of_watching_users_pageview', $numberofwatchingusers));
				} else {
					$tpl->set('numberofwatchingusers', false);
				}
			} else {
				$tpl->set('numberofwatchingusers', false);
			}

			$tpl->set('copyright',$this->getCopyright());

			$this->credits = false;

			if (isset($wgMaxCredits) && $wgMaxCredits != 0) {
				require_once("Credits.php");
				$this->credits = getCredits($wgArticle, $wgMaxCredits, $wgShowCreditsIfMax);
			} else {
				$tpl->set('lastmod', $this->lastModified());
			}

			$tpl->setRef( 'credits', $this->credits );

		} elseif ( isset( $oldid ) && !isset( $diff ) ) {
			$tpl->set('copyright', $this->getCopyright());
			$tpl->set('viewcount', false);
			$tpl->set('lastmod', false);
			$tpl->set('credits', false);
			$tpl->set('numberofwatchingusers', false);
		} else {
			$tpl->set('copyright', false);
			$tpl->set('viewcount', false);
			$tpl->set('lastmod', false);
			$tpl->set('credits', false);
			$tpl->set('numberofwatchingusers', false);
		}
		wfProfileOut( "$fname-stuff3" );

		wfProfileIn( "$fname-stuff4" );
		$tpl->set( 'copyrightico', $this->getCopyrightIcon() );
		$tpl->set( 'poweredbyico', $this->getPoweredBy() );
		$tpl->set( 'disclaimer', $this->disclaimerLink() );
		$tpl->set( 'privacy', $this->privacyLink() );
		$tpl->set( 'about', $this->aboutLink() );

		$tpl->setRef( 'debug', $out->mDebugtext );
		$tpl->set( 'reporttime', $out->reportTime() );
		$tpl->set( 'sitenotice', wfGetSiteNotice() );

		$printfooter = "<div class=\"printfooter\">\n" . $this->printSource() . "</div>\n";
		$out->mBodytext .= $printfooter ;
		$tpl->setRef( 'bodytext', $out->mBodytext );

		# Language links
		$language_urls = array();

		if ( !$wgHideInterlanguageLinks ) {
			foreach( $wgOut->getLanguageLinks() as $l ) {
				$tmp = explode( ':', $l, 2 );
				$class = 'interwiki-' . $tmp[0];
				unset($tmp);
				$nt = Title::newFromText( $l );
				$language_urls[] = array(
					'href' => $nt->getFullURL(),
					'text' => ($wgContLang->getLanguageName( $nt->getInterwiki()) != ''?$wgContLang->getLanguageName( $nt->getInterwiki()) : $l),
					'class' => $class
				);
			}
		}
		if(count($language_urls)) {
			$tpl->setRef( 'language_urls', $language_urls);
		} else {
			$tpl->set('language_urls', false);
		}
		wfProfileOut( "$fname-stuff4" );

		# Personal toolbar
		$tpl->set('personal_urls', $this->buildPersonalUrls());
		$content_actions = $this->buildContentActionUrls();
		$tpl->setRef('content_actions', $content_actions);

		// XXX: attach this from javascript, same with section editing
		if($this->iseditable &&	$wgUser->getOption("editondblclick") )
		{
			$tpl->set('body_ondblclick', 'document.location = "' .$content_actions['edit']['href'] .'";');
		} else {
			$tpl->set('body_ondblclick', false);
		}
		if( $this->iseditable && $wgUser->getOption( 'editsectiononrightclick' ) ) {
			$tpl->set( 'body_onload', 'setupRightClickEdit()' );
		} else {
			$tpl->set( 'body_onload', false );
		}
		$tpl->set( 'sidebar', $this->buildSidebar() );
		$tpl->set( 'nav_urls', $this->buildNavUrls() );

		// execute template
		wfProfileIn( "$fname-execute" );
		$res = $tpl->execute();
		wfProfileOut( "$fname-execute" );

		// result may be an error
		$this->printOrError( $res );
		wfProfileOut( $fname );
	}

	/**
	 * Output the string, or print error message if it's
	 * an error object of the appropriate type.
	 * For the base class, assume strings all around.
	 *
	 * @param mixed $str
	 * @access private
	 */
	function printOrError( &$str ) {
		echo $str;
	}

	/**
	 * build array of urls for personal toolbar
	 * @return array
	 * @access private
	 */
	function buildPersonalUrls() {
		global $wgTitle, $wgShowIPinHeader;

		$fname = 'SkinTemplate::buildPersonalUrls';
		$pageurl = $wgTitle->getLocalURL();
		wfProfileIn( $fname );

		/* set up the default links for the personal toolbar */
		$personal_urls = array();
		if ($this->loggedin) {
			$personal_urls['userpage'] = array(
				'text' => $this->username,
				'href' => &$this->userpageUrlDetails['href'],
				'class' => $this->userpageUrlDetails['exists']?false:'new',
				'active' => ( $this->userpageUrlDetails['href'] == $pageurl )
			);
			$usertalkUrlDetails = $this->makeTalkUrlDetails($this->userpage);
			$personal_urls['mytalk'] = array(
				'text' => wfMsg('mytalk'),
				'href' => &$usertalkUrlDetails['href'],
				'class' => $usertalkUrlDetails['exists']?false:'new',
				'active' => ( $usertalkUrlDetails['href'] == $pageurl )
			);
			$href = $this->makeSpecialUrl('Preferences');
			$personal_urls['preferences'] = array(
				'text' => wfMsg('preferences'),
				'href' => $this->makeSpecialUrl('Preferences'),
				'active' => ( $href == $pageurl )
			);
			$href = $this->makeSpecialUrl('Watchlist');
			$personal_urls['watchlist'] = array(
				'text' => wfMsg('watchlist'),
				'href' => $href,
				'active' => ( $href == $pageurl )
			);
			$href = $this->makeSpecialUrl("Contributions/$this->username");
			$personal_urls['mycontris'] = array(
				'text' => wfMsg('mycontris'),
				'href' => $href
				# FIXME #  'active' => ( $href == $pageurl . '/' . $this->username )
			);
			$personal_urls['logout'] = array(
				'text' => wfMsg('userlogout'),
				'href' => $this->makeSpecialUrl( 'Userlogout',
					$wgTitle->getNamespace() === NS_SPECIAL && $wgTitle->getText() === 'Preferences' ? '' : "returnto={$this->thisurl}"
				)
			);
		} else {
			if( $wgShowIPinHeader && isset(  $_COOKIE[ini_get("session.name")] ) ) {
				$href = &$this->userpageUrlDetails['href'];
				$personal_urls['anonuserpage'] = array(
					'text' => $this->username,
					'href' => $href,
					'class' => $this->userpageUrlDetails['exists']?false:'new',
					'active' => ( $pageurl == $href )
				);
				$usertalkUrlDetails = $this->makeTalkUrlDetails($this->userpage);
				$href = &$usertalkUrlDetails['href'];
				$personal_urls['anontalk'] = array(
					'text' => wfMsg('anontalk'),
					'href' => $href,
					'class' => $usertalkUrlDetails['exists']?false:'new',
					'active' => ( $pageurl == $href )
				);
				$personal_urls['anonlogin'] = array(
					'text' => wfMsg('userlogin'),
					'href' => $this->makeSpecialUrl('Userlogin', 'returnto=' . $this->thisurl ),
					'active' => ( NS_SPECIAL == $wgTitle->getNamespace() && 'Userlogin' == $wgTitle->getDBkey() )
				);
			} else {

				$personal_urls['login'] = array(
					'text' => wfMsg('userlogin'),
					'href' => $this->makeSpecialUrl('Userlogin', 'returnto=' . $this->thisurl ),
					'active' => ( NS_SPECIAL == $wgTitle->getNamespace() && 'Userlogin' == $wgTitle->getDBkey() )
				);
			}
		}
		wfProfileOut( $fname );
		return $personal_urls;
	}

	/**
	 * Returns true if the IP should be shown in the header
	 */
	function showIPinHeader() {
		global $wgShowIPinHeader;
		return $wgShowIPinHeader && isset(  $_COOKIE[ini_get("session.name")] );
	}

	function tabAction( $title, $message, $selected, $query='', $checkEdit=false ) {
		$classes = array();
		if( $selected ) {
			$classes[] = 'selected';
		}
		if( $checkEdit && $title->getArticleId() == 0 ) {
			$classes[] = 'new';
			$query = 'action=edit';
		}

		$text = wfMsg( $message );
		if ( $text == "&lt;$message&gt;" ) {
			global $wgContLang;
			$text = $wgContLang->getNsText( Namespace::getSubject( $title->getNamespace() ) );
		}

		return array(
			'class' => implode( ' ', $classes ),
			'text' => $text,
			'href' => $title->getLocalUrl( $query ) );
	}

	function makeTalkUrlDetails( $name, $urlaction='' ) {
		$title = Title::newFromText( $name );
		$title = $title->getTalkPage();
		$this->checkTitle($title, $name);
		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->getArticleID() != 0?true:false
		);
	}

	function makeArticleUrlDetails( $name, $urlaction='' ) {
		$title = Title::newFromText( $name );
		$title= $title->getSubjectPage();
		$this->checkTitle($title, $name);
		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->getArticleID() != 0?true:false
		);
	}

	/**
	 * an array of edit links by default used for the tabs
	 * @return array
	 * @access private
	 */
	function buildContentActionUrls () {
		global $wgContLang;
		$fname = 'SkinTemplate::buildContentActionUrls';
		wfProfileIn( $fname );

		global $wgUser, $wgRequest;
		$action = $wgRequest->getText( 'action' );
		$section = $wgRequest->getText( 'section' );
		$content_actions = array();

		$prevent_active_tabs = false ;
		wfRunHooks( 'SkinTemplatePreventOtherActiveTabs', array( &$this , &$prevent_active_tabs ) )	;

		if( $this->iscontent ) {
			$subjpage = $this->mTitle->getSubjectPage();
			$talkpage = $this->mTitle->getTalkPage();

			$nskey = $this->mTitle->getNamespaceKey();
			$content_actions[$nskey] = $this->tabAction(
				$subjpage,
				$nskey,
				!$this->mTitle->isTalkPage() && !$prevent_active_tabs,
				'', true);

			$content_actions['talk'] = $this->tabAction(
				$talkpage,
				'talk',
				$this->mTitle->isTalkPage() && !$prevent_active_tabs,
				'',
				true);

			wfProfileIn( "$fname-edit" );
			if ( $this->mTitle->userCanEdit() ) {
				$istalk = $this->mTitle->isTalkPage();
				$istalkclass = $istalk?' istalk':'';
				$content_actions['edit'] = array(
					'class' => ((($action == 'edit' or $action == 'submit') and $section != 'new') ? 'selected' : '').$istalkclass,
					'text' => wfMsg('edit'),
					'href' => $this->mTitle->getLocalUrl( $this->editUrlOptions() )
				);

				if ( $istalk ) {
					$content_actions['addsection'] = array(
						'class' => $section == 'new'?'selected':false,
						'text' => wfMsg('addsection'),
						'href' => $this->mTitle->getLocalUrl( 'action=edit&section=new' )
					);
				}
			} else {
				$content_actions['viewsource'] = array(
					'class' => ($action == 'edit') ? 'selected' : false,
					'text' => wfMsg('viewsource'),
					'href' => $this->mTitle->getLocalUrl( $this->editUrlOptions() )
				);
			}
			wfProfileOut( "$fname-edit" );

			wfProfileIn( "$fname-live" );
			if ( $this->mTitle->getArticleId() ) {

				$content_actions['history'] = array(
					'class' => ($action == 'history') ? 'selected' : false,
					'text' => wfMsg('history_short'),
					'href' => $this->mTitle->getLocalUrl( 'action=history')
				);

				if ( $this->mTitle->getNamespace() !== NS_MEDIAWIKI && $wgUser->isAllowed( 'protect' ) ) {
					if(!$this->mTitle->isProtected()){
						$content_actions['protect'] = array(
							'class' => ($action == 'protect') ? 'selected' : false,
							'text' => wfMsg('protect'),
							'href' => $this->mTitle->getLocalUrl( 'action=protect' )
						);

					} else {
						$content_actions['unprotect'] = array(
							'class' => ($action == 'unprotect') ? 'selected' : false,
							'text' => wfMsg('unprotect'),
							'href' => $this->mTitle->getLocalUrl( 'action=unprotect' )
						);
					}
				}
				if($wgUser->isAllowed('delete')){
					$content_actions['delete'] = array(
						'class' => ($action == 'delete') ? 'selected' : false,
						'text' => wfMsg('delete'),
						'href' => $this->mTitle->getLocalUrl( 'action=delete' )
					);
				}
				if ( $this->mTitle->userCanMove()) {
					$content_actions['move'] = array(
						'class' => ($this->mTitle->getDbKey() == 'Movepage' and $this->mTitle->getNamespace == NS_SPECIAL) ? 'selected' : false,
						'text' => wfMsg('move'),
						'href' => $this->makeSpecialUrl("Movepage/$this->thispage" )
					);
				}
			} else {
				//article doesn't exist or is deleted
				if($wgUser->isAllowed('delete')){
					if( $n = $this->mTitle->isDeleted() ) {
						$content_actions['undelete'] = array(
							'class' => false,
							'text' => ($n == 1) ? wfMsg( 'undelete_short1' ) : wfMsg('undelete_short', $n ),
							'href' => $this->makeSpecialUrl("Undelete/$this->thispage")
						);
					}
				}
			}
			wfProfileOut( "$fname-live" );

			if( $this->loggedin ) {
				if( !$this->mTitle->userIsWatching()) {
					$content_actions['watch'] = array(
						'class' => ($action == 'watch' or $action == 'unwatch') ? 'selected' : false,
						'text' => wfMsg('watch'),
						'href' => $this->mTitle->getLocalUrl( 'action=watch' )
					);
				} else {
					$content_actions['unwatch'] = array(
						'class' => ($action == 'unwatch' or $action == 'watch') ? 'selected' : false,
						'text' => wfMsg('unwatch'),
						'href' => $this->mTitle->getLocalUrl( 'action=unwatch' )
					);
				}
			}

			wfRunHooks( 'SkinTemplateTabs', array( &$this , &$content_actions ) )	;
		} else {
			/* show special page tab */

			$content_actions['article'] = array(
				'class' => 'selected',
				'text' => wfMsg('specialpage'),
				'href' => $wgRequest->getRequestURL(), // @bug 2457, 2510
			);

			wfRunHooks( 'SkinTemplateBuildContentActionUrlsAfterSpecialPage', array( &$this, &$content_actions ) );
		}

		/* show links to different language variants */
		global $wgDisableLangConversion;
		$variants = $wgContLang->getVariants();
		if( !$wgDisableLangConversion && sizeof( $variants ) > 1 ) {
			$preferred = $wgContLang->getPreferredVariant();
			$actstr = '';
			if( $action )
				$actstr = 'action=' . $action . '&';
			$vcount=0;
			foreach( $variants as $code ) {
				$varname = $wgContLang->getVariantname( $code );
				if( $varname == 'disable' )
					continue;
				$selected = ( $code == $preferred )? 'selected' : false;
				$content_actions['varlang-' . $vcount] = array(
						'class' => $selected,
						'text' => $varname,
						'href' => $this->mTitle->getLocalUrl( $actstr . 'variant=' . $code )
					);
				$vcount ++;
			}
		}

		wfRunHooks( 'SkinTemplateContentActions', array( &$content_actions ) );

		wfProfileOut( $fname );
		return $content_actions;
	}



	/**
	 * build array of common navigation links
	 * @return array
	 * @access private
	 */
	function buildNavUrls () {
		global $wgUseTrackbacks, $wgTitle, $wgArticle;

		$fname = 'SkinTemplate::buildNavUrls';
		wfProfileIn( $fname );

		global $wgUser, $wgRequest;
		global $wgEnableUploads, $wgUploadNavigationUrl;

		$action = $wgRequest->getText( 'action' );
		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );

		$nav_urls = array();
		$nav_urls['mainpage'] = array('href' => $this->makeI18nUrl('mainpage'));
		if( $wgEnableUploads ) {
			if ($wgUploadNavigationUrl) {
				$nav_urls['upload'] = array('href' => $wgUploadNavigationUrl );
			} else {
				$nav_urls['upload'] = array('href' => $this->makeSpecialUrl('Upload'));
			}
		} else {
			if ($wgUploadNavigationUrl)
				$nav_urls['upload'] = array('href' => $wgUploadNavigationUrl );
			else
				$nav_urls['upload'] = false;
		}
		$nav_urls['specialpages'] = array('href' => $this->makeSpecialUrl('Specialpages'));


		// A print stylesheet is attached to all pages, but nobody ever
		// figures that out. :)  Add a link...
		if( $this->iscontent && ($action == '' || $action == 'view' || $action == 'purge' ) ) {
			$revid = $wgArticle->getLatest();
			if ( !( $revid == 0 )  )
				$nav_urls['print'] = array(
					'text' => wfMsg( 'printableversion' ),
					'href' => $wgRequest->appendQuery( 'printable=yes' )
				);

			// Also add a "permalink" while we're at it
			if ( (int)$oldid ) {
				$nav_urls['permalink'] = array(
					'text' => wfMsg( 'permalink' ),
					'href' => ''
				);
			} else {
				if ( !( $revid == 0 )  )
					$nav_urls['permalink'] = array(
						'text' => wfMsg( 'permalink' ),
						'href' => $wgTitle->getLocalURL( "oldid=$revid" )
					);
			}

			wfRunHooks( 'SkinTemplateBuildNavUrlsNav_urlsAfterPermalink', array( &$this, &$nav_urls, &$oldid, &$revid ) );
		}

		if( $this->mTitle->getNamespace() != NS_SPECIAL) {
			$nav_urls['whatlinkshere'] = array(
				'href' => $this->makeSpecialUrl("Whatlinkshere/$this->thispage")
			);
			if( $this->mTitle->getArticleId() ) {
				$nav_urls['recentchangeslinked'] = array(
					'href' => $this->makeSpecialUrl("Recentchangeslinked/$this->thispage")
				);
			}
			if ($wgUseTrackbacks)
				$nav_urls['trackbacklink'] = array(
					'href' => $wgTitle->trackbackURL()
				);
		}

		if( $this->mTitle->getNamespace() == NS_USER || $this->mTitle->getNamespace() == NS_USER_TALK ) {
			$id = User::idFromName($this->mTitle->getText());
			$ip = User::isIP($this->mTitle->getText());
		} else {
			$id = 0;
			$ip = false;
		}

		if($id || $ip) { # both anons and non-anons have contri list
			$nav_urls['contributions'] = array(
				'href' => $this->makeSpecialUrl('Contributions/' . $this->mTitle->getText() )
			);
			if ( $wgUser->isAllowed( 'block' ) )
				$nav_urls['blockip'] = array(
					'href' => $this->makeSpecialUrl( 'Blockip/' . $this->mTitle->getText() )
				);
		} else {
			$nav_urls['contributions'] = false;
		}
		$nav_urls['emailuser'] = false;
		if( $this->showEmailUser( $id ) ) {
			$nav_urls['emailuser'] = array(
				'href' => $this->makeSpecialUrl('Emailuser/' . $this->mTitle->getText() )
			);
		}
		wfProfileOut( $fname );
		return $nav_urls;
	}

	/**
	 * Generate strings used for xml 'id' names
	 * @return string
	 * @access private
	 */
	function getNameSpaceKey () {
		return $this->mTitle->getNamespaceKey();
	}

	/**
	 * @access private
	 */
	function setupUserCss() {
		$fname = 'SkinTemplate::setupUserCss';
		wfProfileIn( $fname );

		global $wgRequest, $wgAllowUserCss, $wgUseSiteCss, $wgContLang, $wgSquidMaxage, $wgStylePath, $wgUser;

		$sitecss = '';
		$usercss = '';
		$siteargs = '&maxage=' . $wgSquidMaxage;

		# Add user-specific code if this is a user and we allow that kind of thing

		if ( $wgAllowUserCss && $this->loggedin ) {
			$action = $wgRequest->getText('action');

			# if we're previewing the CSS page, use it
			if( $this->mTitle->isCssSubpage() and $this->userCanPreview( $action ) ) {
				$siteargs = "&smaxage=0&maxage=0";
				$usercss = $wgRequest->getText('wpTextbox1');
			} else {
				$usercss = '@import "' .
				  $this->makeUrl($this->userpage . '/'.$this->skinname.'.css',
								 'action=raw&ctype=text/css') . '";' ."\n";
			}

			$siteargs .= '&ts=' . $wgUser->mTouched;
		}

		if ($wgContLang->isRTL()) $sitecss .= '@import "' . $wgStylePath . '/' . $this->stylename . '/rtl.css";' . "\n";

		# If we use the site's dynamic CSS, throw that in, too
		if ( $wgUseSiteCss ) {
			$query = "action=raw&ctype=text/css&smaxage=$wgSquidMaxage";
			$sitecss .= '@import "' . $this->makeNSUrl('Common.css', $query, NS_MEDIAWIKI) . '";' . "\n";
			$sitecss .= '@import "' . $this->makeNSUrl(ucfirst($this->skinname) . '.css', $query, NS_MEDIAWIKI) . '";' . "\n";
			$sitecss .= '@import "' . $this->makeUrl('-','action=raw&gen=css' . $siteargs) . '";' . "\n";
		}

		# If we use any dynamic CSS, make a little CDATA block out of it.

		if ( !empty($sitecss) || !empty($usercss) ) {
			$this->usercss = "/*<![CDATA[*/\n" . $sitecss . $usercss . '/*]]>*/';
		}
		wfProfileOut( $fname );
	}

	/**
	 * @access private
	 */
	function setupUserJs() {
		$fname = 'SkinTemplate::setupUserJs';
		wfProfileIn( $fname );

		global $wgRequest, $wgAllowUserJs, $wgJsMimeType;
		$action = $wgRequest->getText('action');

		if( $wgAllowUserJs && $this->loggedin ) {
			if( $this->mTitle->isJsSubpage() and $this->userCanPreview( $action ) ) {
				# XXX: additional security check/prompt?
				$this->userjsprev = '/*<![CDATA[*/ ' . $wgRequest->getText('wpTextbox1') . ' /*]]>*/';
			} else {
				$this->userjs = $this->makeUrl($this->userpage.'/'.$this->skinname.'.js', 'action=raw&ctype='.$wgJsMimeType.'&dontcountme=s');
			}
		}
		wfProfileOut( $fname );
	}

	/**
	 * Code for extensions to hook into to provide per-page CSS, see
	 * extensions/PageCSS/PageCSS.php for an implementation of this.
	 *
	 * @access private
	 */
	function setupPageCss() {
		$fname = 'SkinTemplate::setupPageCss';
		wfProfileIn( $fname );
		$out = false;
		wfRunHooks( 'SkinTemplateSetupPageCss', array( &$out ) );
		
		wfProfileOut( $fname );
		return $out;
	}

	/**
	 * returns css with user-specific options
	 * @access public
	 */

	function getUserStylesheet() {
		$fname = 'SkinTemplate::getUserStylesheet';
		wfProfileIn( $fname );

		$s = "/* generated user stylesheet */\n";
		$s .= $this->reallyDoGetUserStyles();
		wfProfileOut( $fname );
		return $s;
	}

	/**
	 * @access public
	 */
	function getUserJs() {
		$fname = 'SkinTemplate::getUserJs';
		wfProfileIn( $fname );

		global $wgStylePath;
		$s = '/* generated javascript */';
		$s .= "var skin = '{$this->skinname}';\nvar stylepath = '{$wgStylePath}';";
		$s .= '/* MediaWiki:'.ucfirst($this->skinname)." */\n";

		// avoid inclusion of non defined user JavaScript (with custom skins only)
		// by checking for default message content
		$msgKey = ucfirst($this->skinname).'.js';
		$userJS = wfMsg($msgKey);
		if ('&lt;'.$msgKey.'&gt;' != $userJS) {
			$s .= $userJS;
		}

		wfProfileOut( $fname );
		return $s;
	}
}

/**
 * Generic wrapper for template functions, with interface
 * compatible with what we use of PHPTAL 0.7.
 * @package MediaWiki
 * @subpackage Skins
 */
class QuickTemplate {
	/**
	 * @access public
	 */
	function QuickTemplate() {
		$this->data = array();
		$this->translator = new MediaWiki_I18N();
	}

	/**
	 * @access public
	 */
	function set( $name, $value ) {
		$this->data[$name] = $value;
	}

	/**
	 * @access public
	 */
	function setRef($name, &$value) {
		$this->data[$name] =& $value;
	}

	/**
	 * @access public
	 */
	function setTranslator( &$t ) {
		$this->translator = &$t;
	}

	/**
	 * @access public
	 */
	function execute() {
		echo "Override this function.";
	}


	/**
	 * @access private
	 */
	function text( $str ) {
		echo htmlspecialchars( $this->data[$str] );
	}

	/**
	 * @access private
	 */
	function html( $str ) {
		echo $this->data[$str];
	}

	/**
	 * @access private
	 */
	function msg( $str ) {
		echo htmlspecialchars( $this->translator->translate( $str ) );
	}

	/**
	 * @access private
	 */
	function msgHtml( $str ) {
		echo $this->translator->translate( $str );
	}

	/**
	 * An ugly, ugly hack.
	 * @access private
	 */
	function msgWiki( $str ) {
		global $wgParser, $wgTitle, $wgOut;

		$text = $this->translator->translate( $str );
		$parserOutput = $wgParser->parse( $text, $wgTitle,
			$wgOut->mParserOptions, true );
		echo $parserOutput->getText();
	}

	/**
	 * @access private
	 */
	function haveData( $str ) {
		return $this->data[$str];
	}

	/**
	 * @access private
	 */
	function haveMsg( $str ) {
		$msg = $this->translator->translate( $str );
		return ($msg != '-') && ($msg != ''); # ????
	}
}
?>
