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

		function set($varName, $value) {
			$this->_context[$varName] = $value;
		}

		function translate($value) {
			$value = wfMsg( $value );
			// interpolate variables
			while (preg_match('/\$([0-9]*?)/sm', $value, $m)) {
				list($src, $var) = $m;
				$varValue = @$this->_context[$var];
				$value = str_replace($src, $varValue, $value);
			}
			return $value;
		}
	}

	class SkinPHPTal extends Skin {
		var $template;

		function initPage( &$out ) {
			parent::initPage( $out );
			$this->skinname = "davinci";
			$this->template = "xhtml_slim";
		}

		function outputPage( &$out ) {
			global $wgTitle, $wgArticle, $wgUser, $wgLang, $wgOut;
			global $wgScript, $wgStylePath, $wgLanguageCode, $wgUseNewInterlanguage;
			global $wgMimeType, $wgOutputEncoding, $wgUseDatabaseMessages, $wgRequest;
			global $wgDisableCounters, $wgLogo, $action, $wgFeedClasses;
			
			extract( $wgRequest->getValues( 'oldid', 'diff' ) );

			$this->initPage( $out );
			$tpl = new PHPTAL($this->template . '.pt', 'templates');
			
			#if ( $wgUseDatabaseMessages ) { // uncomment this to fall back to GetText
			$tpl->setTranslator(new MediaWiki_I18N());
			#}
			
			$this->thispage = $wgTitle->getPrefixedDbKey();
			$this->thisurl = $wgTitle->getPrefixedURL();
			$this->loggedin = $wgUser->getID() != 0;
			$this->iscontent = ($wgTitle->getNamespace() != Namespace::getSpecial() );
			$this->iseditable = ($this->iscontent and !($action == 'edit' or $action == 'submit'));
			$this->username = $wgUser->getName();
			$this->userpage = $wgLang->getNsText( Namespace::getUser() ) . ":" . $wgUser->getName();
			$this->userpageurl = $this->makeUrl($this->userpage);
			
			if( $this->loggedin ) {
				$this->usercss = $this->makeUrl($this->userpage.'/'.$this->skinname.'.css', 'action=raw&ctype=text/css');
				$this->userjs = $this->makeUrl($this->userpage.'/'.$this->skinname.'.js', 'action=raw&ctype=text/javascript');
				$this->userjse = htmlspecialchars($this->userjs);
			} else {
				$this->usercss =  $this->userjs = $this->userjse = false;
			}
			$this->titletxt = $wgTitle->getPrefixedText();
			
			$tpl->set( "title", $wgOut->getPageTitle() );
			$tpl->set( "pagetitle", $wgOut->getHTMLTitle() );
			
			$tpl->setRef( "thispage", &$this->thispage );
			$subpagestr = $this->subPageSubtitle();
			$tpl->set( 
				"subtitle",  !empty($subpagestr)?
				'<span class="subpages">'.$subpagestr.'</span>'.$out->getSubtitle():
				$out->getSubtitle()  
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
				$tpl->setRef( 'feeds', &$feeds );
			}
			$tpl->setRef( 'mimetype', &$wgMimeType );
			$tpl->setRef( 'charset', &$wgOutputEncoding );
			$tpl->set( 'headlinks', $out->getHeadLinks() );
			$tpl->setRef( 'skinname', &$this->skinname );
			$tpl->setRef( "loggedin", &$this->loggedin );
			/* XXX currently unused, might get useful later
			$tpl->set( "editable", ($wgTitle->getNamespace() != NS_SPECIAL ) );
			$tpl->set( "exists", $wgTitle->getArticleID() != 0 );
			$tpl->set( "watch", $wgTitle->userIsWatching() ? "unwatch" : "watch" );
			$tpl->set( "protect", count($wgTitle->getRestrictions()) ? "unprotect" : "protect" );
			$tpl->set( "helppage", wfMsg('helppage'));
			$tpl->set( "sysop", $wgUser->isSysop() );
			*/
			$tpl->set( "searchaction", $this->escapeSearchLink() );
			$tpl->setRef( "stylepath", &$wgStylePath );
			$tpl->setRef( "logopath", &$wgLogo );
			$tpl->setRef( "lang", &$wgLanguageCode );
			$tpl->set( "dir", $wgLang->isRTL() ? "rtl" : "ltr" );
			$tpl->set( "rtl", $wgLang->isRTL() );
			$tpl->set( "langname", $wgLang->getLanguageName( $wgLanguageCode ) );
			$tpl->setRef( "username", &$this->username );
			$tpl->setRef( "userpage", &$this->userpage);
			$tpl->setRef( "userpageurl", &$this->userpageurl);
			$tpl->setRef( "usercss", &$this->usercss);
			$tpl->setRef( "userjs", &$this->userjs);
			$tpl->setRef( "userjse", &$this->userjse);
			if( $wgUser->getNewtalk() ) {
				$usertitle = Title::newFromText( $this->userpage );
				$usertalktitle = $usertitle->getTalkPage();
				if($usertalktitle->getPrefixedDbKey() != $this->thispage){
					
					$ntl = wfMsg( "newmessages",
					$this->makeKnownLink( 
						$wgLang->getNsText( Namespace::getTalk( Namespace::getUser() ) )
						. ":" . $this->username,
						wfMsg("newmessageslink") ) 
					);
				}
			} else {
				$ntl = "";
			}

			$tpl->setRef( "newtalk", &$ntl );
			$tpl->setRef( "skin", &$this);
			$tpl->set( "logo", $this->logoText() );
			if ( $wgOut->isArticle() and (!isset( $oldid ) or isset( $diff )) and 0 != $wgArticle->getID() ) {
				if ( !$wgDisableCounters ) {
					$viewcount = $wgLang->formatNum( $wgArticle->getCount() );
					if ( $viewcount ) {
						$tpl->set('viewcount', wfMsg( "viewcount", $viewcount ));
					}
				}
				$tpl->set('lastmod', $this->lastModified());
			        $tpl->set('copyright',$this->getCopyright());
			}
			$tpl->set( "copyrightico", $this->getCopyrightIcon() );
			$tpl->set( "poweredbyico", $this->getPoweredBy() );
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
			if(count($language_urls)) {
				$tpl->setRef( 'language_urls', &$language_urls);
			} else {
				$tpl->set('language_urls', false);
			}
			$tpl->set('personal_urls', $this->buildPersonalUrls());
			$content_actions = $this->buildContentActionUrls();
			$tpl->setRef('content_actions', &$content_actions);
			// XXX: attach this from javascript, same with section editing
			if($this->iseditable &&	$wgUser->getOption("editondblclick") ) 
			{
				$tpl->set('body-ondblclick', 'document.location = "' .$content_actions['edit']['href'] .'";');
			} else {
				$tpl->set('body-ondblclick', false);
			}
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
			global $wgShowIPinHeader;
			$personal_urls = array();
			if ($this->loggedin) {
				$personal_urls['userpage'] = array(
					'text' => $this->username,
					'href' => &$this->userpageurl,
					'ttip' => wfMsg('tooltip-userpage'),
					'akey' => wfMsg('accesskey-userpage')
				);
				$personal_urls['mytalk'] = array(
					'text' => wfMsg('mytalk'),
					'href' => $this->makeTalkUrl($this->userpage),
					'ttip' => wfMsg('tooltip-mytalk'),
					'akey' => wfMsg('accesskey-mytalk')
				);
				$personal_urls['preferences'] = array(
					'text' => wfMsg('preferences'),
					'href' => $this->makeSpecialUrl('Preferences'),
					'ttip' => wfMsg('tooltip-preferences'),
					'akey' => wfMsg('accesskey-preferences')
				);
				$personal_urls['watchlist'] = array(
					'text' => wfMsg('watchlist'),
					'href' => $this->makeSpecialUrl('Watchlist'),
					'ttip' => wfMsg('tooltip-watchlist'),
					'akey' => wfMsg('accesskey-watchlist')
				);
				$personal_urls['mycontris'] = array(
					'text' => wfMsg('mycontris'),
					'href' => $this->makeSpecialUrl('Contributions','target=' . $this->username),
					'ttip' => wfMsg('tooltip-mycontris'),
					'akey' => wfMsg('accesskey-mycontris')
				);
				$personal_urls['logout'] = array(
					'text' => wfMsg('userlogout'),
					'href' => $this->makeSpecialUrl('Userlogout','returnto=' . $this->thisurl),
					'ttip' => wfMsg('tooltip-logout'),
					'akey' => wfMsg('accesskey-logout')
				);
			} else {
				if( $wgShowIPinHeader && isset(  $_COOKIE[ini_get("session.name")] ) ) {
					$personal_urls['anonuserpage'] = array(
						'text' => $this->username,
						'href' => $this->makeUrl($this->userpage),
						'ttip' => wfMsg('tooltip-anonuserpage'),
						'akey' => wfMsg('accesskey-anonuserpage')
					);
					$personal_urls['anontalk'] = array(
						'text' => wfMsg('anontalk'),
						'href' => $this->makeTalkUrl($this->userpage),
						'ttip' => wfMsg('tooltip-anontalk'),
						'akey' => wfMsg('accesskey-anontalk')
					);
					$personal_urls['anonlogin'] = array(
						'text' => wfMsg('userlogin'),
						'href' => $this->makeSpecialUrl('Userlogin', 'returnto='.$this->thisurl),
						'ttip' => wfMsg('tooltip-login'),
						'akey' => wfMsg('accesskey-login')
					);
				} else {

					$personal_urls['login'] = array(
						'text' => wfMsg('userlogin'),
						'href' => $this->makeSpecialUrl('Userlogin', 'returnto='.$this->thisurl),
						'ttip' => wfMsg('tooltip-login'),
						'akey' => wfMsg('accesskey-login')
					);
				}
			}

			return $personal_urls;
		}
		
		# an array of edit links by default used for the tabs
		function buildContentActionUrls () {
			global $wgTitle, $wgUser, $wgRequest;
			$action = $wgRequest->getText( 'action' );
			$section = $wgRequest->getText( 'section' );
			$oldid = $wgRequest->getVal( 'oldid' );
			$diff = $wgRequest->getVal( 'diff' );
			$content_actions = array();
			
			if( $this->iscontent ) {

				$content_actions['article'] = array('class' => (!Namespace::isTalk( $wgTitle->getNamespace())) ? 'selected' : false,
				'text' => $this->getNameSpaceWord(),
				'href' => $this->makeArticleUrl($this->thispage),
				'ttip' => wfMsg('tooltip-article'),
				'akey' => wfMsg('accesskey-article'));

				/* set up the classes for the talk link */
				$talk_class = (Namespace::isTalk( $wgTitle->getNamespace()) ? 'selected' : false);				
				$talktitle = Title::newFromText( $this->titletxt );
				$talktitle = $talktitle->getTalkPage();
				$this->checkTitle(&$talktitle, &$this->titletxt);	
				if($talktitle->getArticleId() != 0) { 
					$content_actions['talk'] = array(
						'class' => $talk_class,
						'text' => wfMsg('talk'),
						'href' => $this->makeTalkUrl($this->titletxt),
						'ttip' => wfMsg('tooltip-talk'),
						'akey' => wfMsg('accesskey-talk')
					);
				} else {
					$content_actions['talk'] = array(
						'class' => $talk_class?$talk_class.' new':'new',
						'text' => wfMsg('talk'),
						'href' => $this->makeTalkUrl($this->titletxt,'action=edit'),
						'ttip' => wfMsg('tooltip-talk'),
						'akey' => wfMsg('accesskey-talk')
					);
				}

				if ( $wgTitle->userCanEdit() ) {
					$oid = ( $oldid && ! isset( $diff ) ) ? "&oldid={$oldid}" : false;
					$istalk = ( Namespace::isTalk( $wgTitle->getNamespace()) );
					$istalkclass = $istalk?' istalk':'';
					$content_actions['edit'] = array(
						'class' => ((($action == 'edit' or $action == 'submit') and $section != 'new') ? 'selected' : '').$istalkclass,
						'text' => wfMsg('edit'),
						'href' => $this->makeUrl($this->thispage, 'action=edit'.$oid),
						'ttip' => wfMsg('tooltip-edit'),
						'akey' => wfMsg('accesskey-edit')
					);
					if ( $istalk ) {
						$content_actions['addsection'] = array(
							'class' => $section == 'new'?'selected':false,
							'text' => wfMsg('addsection'),
							'href' => $this->makeUrl($this->thispage, 'action=edit&section=new'),
							'ttip' => wfMsg('tooltip-addsection'),
							'akey' => wfMsg('accesskey-addsection')
						);
					}
				} else {
				        $oid = ( $oldid && ! isset( $diff ) ) ? "&oldid={$oldid}" : '';
					$content_actions['edit'] = array('class' => ($action == 'edit') ? 'selected' : false,
					'text' => wfMsg('viewsource'),
					'href' => $this->makeUrl($this->thispage, 'action=edit'.$oid),
					'ttip' => wfMsg('tooltip-viewsource'),
					'akey' => wfMsg('accesskey-viewsource'));
				}

				if ( $wgTitle->getArticleId() ) {

					$content_actions['history'] = array('class' => ($action == 'history') ? 'selected' : false,
					'text' => wfMsg('history_short'),
					'href' => $this->makeUrl($this->thispage, 'action=history'),
					'ttip' => wfMsg('tooltip-history'),
					'akey' => wfMsg('accesskey-history'));

					# XXX: is there a rollback action anywhere or is it planned?
					# Don't recall where i got this from...
					/*if( $wgUser->getNewtalk() ) {
						$content_actions['rollback'] = array('class' => ($action == 'rollback') ? 'selected' : false,
						'text' => wfMsg('rollback_short'),
						'href' => $this->makeUrl($this->thispage, 'action=rollback'),
						'ttip' => wfMsg('tooltip-rollback'),
						'akey' => wfMsg('accesskey-rollback'));
					}*/

					if($wgUser->isSysop()){
						if(!$wgTitle->isProtected()){
							$content_actions['protect'] = array(
								'class' => ($action == 'protect') ? 'selected' : false,
								'text' => wfMsg('protect'),
								'href' => $this->makeUrl($this->thispage, 'action=protect'),
								'ttip' => wfMsg('tooltip-protect'),
								'akey' => wfMsg('accesskey-protect')
							);

						} else {
							$content_actions['unprotect'] = array(
								'class' => ($action == 'unprotect') ? 'selected' : false,
								'text' => wfMsg('unprotect'),
								'href' => $this->makeUrl($this->thispage, 'action=unprotect'),
								'ttip' => wfMsg('tooltip-protect'),
								'akey' => wfMsg('accesskey-protect')
							);
						}
						$content_actions['delete'] = array(
							'class' => ($action == 'delete') ? 'selected' : false,
							'text' => wfMsg('delete'),
							'href' => $this->makeUrl($this->thispage, 'action=delete'),
							'ttip' => wfMsg('tooltip-delete'),
							'akey' => wfMsg('accesskey-delete')
						);
					}
					if ( $wgUser->getID() != 0 ) {
						if ( $wgTitle->userCanEdit()) {
							$content_actions['move'] = array('class' => ($wgTitle->getDbKey() == 'Movepage' and $wgTitle->getNamespace == Namespace::getSpecial()) ? 'selected' : false,
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
				} else { 
					//article doesn't exist or is deleted
					if($wgUser->isSysop()){
						if( $n = $wgTitle->isDeleted() ) {
							$content_actions['delete'] = array(
								'class' => false,
								'text' => wfMsg( "undelete_short", $n ),
								'href' => $this->makeSpecialUrl('Undelete/'.$this->thispage),
								'ttip' => wfMsg('tooltip-undelete', $n),
								'akey' => wfMsg('accesskey-undelete')
							);
						}
					}
				}

				if ( $wgUser->getID() != 0 and $action != 'edit' and $action != 'submit' ) {
					if( !$wgTitle->userIsWatching()) {
						$content_actions['watch'] = array('class' => ($action == 'watch' or $action == 'unwatch') ? 'selected' : false,
						'text' => wfMsg('watch'),
						'href' => $this->makeUrl($this->thispage, 'action=watch'),
						'ttip' => wfMsg('tooltip-watch'),
						'akey' => wfMsg('accesskey-watch'));
					} else {
						$content_actions['watch'] = array('class' => ($action == 'unwatch' or $action == 'watch') ? 'selected' : false,
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
			// XXX: remove htmlspecialchars when tal:attributes works with i18n:attributes
			$nav_urls = array();
			$nav_urls['mainpage'] = array('href' => htmlspecialchars( $this->makeI18nUrl('mainpage')));
			$nav_urls['randompage'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Randompage')));
			$nav_urls['recentchanges'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchanges')));
			$nav_urls['whatlinkshere'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Whatlinkshere', 'target='.$this->thispage)));
			$nav_urls['currentevents'] = (wfMsg('currentevents') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('currentevents'))) : false;
			$nav_urls['portal'] = (wfMsg('portal') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('portal-url'))) : false;
			$nav_urls['recentchangeslinked'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchangeslinked', 'target='.$this->thispage)));
			$nav_urls['bugreports'] = array('href' => htmlspecialchars( $this->makeI18nUrl('bugreportspage')));
			// $nav_urls['sitesupport'] = array('href' => htmlspecialchars( $this->makeI18nUrl('sitesupportpage')));
			$nav_urls['sitesupport'] = array('href' => htmlspecialchars( $wgSiteSupportPage));
			$nav_urls['help'] = array('href' => htmlspecialchars( $this->makeI18nUrl('helppage')));
			$nav_urls['upload'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Upload')));
			$nav_urls['specialpages'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Specialpages')));
			
			
			$id=User::idFromName($wgTitle->getText());
			$ip=User::isIP($wgTitle->getText());

			if($id || $ip) { # both anons and non-anons have contri list
				$nav_urls['contributions'] = array(
					'href' => htmlspecialchars( $this->makeSpecialUrl('Contributions', "target=" . $wgTitle->getPartialURL() ) )
				);
			}
			if ( 0 != $wgUser->getID() ) { # show only to signed in users
				if($id) {	# can only email non-anons
					$nav_urls['emailuser'] = array(
						'href' => htmlspecialchars( $this->makeSpecialUrl('Emailuser', "target=" . $wgTitle->getPartialURL() ) )
					);
				}
			}


			return $nav_urls;
		}

		function getNameSpaceWord () {
			global $wgTitle;
			switch ($wgTitle->getNamespace()) { 
				case NS_MAIN:
				case NS_TALK:
					return wfMsg('nstab-main');
				case NS_USER:
				case NS_USER_TALK:
					return wfMsg('nstab-user');
				case NS_MEDIA:
					return wfMsg('nstab-media');
				case NS_SPECIAL:
					return wfMsg('nstab-special');
				case NS_WP:
				case NS_WP_TALK:
					return wfMsg('nstab-wp');
				case NS_IMAGE:
				case NS_IMAGE_TALK:
					return wfMsg('nstab-image');
				case NS_MEDIAWIKI:
				case NS_MEDIAWIKI_TALK:
					return wfMsg('nstab-mediawiki');
				case NS_TEMPLATE:
				case NS_TEMPLATE_TALK:
					return wfMsg('nstab-template');
				case NS_HELP:
				case NS_HELP_TALK:
					return wfMsg('nstab-help');
				case NS_CATEGORY:
				case NS_CATEGORY_TALK:
					return wfMsg('nstab-category');
				default:
					return wfMsg('nstab-main');
			}
		}
	}

	class SkinDaVinci extends SkinPHPTal {
		function initPage( &$out ) {
			SkinPHPTal::initPage( $out );
			$this->skinname = "davinci";
		}
	}
	
	class SkinMono extends SkinPHPTal {
		function initPage( &$out ) {
			SkinPHPTal::initPage( $out );
			$this->skinname = "mono";
		}
	}
	
	class SkinMonoBook extends SkinPHPTal {
		function initPage( &$out ) {
			SkinPHPTal::initPage( $out );
			$this->skinname = "monobook";
		}
	}
	
?>
