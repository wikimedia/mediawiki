<?php
	# And turn on $wgUsePHPTal so this file gets included

	# Set your include_path so the PHPTal dir is available

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
			global $wgOutputEncoding, $wgUseDatabaseMessages, $action, $oldid, $diff;

			$this->initPage();
			$tpl = new PHPTAL($this->template . '.pt', 'templates');
			#if ( $wgUseDatabaseMessages ) { // uncomment this to fall back to GetText

			$tpl->setTranslator(new MediaWiki_I18N());
			#}

			$title = $wgTitle->getPrefixedText();
			$tpl->setRef( "title", &$title ); // ?
			$thispage = $wgTitle->getPrefixedDbKey();
			$tpl->setRef( "thispage", &$thispage );
			$tpl->set( "subtitle", $out->getSubtitle() );
			$tpl->setRef( 'charset', $wgOutputEncoding);
			$tpl->setRef( 'skinname', $this->skinname );

			$loggedin = $wgUser->getID() != 0;
			$tpl->setRef( "loggedin", &$loggedin );
			$tpl->set( "editable", ($wgTitle->getNamespace != Namespace::getSpecial() ) );
			$tpl->set( "exists", $wgTitle->getArticleID() != 0 );
			$tpl->set( "watch", $wgTitle->userIsWatching() ? "unwatch" : "watch" );
			$tpl->set( "protect", count($wgTitle->getRestrictions()) ? "unprotect" : "protect" );
			$tpl->set( "helppage", wfMsg('helppage'));
			$tpl->setRef( "searchaction", &$wgScriptPath );
			$tpl->setRef( "stylepath", &$wgStyleSheetPath );
			$tpl->setRef( "lang", &$wgLanguageCode );
			$tpl->set( "langname", $wgLang->getLanguageName( $wgLanguageCode ) );

			$username = $wgUser->getName();
			$tpl->setRef( "username", &$username );
			$userpage = $wgLang->getNsText( Namespace::getUser() ) . ":" . $wgUser->getName();
			$tpl->setRef( "userpage", &$userpage);
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

			/* set up the default links for the personal toolbar */
			$personal_urls = array();
			if ($loggedin) {
				$personal_urls['userpage'] = array('text' => $username,
					'href' => $this->makeUrl($userpage),
					'ttip' => wfMsg('tooltip-userpage'),
					'akey' => wfMsg('accesskey-userpage'));
				$personal_urls['mytalk'] = array('text' => wfMsg('mytalk'),
					'href' => $this->makeTalkUrl($userpage),
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
					'href' => $this->makeSpecialUrl('Contributions','target=' . $username),
					'ttip' => wfMsg('tooltip-mycontris'),
					'akey' => wfMsg('accesskey-mycontris'));
				$personal_urls['logout'] = array('text' => wfMsg('userlogout'),
					'href' => $this->makeSpecialUrl('Userlogout','returnpage=' . thispage),
					'ttip' => wfMsg('tooltip-logout'),
					'akey' => wfMsg('accesskey-logout'));
			} else {
				$personal_urls['login'] = array('text' => wfMsg('userlogin'),
					'href' => $this->makeSpecialUrl('Userlogin'),
					'ttip' => wfMsg('tooltip-login'),
					'akey' => wfMsg('accesskey-login'));
			}
			$tpl->setRef('personal_urls', &$personal_urls);

			/* set up the content actions */
			$iscontent = ($wgTitle->getNamespace() != Namespace::getSpecial() );

			$content_actions = array();
			/*$content_actions['view'] = array('class' => ($action == 'view' and !Namespace::isTalk( $wgTitle->getNamespace())) ? 'selected' : '',*/


			/* the edit tab */
			if( $iscontent) {
			
				$content_actions['article'] = array('class' => (!Namespace::isTalk( $wgTitle->getNamespace())) ? 'selected' : '',
				'text' => wfMsg('article'),
				'href' => $this->makeArticleUrl($wgTitle->getPrefixedDbKey()),
				'ttip' => wfMsg('tooltip-article'),
				'akey' => wfMsg('accesskey-article'));

				$content_actions['talk'] = array('class' => (Namespace::isTalk( $wgTitle->getNamespace()) ? 'selected' : ''),
				'text' => wfMsg('talk'),
				'href' => $this->makeTalkUrl($title),
				'ttip' => wfMsg('tooltip-talk'),
				'akey' => wfMsg('accesskey-talk'));

				if ( $wgTitle->userCanEdit() ) {
					if ( $oldid && ! isset( $diff ) ) {
						$oid = "&oldid={$oldid}";
					}
					$content_actions['edit'] = array('class' => ($action == 'edit' or $action == 'submit') ? 'selected' : '',
					'text' => wfMsg('edit'),
					'href' => $this->makeUrl($thispage, 'action=edit'.$oid),
					'ttip' => wfMsg('tooltip-edit'),
					'akey' => wfMsg('accesskey-edit'));
				} else {
					if ( $oldid && ! isset( $diff ) ) {
						$oid = "&oldid={$oldid}";
					}
					$content_actions['edit'] = array('class' => ($action == 'edit') ? 'selected' : '',
					'text' => wfMsg('viewsource'),
					'href' => $this->makeUrl($thispage, 'action=edit'.$oid),
					'ttip' => wfMsg('tooltip-edit'),
					'akey' => wfMsg('accesskey-edit'));
				}

				if ( $wgTitle->getArticleId() ) {

					$content_actions['history'] = array('class' => ($action == 'history') ? 'selected' : '',
					'text' => wfMsg('history_short'),
					'href' => $this->makeUrl($thispage, 'action=history'),
					'ttip' => wfMsg('tooltip-history'),
					'akey' => wfMsg('accesskey-history'));

					/*
					$content_actions['revert'] = array('class' => ($action == 'revert') ? 'selected' : '',
					'i18n_key' => 'revert',
					'href' => $this->makeUrl($wgTitle->getPrefixedDbKey(), 'action=revert'),
					'akey' => wfMsg('accesskeyrevert'));
					*/
					if( $wgUser->getNewtalk() ) {
						$content_actions['rollback'] = array('class' => ($action == 'rollback') ? 'selected' : '',
						'text' => wfMsg('rollback_short'),
						'href' => $this->makeUrl($thispage, 'action=rollback'),
						'ttip' => wfMsg('tooltip-rollback'),
						'akey' => wfMsg('accesskey-rollback'));
					}
					if($wgUser->isSysop()){
						if(!$wgTitle->isProtected()){
							$content_actions['protect'] = array('class' => ($action == 'protect') ? 'selected' : '',
							'text' => wfMsg('protect'),
							'href' => $this->makeUrl($thispage, 'action=protect'),
							'ttip' => wfMsg('tooltip-protect'),
							'akey' => wfMsg('accesskey-protect'));

						} else {
							$content_actions['unprotect'] = array('class' => ($action == 'unprotect') ? 'selected' : '',
							'text' => wfMsg('unprotect'),
							'href' => $this->makeUrl($thispage, 'action=unprotect'),
							'ttip' => wfMsg('tooltip-protect'),
							'akey' => wfMsg('accesskey-protect'));
						}
						$content_actions['delete'] = array('class' => ($action == 'delete') ? 'selected' : '',
						'text' => wfMsg('delete'),
						'href' => $this->makeUrl($thispage, 'action=delete'),
						'ttip' => wfMsg('tooltip-delete'),
						'akey' => wfMsg('accesskey-delete'));
					}
					if ( $wgUser->getID() != 0 ) {
						if ( $wgTitle->userCanEdit()) {
							$content_actions['move'] = array('class' => ($wgTitle->getDbKey() == 'Movepage' and $wgTitle->getNamespace == Namespace::getSpecial()) ? 'selected' : '',
							'text' => wfMsg('move'),
							'href' => $this->makeSpecialUrl('Movepage', 'target='.$thispage),
							'ttip' => wfMsg('tooltip-move'),
							'akey' => wfMsg('accesskey-move'));
						} else {
							$content_actions['move'] = array('class' => 'inactive',
							'text' => wfMsg('move'),
							'href' => '',
							'akey' => '');

						}
					}
				}
			
				if ( $wgUser->getID() != 0 and $action != 'edit' and $action != 'submit' ) {
					if( !$wgTitle->userIsWatching()) {
						$content_actions['watch'] = array('class' => ($action == 'watch' or $action == 'unwatch') ? 'selected' : '',
						'text' => wfMsg('watch'),
						'href' => $this->makeUrl($thispage, 'action=watch'),
						'ttip' => wfMsg('tooltip-watch'),
						'akey' => wfMsg('accesskey-watch'));
					} else {
						$content_actions['watch'] = array('class' => ($action == 'unwatch' or $action == 'watch') ? 'selected' : '',
						'text' => wfMsg('unwatch'),
						'href' => $this->makeUrl($thispage, 'action=unwatch'),
						'ttip' => wfMsg('tooltip-unwatch'),
						'akey' => wfMsg('accesskey-unwatch'));

					}
				}
			} else {
				/* show special page actions */

				$content_actions['article'] = array('class' => 'selected',
				'text' => wfMsg('specialpage'),
				'href' => 'javascript:void()',
				'ttip' => wfMsg('tooltip-specialpage'),
				'akey' => '');
				
				/*if ($wgTitle->getDbKey() == 'Movepage') {
					$content_actions['move'] = array('class' => 'selected',
					'i18n_key' => 'move',
					'href' => '',
					'akey' => '');
				}*/
			}
			$tpl->setRef('content_actions', &$content_actions);


			/* prepare an array of common navigation links */

			$nav_urls = array();
			$nav_urls['mainpage'] = array('href' => $this->makeI18nUrl('mainpage'));
			$nav_urls['randompage'] = array('href' => $this->makeSpecialUrl('Randompage'));
			$nav_urls['recentchanges'] = array('href' => $this->makeSpecialUrl('Recentchanges'));
			$nav_urls['whatlinkshere'] = array('href' => $this->makeSpecialUrl('Whatlinkshere', 'target='.$thispage));
			$nav_urls['currentevents'] = array('href' => $this->makeI18nUrl('currentevents'));
			$nav_urls['recentchangeslinked'] = array('href' => $this->makeSpecialUrl('Recentchangeslinked', 'target='.$thispage));
			$nav_urls['bugreports'] = array('href' => $this->makeI18nUrl('bugreportspage'));
			$nav_urls['sitesupport'] = array('href' => $this->makeI18nUrl('sitesupportpage'));
			$nav_urls['help'] = array('href' => $this->makeI18nUrl('helppage'));
			$nav_urls['upload'] = array('href' => $this->makeSpecialUrl('Upload'));
			$nav_urls['specialpages'] = array('href' => $this->makeSpecialUrl('Specialpages'));
			$tpl->setRef( "nav_urls", &$nav_urls );


			// execute template
			$res = $tpl->execute();
			// result may be an error
			if (PEAR::isError($res)) {
				echo $res->toString(), "\n";
			} else {
				echo $res;
			}

		}



		/*static*/ function makeSpecialUrl( $name, $urlaction='' ) {
			$title = Title::makeTitle( NS_SPECIAL, $name );
			return $title->getLocalURL( $urlaction );
		}
		/*static*/ function makeTalkUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			$title = $title->getTalkPage();
			return $title->getLocalURL( $urlaction );
		}
		/*static*/ function makeArticleUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			#$title->setNamespace(0);
			#$title = Title::makeTitle( Namespace::getSubject( $wgTitle->getNamespace() ), $wgTitle->getDbKey() );
			$title= $title->getSubjectPage();
			return $title->getLocalURL( $urlaction );
		}
		/*static*/ function makeI18nUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( wfMsg($name) );
			if(!is_object($title)) {
				$title = Title::newFromText( $name );
			}
			return $title->getLocalURL( $urlaction );
		}
		/*static*/ function makeUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			return $title->getLocalURL( $urlaction ); 
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
	
?>
