<?php
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
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
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
 * PHPTAL support has been moved to a subclass in SkinPHPTal.php,
 * and is optional. You'll need to install PHPTAL manually to use
 * skins that depend on it.
 *
 * @package MediaWiki
 * @subpackage Skins
 */

/**
 * This is not a valid entry point, perform no further processing unless
 * MEDIAWIKI is defined
 */
if( defined( 'MEDIAWIKI' ) ) {

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
	 * For QuickTemplate, name or reference to callback function which
	 * will actually fill the template.
	 *
	 * In PHPTal mode, name of PHPTal template to be used.
	 * '.pt' will be automaticly added to it on PHPTAL object creation
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
		$this->template  = 'MonoBookTemplate';
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
	function &setupTemplate( $callback, $repository=false, $cache_dir=false ) {
		return new QuickTemplate( $callback );
	}
	
	/**
	 * initialize various variables and generate the template
	 *
	 * @param OutputPage $out
	 * @access public
	 */
	function outputPage( &$out ) {
		global $wgTitle, $wgArticle, $wgUser, $wgLang, $wgContLang, $wgOut;
		global $wgScript, $wgStylePath, $wgLanguageCode, $wgContLanguageCode, $wgUseNewInterlanguage;
		global $wgMimeType, $wgOutputEncoding, $wgUseDatabaseMessages, $wgRequest;
		global $wgDisableCounters, $wgLogo, $action, $wgFeedClasses, $wgSiteNotice;
		global $wgMaxCredits, $wgShowCreditsIfMax;

		$fname = 'SkinTemplate::outputPage';
		wfProfileIn( $fname );
		
		extract( $wgRequest->getValues( 'oldid', 'diff' ) );

		wfProfileIn( "$fname-init" );
		$this->initPage( $out );
		$tpl =& $this->setupTemplate( $this->template, 'skins' );

		#if ( $wgUseDatabaseMessages ) { // uncomment this to fall back to GetText
		$tpl->setTranslator(new MediaWiki_I18N());
		#}
		wfProfileOut( "$fname-init" );

		wfProfileIn( "$fname-stuff" );
		$this->thispage = $wgTitle->getPrefixedDbKey();
		$this->thisurl = $wgTitle->getPrefixedURL();
		$this->loggedin = $wgUser->getID() != 0;
		$this->iscontent = ($wgTitle->getNamespace() != Namespace::getSpecial() );
		$this->iseditable = ($this->iscontent and !($action == 'edit' or $action == 'submit'));
		$this->username = $wgUser->getName();
		$this->userpage = $wgContLang->getNsText( Namespace::getUser() ) . ":" . $wgUser->getName();
		$this->userpageUrlDetails = $this->makeUrlDetails($this->userpage);

		$this->usercss =  $this->userjs = $this->userjsprev = false;
		$this->setupUserCss();
		$this->setupUserJs();
		$this->titletxt = $wgTitle->getPrefixedText();
		wfProfileOut( "$fname-stuff" );

		wfProfileIn( "$fname-stuff2" );
		$tpl->set( 'title', $wgOut->getPageTitle() );
		$tpl->set( 'pagetitle', $wgOut->getHTMLTitle() );

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
					'href' => $wgRequest->appendQuery( "feed=$format" ),
					'ttip' => wfMsg('tooltip-'.$format)
				);
			}
			$tpl->setRef( 'feeds', $feeds );
		} else {
			$tpl->set( 'feeds', false );
		}
		$tpl->setRef( 'mimetype', $wgMimeType );
		$tpl->setRef( 'charset', $wgOutputEncoding );
		$tpl->set( 'headlinks', $out->getHeadLinks() );
		$tpl->setRef( 'wgScript', $wgScript );
		$tpl->setRef( 'skinname', $this->skinname );
		$tpl->setRef( 'stylename', $this->stylename );
		$tpl->setRef( 'loggedin', $this->loggedin );
		$tpl->set('nsclass', 'ns-'.$wgTitle->getNamespace());
		$tpl->set('notspecialpage', $wgTitle->getNamespace() != NS_SPECIAL);
		/* XXX currently unused, might get useful later
		$tpl->set( "editable", ($wgTitle->getNamespace() != NS_SPECIAL ) );
		$tpl->set( "exists", $wgTitle->getArticleID() != 0 );
		$tpl->set( "watch", $wgTitle->userIsWatching() ? "unwatch" : "watch" );
		$tpl->set( "protect", count($wgTitle->getRestrictions()) ? "unprotect" : "protect" );
		$tpl->set( "helppage", wfMsg('helppage'));
		*/
		$tpl->set( 'searchaction', $this->escapeSearchLink() );
		$tpl->setRef( 'stylepath', $wgStylePath );
		$tpl->setRef( 'logopath', $wgLogo );
		$tpl->setRef( "lang", $wgContLanguageCode );
		$tpl->set( 'dir', $wgContLang->isRTL() ? "rtl" : "ltr" );
		$tpl->set( 'rtl', $wgContLang->isRTL() );
		$tpl->set( 'langname', $wgContLang->getLanguageName( $wgContLanguageCode ) );
		$tpl->setRef( 'username', $this->username );
		$tpl->setRef( 'userpage', $this->userpage);
		$tpl->setRef( 'userpageurl', $this->userpageUrlDetails['href']);
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
		if( $wgUser->getNewtalk() ) {
			$usertitle = Title::newFromText( $this->userpage );
			$usertalktitle = $usertitle->getTalkPage();
			if($usertalktitle->getPrefixedDbKey() != $this->thispage){

				$ntl = wfMsg( 'newmessages',
				$this->makeKnownLink(
					$wgContLang->getNsText( Namespace::getTalk( Namespace::getUser() ) )
					. ':' . $this->username,
					wfMsg('newmessageslink') )
				);
				# Disable Cache
				$wgOut->setSquidMaxage(0);
			}
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
			}
			$tpl->set('lastmod', $this->lastModified());
			$tpl->set('copyright',$this->getCopyright());

			$this->credits = false;

			if (isset($wgMaxCredits) && $wgMaxCredits != 0) {
				require_once("Credits.php");
				$this->credits = getCredits($wgArticle, $wgMaxCredits, $wgShowCreditsIfMax);
			}

			$tpl->setRef( 'credits', $this->credits );

		} elseif ( isset( $oldid ) && !isset( $diff ) ) {
			$tpl->set('copyright', $this->getCopyright());
			$tpl->set('viewcount', false);
			$tpl->set('lastmod', false);
			$tpl->set('credits', false);
		} else {
			$tpl->set('copyright', false);
			$tpl->set('viewcount', false);
			$tpl->set('lastmod', false);
			$tpl->set('credits', false);
		}
		wfProfileOut( "$fname-stuff3" );

		wfProfileIn( "$fname-stuff4" );
		$tpl->set( 'copyrightico', $this->getCopyrightIcon() );
		$tpl->set( 'poweredbyico', $this->getPoweredBy() );
		$tpl->set( 'disclaimer', $this->disclaimerLink() );
		$tpl->set( 'about', $this->aboutLink() );

		$tpl->setRef( 'debug', $out->mDebugtext );
		$tpl->set( 'reporttime', $out->reportTime() );
		$tpl->set( 'sitenotice', $wgSiteNotice );

		$printfooter = "<div class=\"printfooter\">\n" . $this->printSource() . "</div>\n";
		$out->mBodytext .= $printfooter ;
		$tpl->setRef( 'bodytext', $out->mBodytext );

		# Language links
		$language_urls = array();
		foreach( $wgOut->getLanguageLinks() as $l ) {
			$nt = Title::newFromText( $l );
			$language_urls[] = array('href' => $nt->getFullURL(),
			'text' => ($wgContLang->getLanguageName( $nt->getInterwiki()) != ''?$wgContLang->getLanguageName( $nt->getInterwiki()) : $l),
			'class' => $wgContLang->isRTL() ? 'rtl' : 'ltr');
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
		$tpl->set( 'navigation_urls', $this->buildNavigationUrls() );
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
		$fname = 'SkinTemplate::buildPersonalUrls';
		wfProfileIn( $fname );
		
		/* set up the default links for the personal toolbar */
		global $wgShowIPinHeader;
		$personal_urls = array();
		if ($this->loggedin) {
			$personal_urls['userpage'] = array(
				'text' => $this->username,
				'href' => &$this->userpageUrlDetails['href'],
				'class' => $this->userpageUrlDetails['exists']?false:'new'
			);
			$usertalkUrlDetails = $this->makeTalkUrlDetails($this->userpage);
			$personal_urls['mytalk'] = array(
				'text' => wfMsg('mytalk'),
				'href' => &$usertalkUrlDetails['href'],
				'class' => $usertalkUrlDetails['exists']?false:'new'
			);
			$personal_urls['preferences'] = array(
				'text' => wfMsg('preferences'),
				'href' => $this->makeSpecialUrl('Preferences')
			);
			$personal_urls['watchlist'] = array(
				'text' => wfMsg('watchlist'),
				'href' => $this->makeSpecialUrl('Watchlist')
			);
			$personal_urls['mycontris'] = array(
				'text' => wfMsg('mycontris'),
				'href' => $this->makeSpecialUrl('Contributions','target=' . urlencode( $this->username ) )
			);
			$personal_urls['logout'] = array(
				'text' => wfMsg('userlogout'),
				'href' => $this->makeSpecialUrl('Userlogout','returnto=' . $this->thisurl )
			);
		} else {
			if( $wgShowIPinHeader && isset(  $_COOKIE[ini_get("session.name")] ) ) {
				$personal_urls['anonuserpage'] = array(
					'text' => $this->username,
					'href' => &$this->userpageUrlDetails['href'],
					'class' => $this->userpageUrlDetails['exists']?false:'new'
				);
				$usertalkUrlDetails = $this->makeTalkUrlDetails($this->userpage);
				$personal_urls['anontalk'] = array(
					'text' => wfMsg('anontalk'),
					'href' => &$usertalkUrlDetails['href'],
					'class' => $usertalkUrlDetails['exists']?false:'new'
				);
				$personal_urls['anonlogin'] = array(
					'text' => wfMsg('userlogin'),
					'href' => $this->makeSpecialUrl('Userlogin', 'returnto=' . $this->thisurl )
				);
			} else {

				$personal_urls['login'] = array(
					'text' => wfMsg('userlogin'),
					'href' => $this->makeSpecialUrl('Userlogin', 'returnto=' . $this->thisurl )
				);
			}
		}
		wfProfileOut( $fname );
		return $personal_urls;
	}

	/**
	 * an array of edit links by default used for the tabs
	 * @return array
	 * @access private
	 */
	function buildContentActionUrls () {
		$fname = 'SkinTemplate::buildContentActionUrls';
		wfProfileIn( $fname );
		
		global $wgTitle, $wgUser, $wgOut, $wgRequest, $wgUseValidation;
		$action = $wgRequest->getText( 'action' );
		$section = $wgRequest->getText( 'section' );
		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		$content_actions = array();

		if( $this->iscontent and !$wgOut->isQuickbarSuppressed() ) {

			$nskey = $this->getNameSpaceKey();
			$is_active = !Namespace::isTalk( $wgTitle->getNamespace()) ;
			if ( $action == 'validate' ) $is_active = false ; # Show article tab deselected when validating
			$content_actions[$nskey] = array('class' => ($is_active) ? 'selected' : false,
			'text' => wfMsg($nskey),
			'href' => $this->makeArticleUrl($this->thispage));

			/* set up the classes for the talk link */
			wfProfileIn( "$fname-talk" );
			$talk_class = (Namespace::isTalk( $wgTitle->getNamespace()) ? 'selected' : false);
			$talktitle = $wgTitle->getTalkPage();
			if( $talktitle->getArticleId() != 0 ) {
				$content_actions['talk'] = array(
					'class' => $talk_class,
					'text' => wfMsg('talk'),
					'href' => $talktitle->getLocalUrl()
				);
			} else {
				$content_actions['talk'] = array(
					'class' => $talk_class ? $talk_class.' new' : 'new',
					'text' => wfMsg('talk'),
					'href' => $talktitle->getLocalUrl( 'action=edit' )
				);
			}
			wfProfileOut( "$fname-talk" );

			wfProfileIn( "$fname-edit" );
			if ( $wgTitle->userCanEdit() ) {
				$oid = ( $oldid && ! isset( $diff ) ) ? '&oldid='.IntVal( $oldid ) : false;
				$istalk = ( Namespace::isTalk( $wgTitle->getNamespace()) );
				$istalkclass = $istalk?' istalk':'';
				$content_actions['edit'] = array(
					'class' => ((($action == 'edit' or $action == 'submit') and $section != 'new') ? 'selected' : '').$istalkclass,
					'text' => wfMsg('edit'),
					'href' => $wgTitle->getLocalUrl( 'action=edit'.$oid )
				);
				if ( $istalk ) {
					$content_actions['addsection'] = array(
						'class' => $section == 'new'?'selected':false,
						'text' => wfMsg('addsection'),
						'href' => $wgTitle->getLocalUrl( 'action=edit&section=new' )
					);
				}
			} else {
				$oid = ( $oldid && ! isset( $diff ) ) ? '&oldid='.IntVal( $oldid ) : '';
				$content_actions['viewsource'] = array(
					'class' => ($action == 'edit') ? 'selected' : false,
					'text' => wfMsg('viewsource'),
					'href' => $wgTitle->getLocalUrl( 'action=edit'.$oid )
				);
			}
			wfProfileOut( "$fname-edit" );

			wfProfileIn( "$fname-live" );
			if ( $wgTitle->getArticleId() ) {

				$content_actions['history'] = array(
					'class' => ($action == 'history') ? 'selected' : false,
					'text' => wfMsg('history_short'),
					'href' => $wgTitle->getLocalUrl( 'action=history')
				);

				# XXX: is there a rollback action anywhere or is it planned?
				# Don't recall where i got this from...
				/*if( $wgUser->getNewtalk() ) {
					$content_actions['rollback'] = array('class' => ($action == 'rollback') ? 'selected' : false,
					'text' => wfMsg('rollback_short'),
					'href' => $this->makeUrl($this->thispage, 'action=rollback'),
					'ttip' => wfMsg('tooltip-rollback'),
					'akey' => wfMsg('accesskey-rollback'));
				}
				*/

				if($wgUser->isAllowed('protect')){
					if(!$wgTitle->isProtected()){
						$content_actions['protect'] = array(
							'class' => ($action == 'protect') ? 'selected' : false,
							'text' => wfMsg('protect'),
							'href' => $wgTitle->getLocalUrl( 'action=protect' )
						);

					} else {
						$content_actions['unprotect'] = array(
							'class' => ($action == 'unprotect') ? 'selected' : false,
							'text' => wfMsg('unprotect'),
							'href' => $wgTitle->getLocalUrl( 'action=unprotect' )
						);
					}
				}
				if($wgUser->isAllowed('delete')){
					$content_actions['delete'] = array(
						'class' => ($action == 'delete') ? 'selected' : false,
						'text' => wfMsg('delete'),
						'href' => $wgTitle->getLocalUrl( 'action=delete' )
					);
				}
				if ( $wgUser->getID() != 0 ) {
					if ( $wgTitle->userCanEdit()) {
						$content_actions['move'] = array(
							'class' => ($wgTitle->getDbKey() == 'Movepage' and $wgTitle->getNamespace == Namespace::getSpecial()) ? 'selected' : false,
							'text' => wfMsg('move'),
							'href' => $this->makeSpecialUrl('Movepage', 'target='. urlencode( $this->thispage ) )
						);
					}
				}
			} else {
				//article doesn't exist or is deleted
				if($wgUser->isAllowed('delete')){
					if( $n = $wgTitle->isDeleted() ) {
						$content_actions['undelete'] = array(
							'class' => false,
							'text' => wfMsg( "undelete_short", $n ),
							'href' => $this->makeSpecialUrl('Undelete/'.$this->thispage)
						);
					}
				}
			}
			wfProfileOut( "$fname-live" );
			
			if ( $wgUser->getID() != 0 and $action != 'submit' ) {
				if( !$wgTitle->userIsWatching()) {
					$content_actions['watch'] = array(
						'class' => ($action == 'watch' or $action == 'unwatch') ? 'selected' : false,
						'text' => wfMsg('watch'),
						'href' => $wgTitle->getLocalUrl( 'action=watch' )
					);
				} else {
					$content_actions['unwatch'] = array(
						'class' => ($action == 'unwatch' or $action == 'watch') ? 'selected' : false,
						'text' => wfMsg('unwatch'),
						'href' => $wgTitle->getLocalUrl( 'action=unwatch' )
					);
				}
			}

			# Show validate tab
			if ( $wgUseValidation && $wgTitle->getArticleId() && $wgTitle->getNamespace() == 0 ) {
				global $wgArticle ;
				$article_time = "&timestamp=" . $wgArticle->mTimestamp ;
				$content_actions['validate'] = array(
					'class' => ($action == 'validate') ? 'selected' : false ,
					'text' => wfMsg('val_tab'),
					'href' => $wgTitle->getLocalUrl( 'action=validate'.$article_time)
				);
			}
		} else {
			/* show special page tab */

			$content_actions['article'] = array(
				'class' => 'selected',
				'text' => wfMsg('specialpage'),
				'href' => false
			);
		}

		wfProfileOut( $fname );
		return $content_actions;
	}

	/**
	 * build array of global navigation links
	 * @return array
	 * @access private
	 */ 
	function buildNavigationUrls () {
		$fname = 'SkinTemplate::buildNavigationUrls';
		wfProfileIn( $fname );
		
		global $wgNavigationLinks;
		$result = array();
		foreach ( $wgNavigationLinks as $link ) {
			$text = wfMsg( $link['text'] );
			wfProfileIn( "$fname-{$link['text']}" );
			if ($text != '-') {
				$dest = wfMsgForContent( $link['href'] );
				wfProfileIn( "$fname-{$link['text']}2" );
			    $result[] = array(
								  'text' => $text,
								  'href' => $this->makeInternalOrExternalUrl( $dest ),
								  'id' => 'n-'.$link['text']
								  );
				wfProfileOut( "$fname-{$link['text']}2" );
			}
			wfProfileOut( "$fname-{$link['text']}" );
		}
		wfProfileOut( $fname );
		return $result;
	}

	/**
	 * build array of common navigation links
	 * @return array
	 * @access private
	 */
	function buildNavUrls () {
		$fname = 'SkinTemplate::buildNavUrls';
		wfProfileIn( $fname );
		
		global $wgTitle, $wgUser, $wgRequest;
		global $wgSiteSupportPage, $wgDisableUploads;

		$action = $wgRequest->getText( 'action' );
		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		
		$nav_urls = array();
		$nav_urls['mainpage'] = array('href' => $this->makeI18nUrl('mainpage'));
		$nav_urls['randompage'] = array('href' => $this->makeSpecialUrl('Randompage'));
		$nav_urls['recentchanges'] = array('href' => $this->makeSpecialUrl('Recentchanges'));
		$nav_urls['currentevents'] = (wfMsgForContent('currentevents') != '-') ? array('href' => $this->makeI18nUrl('currentevents')) : false;
		$nav_urls['portal'] =  (wfMsgForContent('portal') != '-') ? array('href' => $this->makeI18nUrl('portal-url')) : false;
		$nav_urls['bugreports'] = array('href' => $this->makeI18nUrl('bugreportspage'));
		// $nav_urls['sitesupport'] = array('href' => $this->makeI18nUrl('sitesupportpage'));
		$nav_urls['sitesupport'] = array('href' => $wgSiteSupportPage);
		$nav_urls['help'] = array('href' => $this->makeI18nUrl('helppage'));
		if( $this->loggedin && !$wgDisableUploads ) {
			$nav_urls['upload'] = array('href' => $this->makeSpecialUrl('Upload'));
		} else {
			$nav_urls['upload'] = false;
		}
		$nav_urls['specialpages'] = array('href' => $this->makeSpecialUrl('Specialpages'));

		if( $wgTitle->getNamespace() != NS_SPECIAL) {
			$nav_urls['whatlinkshere'] = array('href' => $this->makeSpecialUrl('Whatlinkshere', 'target='.urlencode( $this->thispage)));
			$nav_urls['recentchangeslinked'] = array('href' => $this->makeSpecialUrl('Recentchangeslinked', 'target='.urlencode( $this->thispage)));
		}

		if( $wgTitle->getNamespace() == NS_USER || $wgTitle->getNamespace() == NS_USER_TALK ) {
			$id = User::idFromName($wgTitle->getText());
			$ip = User::isIP($wgTitle->getText());
		} else {
			$id = 0;
			$ip = false;
		}

		if($id || $ip) { # both anons and non-anons have contri list
			$nav_urls['contributions'] = array(
				'href' => $this->makeSpecialUrl('Contributions', "target=" . $wgTitle->getPartialURL() )
			);
		} else {
			$nav_urls['contributions'] = false;
		}
		$nav_urls['emailuser'] = false;
		if ( 0 != $wgUser->getID() ) { # show only to signed in users
			if($id) {	# can only email non-anons
				$nav_urls['emailuser'] = array(
					'href' => $this->makeSpecialUrl('Emailuser', "target=" . $wgTitle->getPartialURL() )
				);
			}
		}
		wfProfileOut( $fname );
		return $nav_urls;
	}

	/**
	 * Generate strings used for xml 'id' names
	 * @return string
	 * @private
	 */
	function getNameSpaceKey () {
		global $wgTitle;
		switch ($wgTitle->getNamespace()) {
			case NS_MAIN:
			case NS_TALK:
				return 'nstab-main';
			case NS_USER:
			case NS_USER_TALK:
				return 'nstab-user';
			case NS_MEDIA:
				return 'nstab-media';
			case NS_SPECIAL:
				return 'nstab-special';
			case NS_PROJECT:
			case NS_PROJECT_TALK:
				return 'nstab-wp';
			case NS_IMAGE:
			case NS_IMAGE_TALK:
				return 'nstab-image';
			case NS_MEDIAWIKI:
			case NS_MEDIAWIKI_TALK:
				return 'nstab-mediawiki';
			case NS_TEMPLATE:
			case NS_TEMPLATE_TALK:
				return 'nstab-template';
			case NS_HELP:
			case NS_HELP_TALK:
				return 'nstab-help';
			case NS_CATEGORY:
			case NS_CATEGORY_TALK:
				return 'nstab-category';
			default:
				return 'nstab-main';
		}
	}
	
	/**
	 * @access private
	 */
	function setupUserCss() {
		$fname = 'SkinTemplate::setupUserCss';
		wfProfileIn( $fname );
		
		global $wgRequest, $wgTitle, $wgAllowUserCss, $wgUseSiteCss;

		$sitecss = "";
		$usercss = "";
		$siteargs = "";

		# Add user-specific code if this is a user and we allow that kind of thing
		
		if ( $wgAllowUserCss && $this->loggedin ) {
			$action = $wgRequest->getText('action');
			
			# if we're previewing the CSS page, use it
			if($wgTitle->isCssSubpage() and $action == 'submit' and  $wgTitle->userCanEditCssJsSubpage()) {
				$siteargs .= "&smaxage=0&maxage=0";
				$usercss = $wgRequest->getText('wpTextbox1');
			} else {
				$siteargs .= "&maxage=0";
				$usercss = '@import "' .
				  $this->makeUrl($this->userpage . '/'.$this->skinname.'.css',
								 'action=raw&ctype=text/css') . '";' ."\n";
			}
		}

		# If we use the site's dynamic CSS, throw that in, too
		
		if ( $wgUseSiteCss ) {
			$sitecss = '@import "'.$this->makeUrl('-','action=raw&gen=css' . $siteargs).'";'."\n";
		}
		
		# If we use any dynamic CSS, make a little CDATA block out of it.
		
		if ( !empty($sitecss) || !empty($usercss) ) {
			$this->usercss = '/*<![CDATA[*/ ' . $sitecss . ' ' . $usercss . ' /*]]>*/';
		}
		wfProfileOut( $fname );
	}

	/**
	 * @access private
	 */
	function setupUserJs() {
		$fname = 'SkinTemplate::setupUserJs';
		wfProfileIn( $fname );
		
		global $wgRequest, $wgTitle, $wgAllowUserJs;
		$action = $wgRequest->getText('action');

		if( $wgAllowUserJs && $this->loggedin ) {
			if($wgTitle->isJsSubpage() and $action == 'submit' and  $wgTitle->userCanEditCssJsSubpage()) {
				# XXX: additional security check/prompt?
				$this->userjsprev = '/*<![CDATA[*/ ' . $wgRequest->getText('wpTextbox1') . ' /*]]>*/';
			} else {
				$this->userjs = $this->makeUrl($this->userpage.'/'.$this->skinname.'.js', 'action=raw&ctype=text/javascript&dontcountme=s');
			}
		}
		wfProfileOut( $fname );
	}
	
	/**
	 * returns css with user-specific options
	 * @access public
	 */
	function getUserStylesheet() {
		$fname = 'SkinTemplate::getUserStylesheet';
		wfProfileIn( $fname );
		
		global $wgUser, $wgRequest, $wgTitle, $wgContLang, $wgSquidMaxage, $wgStylePath;
		$action = $wgRequest->getText('action');
		$maxage = $wgRequest->getText('maxage');
		$s = "/* generated user stylesheet */\n";
		if($wgContLang->isRTL()) $s .= '@import "'.$wgStylePath.'/'.$this->stylename.'/rtl.css";'."\n";
		$s .= '@import "'.
		$this->makeNSUrl(ucfirst($this->skinname).'.css', 'action=raw&ctype=text/css&smaxage='.$wgSquidMaxage, NS_MEDIAWIKI)."\";\n";
		if($wgUser->getID() != 0) {
			if ( 1 == $wgUser->getOption( "underline" ) ) {
				$s .= "a { text-decoration: underline; }\n";
			} else {
				$s .= "a { text-decoration: none; }\n";
			}
		}
		if ( 1 != $wgUser->getOption( "highlightbroken" ) ) {
			$s .= "a.new, #quickbar a.new { color: #CC2200; }\n";
		}
		if ( 1 == $wgUser->getOption( "justify" ) ) {
			$s .= "#bodyContent { text-align: justify; }\n";
		}
		wfProfileOut( $fname );
		return $s;
	}
	
	/**
	 * @access public
	 */
	function getUserJs() {
		$fname = 'SkinTemplate::getUserJs';
		wfProfileIn( $fname );
		
		global $wgUser, $wgStylePath;
		$s = '/* generated javascript */';
		$s .= "var skin = '{$this->skinname}';\nvar stylepath = '{$wgStylePath}';";
		$s .= '/* MediaWiki:'.ucfirst($this->skinname)." */\n";
		$s .= wfMsg(ucfirst($this->skinname).'.js');
		
		wfProfileOut( $fname );
		return $s;
	}
}

/**
 * Generic wrapper for template functions, with interface
 * compatible with what we use of PHPTAL 0.7.
 */
class QuickTemplate {
	function QuickTemplate( $callback, $repository=false, $cache_dir=false ) {
		$this->outputCallback = $callback;
		$this->data = array();
		$this->translator = null;
	}
	
	function set( $name, $value ) {
		$this->data[$name] = $value;
	}
	
	function setRef($name, &$value) {
		$this->data[$name] =& $value;
	}
	
	function setTranslator( &$t ) {
		$this->translator = &$t;
	}
	
	function execute() {
		return call_user_func_array( $this->outputCallback,
			array( &$this->data, &$this->translator ) );
	}
}

} // end of if( defined( 'MEDIAWIKI' ) ) 
?>
