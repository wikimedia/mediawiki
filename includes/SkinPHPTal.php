<?php
# Generic PHPTal (http://phptal.sourceforge.net/) skin
# Based on Brion's smarty skin
# Copyright (C) Gabriel Wicke -- http://www.aulinx.de/
#
# Todo: Needs some serious refactoring into functions that correspond
# to the computations individual esi snippets need. Most importantly no body
# parsing for most of those of course.
#
# Set this in LocalSettings to enable phptal:
# set_include_path(get_include_path() . ":" . $IP.'/PHPTAL-NP-0.7.0/libs');
# $wgUsePHPTal = true;
#
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

	require_once "PHPTAL.php";

	class MediaWiki_I18N extends PHPTAL_I18N
	{
		var $_context = array();

		function set($varName, $value)
		{
			$this->_context[$varName] = $value;
		}

		function translate($value)
		{
			$value = wfMsg( $value );

			// interpolate variables
			while (preg_match('/\$([0-9]*?)/sm', $value, $m)) {
				list($src, $var) = $m;
				$varValue = $this->_context[$var];
				$value = str_replace($src, $varValue, $value);
			}
			return $value;
		}
	}

	class SkinPHPTal extends Skin {
		var $template;

		function initPage() {
			$this->skinname = "davinci";
			$this->template = "xhtml_slim";
		}

		function outputPage( &$out ) {
			global $wgTitle, $wgArticle, $wgUser, $wgLang, $wgOut;
			global $wgScriptPath, $wgStyleSheetPath, $wgLanguageCode, $wgUseNewInterlanguage;
			global $wgOutputEncoding, $wgUseDatabaseMessages, $wgRequest;

			$this->thispage = $wgTitle->getPrefixedDbKey();
			$this->loggedin = $wgUser->getID() != 0;
			$this->username = $wgUser->getName();
			$this->userpage = $wgLang->getNsText( Namespace::getUser() ) . ":" . $wgUser->getName();
			$this->titletxt = $wgTitle->getPrefixedText();
			
			
			$this->initPage();
			$tpl = new PHPTAL($this->template . '.pt', 'templates');
			
			#if ( $wgUseDatabaseMessages ) { // uncomment this to fall back to GetText
			$tpl->setTranslator(new MediaWiki_I18N());
			#}

			$tpl->setRef( "title", &$this->titletxt ); // ?
			$tpl->setRef( "thispage", &$this->thispage );
			$tpl->set( "subtitle", $out->getSubtitle() );
			$tpl->setRef( 'charset', $wgOutputEncoding);
			$tpl->setRef( 'skinname', $this->skinname );
			$tpl->setRef( "loggedin", &$this->loggedin );
			$tpl->set( "editable", ($wgTitle->getNamespace != Namespace::getSpecial() ) );
			$tpl->set( "exists", $wgTitle->getArticleID() != 0 );
			$tpl->set( "watch", $wgTitle->userIsWatching() ? "unwatch" : "watch" );
			$tpl->set( "protect", count($wgTitle->getRestrictions()) ? "unprotect" : "protect" );
			$tpl->set( "helppage", wfMsg('helppage'));
			$tpl->setRef( "searchaction", &$wgScriptPath );
			$tpl->setRef( "stylepath", &$wgStyleSheetPath );
			$tpl->setRef( "lang", &$wgLanguageCode );
			$tpl->set( "langname", $wgLang->getLanguageName( $wgLanguageCode ) );
			$tpl->setRef( "username", &$this->username );
			$tpl->setRef( "userpage", &$this->userpage);
			$tpl->set( "sysop", $wgUser->isSysop() );
			if( $wgUser->getNewtalk() ) {
				$ntl = wfMsg( "newmessages",
				$this->makeKnownLink( 
					$wgLang->getNsText( Namespace::getTalk( Namespace::getUser() ) )
					. ":" . $wgUser->getName(),
					wfMsg("newmessageslink") ) 
				);
			} else {
				$ntl = "";
			}
			$tpl->setRef( "newtalk", &$ntl );
			$tpl->setRef( "skin", &$this);
			$tpl->set( "logo", $this->logoText() );
			$tpl->set( "pagestats", $this->pageStats() );
			$tpl->set( "disclaimer", $this->disclaimerLink() );
			$tpl->set( "about", $this->aboutLink() );

			$tpl->setRef( "debug", &$out->mDebugtext );
			$tpl->set( "reporttime", $out->reportTime() );

			$tpl->setRef( "bodytext", &$out->mBodytext );

			$language_urls = array();
			foreach( $wgOut->getLanguageLinks() as $l ) {
				$nt = Title::newFromText( $l );
				$language_urls[] = array('href' => $nt->getFullURL(),
				'text' => ($wgLang->getLanguageName( $nt->getInterwiki()) != ''?$wgLang->getLanguageName( $nt->getInterwiki()) : $l),
				'class' => $wgLang->isRTL() ? 'rtl' : 'ltr');
			}
			if(count($language_urls) != 0 ) {
				$tpl->setRef( 'language_urls', &$language_urls);
			} else {
				$tpl->set('language_urls', false);
			}
			$tpl->set('personal_urls', $this->buildPersonalUrls());
			$tpl->set('content_actions', $this->buildContentActionUrls());
			$tpl->set( "nav_urls", $this->buildNavUrls() );

			// execute template
			$res = $tpl->execute();
			// result may be an error
			if (PEAR::isError($res)) {
				echo $res->toString(), "\n";
			} else {
				echo $res;
			}

		}

		# build array of urls for personal toolbar
		function buildPersonalUrls() {
			/* set up the default links for the personal toolbar */
			$personal_urls = array();
			if ($this->loggedin) {
				$personal_urls['userpage'] = array('text' => $this->username,
					'href' => $this->makeUrl($this->userpage),
					'ttip' => wfMsg('tooltip-userpage'),
					'akey' => wfMsg('accesskey-userpage'));
				$personal_urls['mytalk'] = array('text' => wfMsg('mytalk'),
					'href' => $this->makeTalkUrl($this->userpage),
					'ttip' => wfMsg('tooltip-mytalk'),
					'akey' => wfMsg('accesskey-mytalk'));
				$personal_urls['preferences'] = array('text' => wfMsg('preferences'),
					'href' => $this->makeSpecialUrl('Preferences'),
					'ttip' => wfMsg('tooltip-preferences'),
					'akey' => wfMsg('accesskey-preferences'));
				$personal_urls['watchlist'] = array('text' => wfMsg('watchlist'),
					'href' => $this->makeSpecialUrl('Watchlist'),
					'ttip' => wfMsg('tooltip-watchlist'),
					'akey' => wfMsg('accesskey-watchlist'));
				$personal_urls['mycontris'] = array('text' => wfMsg('mycontris'),
					'href' => $this->makeSpecialUrl('Contributions','target=' . $this->username),
					'ttip' => wfMsg('tooltip-mycontris'),
					'akey' => wfMsg('accesskey-mycontris'));
				$personal_urls['logout'] = array('text' => wfMsg('userlogout'),
					'href' => $this->makeSpecialUrl('Userlogout','returnpage=' . $this->thispage),
					'ttip' => wfMsg('tooltip-logout'),
					'akey' => wfMsg('accesskey-logout'));
			} else {
				$personal_urls['login'] = array('text' => wfMsg('userlogin'),
					'href' => $this->makeSpecialUrl('Userlogin'),
					'ttip' => wfMsg('tooltip-login'),
					'akey' => wfMsg('accesskey-login'));
			}
			return $personal_urls;
		}
		
		# an array of edit links by default used for the tabs
		function buildContentActionUrls () {
			global $wgTitle, $wgUser, $wgRequest;
			$action = $wgRequest->getText( 'action' );
			$oldid = $wgRequest->getVal( 'oldid' );
			$diff = $wgRequest->getVal( 'diff' );
			$content_actions = array();
			
			$iscontent = ($wgTitle->getNamespace() != Namespace::getSpecial() );
			if( $iscontent) {

				$content_actions['article'] = array('class' => (!Namespace::isTalk( $wgTitle->getNamespace())) ? 'selected' : '',
				'text' => wfMsg('article'),
				'href' => $this->makeArticleUrl($this->thispage),
				'ttip' => wfMsg('tooltip-article'),
				'akey' => wfMsg('accesskey-article'));

				$content_actions['talk'] = array('class' => (Namespace::isTalk( $wgTitle->getNamespace()) ? 'selected' : ''),
				'text' => wfMsg('talk'),
				'href' => $this->makeTalkUrl($this->titletxt),
				'ttip' => wfMsg('tooltip-talk'),
				'akey' => wfMsg('accesskey-talk'));

				if ( $wgTitle->userCanEdit() ) {
					if ( $oldid && ! isset( $diff ) ) {
						$oid = "&oldid={$oldid}";
					}
					$content_actions['edit'] = array('class' => ($action == 'edit' or $action == 'submit') ? 'selected' : '',
					'text' => wfMsg('edit'),
					'href' => $this->makeUrl($this->thispage, 'action=edit'.$oid),
					'ttip' => wfMsg('tooltip-edit'),
					'akey' => wfMsg('accesskey-edit'));
				} else {
					if ( $oldid && ! isset( $diff ) ) {
						$oid = "&oldid={$oldid}";
					}
					$content_actions['edit'] = array('class' => ($action == 'edit') ? 'selected' : '',
					'text' => wfMsg('viewsource'),
					'href' => $this->makeUrl($this->thispage, 'action=edit'.$oid),
					'ttip' => wfMsg('tooltip-edit'),
					'akey' => wfMsg('accesskey-edit'));
				}

				if ( $wgTitle->getArticleId() ) {

					$content_actions['history'] = array('class' => ($action == 'history') ? 'selected' : '',
					'text' => wfMsg('history_short'),
					'href' => $this->makeUrl($this->thispage, 'action=history'),
					'ttip' => wfMsg('tooltip-history'),
					'akey' => wfMsg('accesskey-history'));

					# XXX: is there a rollback action anywhere or is it planned?
					# Don't recall where i got this from...
					/*if( $wgUser->getNewtalk() ) {
						$content_actions['rollback'] = array('class' => ($action == 'rollback') ? 'selected' : '',
						'text' => wfMsg('rollback_short'),
						'href' => $this->makeUrl($this->thispage, 'action=rollback'),
						'ttip' => wfMsg('tooltip-rollback'),
						'akey' => wfMsg('accesskey-rollback'));
					}*/

					if($wgUser->isSysop()){
						if(!$wgTitle->isProtected()){
							$content_actions['protect'] = array('class' => ($action == 'protect') ? 'selected' : '',
							'text' => wfMsg('protect'),
							'href' => $this->makeUrl($this->thispage, 'action=protect'),
							'ttip' => wfMsg('tooltip-protect'),
							'akey' => wfMsg('accesskey-protect'));

						} else {
							$content_actions['unprotect'] = array('class' => ($action == 'unprotect') ? 'selected' : '',
							'text' => wfMsg('unprotect'),
							'href' => $this->makeUrl($this->thispage, 'action=unprotect'),
							'ttip' => wfMsg('tooltip-protect'),
							'akey' => wfMsg('accesskey-protect'));
						}
						$content_actions['delete'] = array('class' => ($action == 'delete') ? 'selected' : '',
						'text' => wfMsg('delete'),
						'href' => $this->makeUrl($this->thispage, 'action=delete'),
						'ttip' => wfMsg('tooltip-delete'),
						'akey' => wfMsg('accesskey-delete'));
					}
					if ( $wgUser->getID() != 0 ) {
						if ( $wgTitle->userCanEdit()) {
							$content_actions['move'] = array('class' => ($wgTitle->getDbKey() == 'Movepage' and $wgTitle->getNamespace == Namespace::getSpecial()) ? 'selected' : '',
							'text' => wfMsg('move'),
							'href' => $this->makeSpecialUrl('Movepage', 'target='.$this->thispage),
							'ttip' => wfMsg('tooltip-move'),
							'akey' => wfMsg('accesskey-move'));
						} else {
							$content_actions['move'] = array('class' => 'inactive',
							'text' => wfMsg('move'),
							'href' => false,
							'ttip' => wfMsg('tooltip-nomove'),
							'akey' => false);

						}
					}
				}

				if ( $wgUser->getID() != 0 and $action != 'edit' and $action != 'submit' ) {
					if( !$wgTitle->userIsWatching()) {
						$content_actions['watch'] = array('class' => ($action == 'watch' or $action == 'unwatch') ? 'selected' : '',
						'text' => wfMsg('watch'),
						'href' => $this->makeUrl($this->thispage, 'action=watch'),
						'ttip' => wfMsg('tooltip-watch'),
						'akey' => wfMsg('accesskey-watch'));
					} else {
						$content_actions['watch'] = array('class' => ($action == 'unwatch' or $action == 'watch') ? 'selected' : '',
						'text' => wfMsg('unwatch'),
						'href' => $this->makeUrl($this->thispage, 'action=unwatch'),
						'ttip' => wfMsg('tooltip-unwatch'),
						'akey' => wfMsg('accesskey-unwatch'));

					}
				}
			} else {
				/* show special page tab */

				$content_actions['article'] = array('class' => 'selected',
				'text' => wfMsg('specialpage'),
				'href' => false,
				'ttip' => wfMsg('tooltip-specialpage'),
				'akey' => false);
			}

			return $content_actions;
		}

		# build array of common navigation links
		function buildNavUrls () {
			global $wgTitle, $wgUser, $wgRequest;
			global $wgSiteSupportPage;
			
			$action = $wgRequest->getText( 'action' );
			$oldid = $wgRequest->getVal( 'oldid' );
			$diff = $wgRequest->getVal( 'diff' );

			$nav_urls = array();
			$nav_urls['mainpage'] = array('href' => htmlspecialchars( $this->makeI18nUrl('mainpage')));
			$nav_urls['randompage'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Randompage')));
			$nav_urls['recentchanges'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchanges')));
			$nav_urls['whatlinkshere'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Whatlinkshere', 'target='.$this->thispage)));
			if(wfMsg('currentevents') != '-') {
				$nav_urls['currentevents'] = array('href' => htmlspecialchars( $this->makeI18nUrl('currentevents')));
			}
			$nav_urls['recentchangeslinked'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchangeslinked', 'target='.$this->thispage)));
			$nav_urls['bugreports'] = array('href' => htmlspecialchars( $this->makeI18nUrl('bugreportspage')));
			// $nav_urls['sitesupport'] = array('href' => htmlspecialchars( $this->makeI18nUrl('sitesupportpage')));
			$nav_urls['sitesupport'] = array('href' => htmlspecialchars( $wgSiteSupportPage));
			$nav_urls['help'] = array('href' => htmlspecialchars( $this->makeI18nUrl('helppage')));
			$nav_urls['upload'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Upload')));
			$nav_urls['specialpages'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Specialpages')));

			return $nav_urls;
		}

		/*static*/ function makeSpecialUrl( $name, $urlaction='' ) {
			$title = Title::makeTitle( NS_SPECIAL, $name );
			$this->checkTitle(&$title, &$name);	
			return $title->getLocalURL( $urlaction );
		}
		/*static*/ function makeTalkUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			$title = $title->getTalkPage();
			$this->checkTitle(&$title, &$name);	
			return $title->getLocalURL( $urlaction );
		}
		/*static*/ function makeArticleUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			$title= $title->getSubjectPage();
			$this->checkTitle(&$title, &$name);	
			return $title->getLocalURL( $urlaction );
		}
		/*static*/ function makeI18nUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( wfMsg($name) );
			$this->checkTitle(&$title, &$name);	
			return $title->getLocalURL( $urlaction );
		}
		/*static*/ function makeUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			$this->checkTitle(&$title, &$name);	
			return $title->getLocalURL( $urlaction ); 
		}

		# make sure we have some title to operate on, mind the '&'
		/*static*/ function checkTitle ( &$title, &$name ) { 
			if(!is_object($title)) {
				$title = Title::newFromText( $name );
				if(!is_object($title)) {
					$title = Title::newFromText( '<error: link target missing>' );
				}
			}
		}
			

	}

	class SkinDaVinci extends SkinPHPTal {
		function initPage() {
			SkinPHPTal::initPage();
			$this->skinname = "davinci";
			$this->template = "xhtml_slim";
		}
	}
	
	class SkinMono extends SkinPHPTal {
		function initPage() {
			SkinPHPTal::initPage();
			$this->skinname = "mono";
			$this->template = "xhtml_slim";
		}
	}
	
	class SkinMonoBook extends SkinPHPTal {
		function initPage() {
			SkinPHPTal::initPage();
			$this->skinname = "monobook";
			$this->template = "xhtml_slim";
		}
	}
	
?>
