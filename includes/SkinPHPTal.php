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

if( defined( "MEDIAWIKI" ) ) {
	require_once "GlobalFunctions.php";
	global $IP;
	require_once $IP."/PHPTAL-NP-0.7.0/libs/PHPTAL.php";

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
				wfSuppressWarnings();
				$varValue = $this->_context[$var];
				wfRestoreWarnings();
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
			global $wgDisableCounters, $wgLogo, $action, $wgFeedClasses, $wgSiteNotice;
		        global $wgMaxCredits, $wgShowCreditsIfMax;

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
			$this->userpageUrlDetails = $this->makeUrlDetails($this->userpage);

			$this->usercss =  $this->userjs = $this->userjsprev = false;
			$this->setupUserCssJs();

			$this->titletxt = $wgTitle->getPrefixedText();

			$tpl->set( "title", $wgOut->getPageTitle() );
			$tpl->set( "pagetitle", $wgOut->getHTMLTitle() );

			$tpl->setRef( "thispage", $this->thispage );
			$subpagestr = $this->subPageSubtitle();
			$tpl->set(
				"subtitle",  !empty($subpagestr)?
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
			}
			$tpl->setRef( 'mimetype', $wgMimeType );
			$tpl->setRef( 'charset', $wgOutputEncoding );
			$tpl->set( 'headlinks', $out->getHeadLinks() );
			$tpl->setRef( 'skinname', $this->skinname );
			$tpl->setRef( "loggedin", $this->loggedin );
			$tpl->set('nsclass', 'ns-'.$wgTitle->getNamespace());
			/* XXX currently unused, might get useful later
			$tpl->set( "editable", ($wgTitle->getNamespace() != NS_SPECIAL ) );
			$tpl->set( "exists", $wgTitle->getArticleID() != 0 );
			$tpl->set( "watch", $wgTitle->userIsWatching() ? "unwatch" : "watch" );
			$tpl->set( "protect", count($wgTitle->getRestrictions()) ? "unprotect" : "protect" );
			$tpl->set( "helppage", wfMsg('helppage'));
			$tpl->set( "sysop", $wgUser->isSysop() );
			*/
			$tpl->set( "searchaction", $this->escapeSearchLink() );
			$tpl->setRef( "stylepath", $wgStylePath );
			$tpl->setRef( "logopath", $wgLogo );
			$tpl->setRef( "lang", $wgLanguageCode );
			$tpl->set( "dir", $wgLang->isRTL() ? "rtl" : "ltr" );
			$tpl->set( "rtl", $wgLang->isRTL() );
			$tpl->set( "langname", $wgLang->getLanguageName( $wgLanguageCode ) );
			$tpl->setRef( "username", $this->username );
			$tpl->setRef( "userpage", $this->userpage);
			$tpl->setRef( "userpageurl", $this->userpageUrlDetails['href']);
			$tpl->setRef( "usercss", $this->usercss);
			$tpl->setRef( "userjs", $this->userjs);
			$tpl->setRef( "userjsprev", $this->userjsprev);
			if($this->loggedin) {
				$tpl->set( "jsvarurl", $this->makeUrl('-','action=raw&smaxage=0&gen=js') );
			} else {
				$tpl->set( "jsvarurl", $this->makeUrl('-','action=raw&gen=js') );
			}
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

			$tpl->setRef( "newtalk", $ntl );
			$tpl->setRef( "skin", $this);
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

			        $this->credits = false;

			        if (isset($wgMaxCredits) && $wgMaxCredits != 0) {
   			            require_once("Credits.php");
			            $this->credits = getCredits($wgArticle, $wgMaxCredits, $wgShowCreditsIfMax);
			        }

 		                $tpl->setRef( "credits", $this->credits );

			} elseif ( isset( $oldid ) && !isset( $diff ) ) {
				$tpl->set('copyright', $this->getCopyright());
			}

			$tpl->set( "copyrightico", $this->getCopyrightIcon() );
			$tpl->set( "poweredbyico", $this->getPoweredBy() );
			$tpl->set( "disclaimer", $this->disclaimerLink() );
			$tpl->set( "about", $this->aboutLink() );

			$tpl->setRef( "debug", $out->mDebugtext );
			$tpl->set( "reporttime", $out->reportTime() );
			$tpl->set( "sitenotice", $wgSiteNotice );

			$printfooter = "<div class=\"printfooter\">\n" . $this->printSource() . "</div>\n";
			$out->mBodytext .= $printfooter ;
			$tpl->setRef( "bodytext", $out->mBodytext );

			$language_urls = array();
			foreach( $wgOut->getLanguageLinks() as $l ) {
				$nt = Title::newFromText( $l );
				$language_urls[] = array('href' => $nt->getFullURL(),
				'text' => ($wgLang->getLanguageName( $nt->getInterwiki()) != ''?$wgLang->getLanguageName( $nt->getInterwiki()) : $l),
				'class' => $wgLang->isRTL() ? 'rtl' : 'ltr');
			}
			if(count($language_urls)) {
				$tpl->setRef( 'language_urls', $language_urls);
			} else {
				$tpl->set('language_urls', false);
			}
			$tpl->set('personal_urls', $this->buildPersonalUrls());
			$content_actions = $this->buildContentActionUrls();
			$tpl->setRef('content_actions', $content_actions);
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

			return $personal_urls;
		}

		# an array of edit links by default used for the tabs
		function buildContentActionUrls () {
			global $wgTitle, $wgUser, $wgRequest, $wgUseValidation;
			$action = $wgRequest->getText( 'action' );
			$section = $wgRequest->getText( 'section' );
			$oldid = $wgRequest->getVal( 'oldid' );
			$diff = $wgRequest->getVal( 'diff' );
			$content_actions = array();

			if( $this->iscontent ) {

				$nskey = $this->getNameSpaceKey();
				$is_active = !Namespace::isTalk( $wgTitle->getNamespace()) ;
				if ( $action == 'validate' ) $is_active = false ; # Show article tab deselected when validating
				$content_actions[$nskey] = array('class' => ($is_active) ? 'selected' : false,
				'text' => wfMsg($nskey),
				'href' => $this->makeArticleUrl($this->thispage));

				/* set up the classes for the talk link */
				$talk_class = (Namespace::isTalk( $wgTitle->getNamespace()) ? 'selected' : false);
				$talktitle = Title::newFromText( $this->titletxt );
				$talktitle = $talktitle->getTalkPage();
				$this->checkTitle($talktitle, $this->titletxt);
				if($talktitle->getArticleId() != 0) {
					$content_actions['talk'] = array(
						'class' => $talk_class,
						'text' => wfMsg('talk'),
						'href' => $this->makeTalkUrl($this->titletxt)
					);
				} else {
					$content_actions['talk'] = array(
						'class' => $talk_class?$talk_class.' new':'new',
						'text' => wfMsg('talk'),
						'href' => $this->makeTalkUrl($this->titletxt,'action=edit')
					);
				}

				if ( $wgTitle->userCanEdit() ) {
					$oid = ( $oldid && ! isset( $diff ) ) ? "&oldid={$oldid}" : false;
					$istalk = ( Namespace::isTalk( $wgTitle->getNamespace()) );
					$istalkclass = $istalk?' istalk':'';
					$content_actions['edit'] = array(
						'class' => ((($action == 'edit' or $action == 'submit') and $section != 'new') ? 'selected' : '').$istalkclass,
						'text' => wfMsg('edit'),
						'href' => $this->makeUrl($this->thispage, 'action=edit'.$oid)
					);
					if ( $istalk ) {
						$content_actions['addsection'] = array(
							'class' => $section == 'new'?'selected':false,
							'text' => wfMsg('addsection'),
							'href' => $this->makeUrl($this->thispage, 'action=edit&section=new')
						);
					}
				} else {
				        $oid = ( $oldid && ! isset( $diff ) ) ? "&oldid={$oldid}" : '';
					$content_actions['viewsource'] = array('class' => ($action == 'edit') ? 'selected' : false,
					'text' => wfMsg('viewsource'),
					'href' => $this->makeUrl($this->thispage, 'action=edit'.$oid));
				}

				if ( $wgTitle->getArticleId() ) {

					$content_actions['history'] = array('class' => ($action == 'history') ? 'selected' : false,
					'text' => wfMsg('history_short'),
					'href' => $this->makeUrl($this->thispage, 'action=history'));

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
								'href' => $this->makeUrl($this->thispage, 'action=protect')
							);

						} else {
							$content_actions['unprotect'] = array(
								'class' => ($action == 'unprotect') ? 'selected' : false,
								'text' => wfMsg('unprotect'),
								'href' => $this->makeUrl($this->thispage, 'action=unprotect')
							);
						}
						$content_actions['delete'] = array(
							'class' => ($action == 'delete') ? 'selected' : false,
							'text' => wfMsg('delete'),
							'href' => $this->makeUrl($this->thispage, 'action=delete')
						);
					}
					if ( $wgUser->getID() != 0 ) {
						if ( $wgTitle->userCanEdit()) {
							$content_actions['move'] = array('class' => ($wgTitle->getDbKey() == 'Movepage' and $wgTitle->getNamespace == Namespace::getSpecial()) ? 'selected' : false,
							'text' => wfMsg('move'),
							'href' => $this->makeSpecialUrl('Movepage', 'target='. urlencode( $this->thispage ))
						);
						} else {
							$content_actions['move'] = array('class' => 'inactive',
							'text' => wfMsg('move'),
							'href' => false);

						}
					}
				} else {
					//article doesn't exist or is deleted
					if($wgUser->isSysop()){
						if( $n = $wgTitle->isDeleted() ) {
							$content_actions['delete'] = array(
								'class' => false,
								'text' => wfMsg( "undelete_short", $n ),
								'href' => $this->makeSpecialUrl('Undelete/'.$this->thispage)
							);
						}
					}
				}

				if ( $wgUser->getID() != 0 and $action != 'edit' and $action != 'submit' ) {
					if( !$wgTitle->userIsWatching()) {
						$content_actions['watch'] = array('class' => ($action == 'watch' or $action == 'unwatch') ? 'selected' : false,
						'text' => wfMsg('watch'),
						'href' => $this->makeUrl($this->thispage, 'action=watch'));
					} else {
						$content_actions['unwatch'] = array('class' => ($action == 'unwatch' or $action == 'watch') ? 'selected' : false,
						'text' => wfMsg('unwatch'),
						'href' => $this->makeUrl($this->thispage, 'action=unwatch'));
					}
				}

				# Show validate tab
				if ( $wgUseValidation && $wgTitle->getArticleId() && $wgTitle->getNamespace() == 0 ) {
					global $wgArticle ;
					$article_time = "&timestamp=" . $wgArticle->mTimestamp ;
					$content_actions['validate'] = array('class' => ($action == 'validate') ? 'selected' : false ,
						'text' => wfMsg('val_tab'),
						'href' => $this->makeUrl($this->thispage, "action=validate{$article_time}"));
					}

			} else {
				/* show special page tab */

				$content_actions['article'] = array('class' => 'selected',
				'text' => wfMsg('specialpage'),
				'href' => false);
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
			$nav_urls['whatlinkshere'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Whatlinkshere', 'target='.urlencode( $this->thispage ))));
			$nav_urls['currentevents'] = (wfMsg('currentevents') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('currentevents'))) : false;
			$nav_urls['portal'] = (wfMsg('portal') != '-') ? array('href' => htmlspecialchars( $this->makeI18nUrl('portal-url'))) : false;
			$nav_urls['recentchangeslinked'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Recentchangeslinked', 'target='.urlencode( $this->thispage ))));
			$nav_urls['bugreports'] = array('href' => htmlspecialchars( $this->makeI18nUrl('bugreportspage')));
			// $nav_urls['sitesupport'] = array('href' => htmlspecialchars( $this->makeI18nUrl('sitesupportpage')));
			$nav_urls['sitesupport'] = array('href' => htmlspecialchars( $wgSiteSupportPage));
			$nav_urls['help'] = array('href' => htmlspecialchars( $this->makeI18nUrl('helppage')));
			$nav_urls['upload'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Upload')));
			$nav_urls['specialpages'] = array('href' => htmlspecialchars( $this->makeSpecialUrl('Specialpages')));

			if( $wgTitle->getNamespace() == NS_USER || $wgTitle->getNamespace() == NS_USER_TALK ) {
				$id = User::idFromName($wgTitle->getText());
				$ip = User::isIP($wgTitle->getText());
			} else {
				$id = 0;
				$ip = false;
			}

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
				case NS_WP:
				case NS_WP_TALK:
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
		/* private */ function setupUserCssJs () {
			global $wgRequest, $wgTitle;
			$action = $wgRequest->getText('action');
			# generated css
			$this->usercss = '@import "'.$this->makeUrl('-','action=raw&gen=css').'";'."\n";

			if( $this->loggedin ) {
				if($wgTitle->isCssSubpage() and $action == 'submit' and  $wgTitle->userCanEditCssJsSubpage()) {
					# generated css
					$this->usercss = '@import "'.$this->makeUrl('-','action=raw&smaxage=0&maxage=0&gen=css').'";'."\n";
					// css preview
					$this->usercss .= $wgRequest->getText('wpTextbox1');
				} else {
					# generated css
					$this->usercss .= '@import "'.$this->makeUrl('-','action=raw&smaxage=0&gen=css').'";'."\n";
					# import user stylesheet
					$this->usercss .= '@import "'.
					$this->makeUrl($this->userpage.'/'.$this->skinname.'.css', 'action=raw&ctype=text/css').'";'."\n";
				}
				if($wgTitle->isJsSubpage() and $action == 'submit' and  $wgTitle->userCanEditCssJsSubpage()) {
					# XXX: additional security check/prompt?
					$this->userjsprev = $wgRequest->getText('wpTextbox1');
				} else {
					$this->userjs = $this->makeUrl($this->userpage.'/'.$this->skinname.'.js', 'action=raw&ctype=text/javascript&dontcountme=s');
				}
			}
		}
		# returns css with user-specific options
		function getUserStylesheet() {
			global $wgUser, $wgRequest, $wgTitle, $wgLang, $wgSquidMaxage, $wgStylePath;
			$action = $wgRequest->getText('action');
			$maxage = $wgRequest->getText('maxage');
			$s = "/* generated user stylesheet */\n";
			if($wgLang->isRTL()) $s .= '@import "'.$wgStylePath.'/'.$this->skinname.'/rtl.css";'."\n";
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
			return $s;
		}
		function getUserJs() {
			global $wgUser, $wgStylePath;
			$s = "/* generated javascript */";
			$s .= "var skin = '{$this->skinname}';\nvar stylepath = '{$wgStylePath}';";
			$s .= '/* MediaWiki:'.ucfirst($this->skinname)." */\n";
			$s .= wfMsg(ucfirst($this->skinname).'.js');
			return $s;
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

	class SkinMySkin extends SkinPHPTal {
		function initPage( &$out ) {
			SkinPHPTal::initPage( $out );
			$this->skinname = "myskin";
		}
	}
}
?>
