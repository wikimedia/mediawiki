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
		}

		function outputPage( &$out ) {
			global $wgTitle, $wgArticle, $wgUser, $wgLang, $wgOut;
			global $wgScriptPath, $wgStyleSheetPath, $wgLanguageCode, $wgUseNewInterlanguage;
			global $wgUseDatabaseMessages, $action;

			$this->initPage();
			$tpl = new PHPTAL($this->skinname . '.pt', '/usr/local/lib/wikipedia/templates');
			if ( $wgUseDatabaseMessages ) {

				$tpl->setTranslator(new MediaWiki_I18N());
			}

			$title = $wgTitle->getPrefixedText();
			$tpl->setRef( "title", &$title ); // ?
			$thispage = $wgTitle->getPrefixedDbKey();
			$tpl->setRef( "thispage", &$thispage );
			$tpl->set( "subtitle", $out->getSubtitle() );

			$tpl->set( "editable", ($wgTitle->getNamespace != Namespace::getSpecial() ) );
			$tpl->set( "exists", $wgTitle->getArticleID() != 0 );
			$tpl->set( "watch", $wgTitle->userIsWatching() ? "unwatch" : "watch" );
			$tpl->set( "protect", count($wgTitle->getRestrictions()) ? "unprotect" : "protect" );
			$tpl->set( "helppage", wfMsg('helppage'));

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


			/* set up the content actions */
			$iscontent = ($wgTitle->getNamespace() != Namespace::getSpecial() );

			$content_actions = array();
			/*$content_actions['view'] = array('class' => ($action == 'view' and !Namespace::isTalk( $wgTitle->getNamespace())) ? 'selected' : '',*/
			$content_actions['view'] = array('class' => (!Namespace::isTalk( $wgTitle->getNamespace())) ? 'selected' : '',
			'i18n_key' => 'view',
			'href' => $this->makeArticleUrl($wgTitle->getPrefixedDbKey()),
			'akey' => wfMsg('accesskeyview'));


			/* the edit tab */
			if( $iscontent) {

				$content_actions['talk'] = array('class' => (Namespace::isTalk( $wgTitle->getNamespace()) ? 'selected' : ''),
				'i18n_key' => 'talk',
				'href' => $this->makeTalkUrl($title),
				'akey' => wfMsg('accesskeytalk'));

				if ( $wgTitle->userCanEdit() ) {
					$content_actions['edit'] = array('class' => ($action == 'edit' or $action == 'submit') ? 'selected' : '',
					'i18n_key' => 'edit',
					'href' => $this->makeUrl($thispage, 'action=edit'),
					'akey' => wfMsg('accesskeyedit'));
				} else {
					$content_actions['edit'] = array('class' => ($action == 'edit') ? 'selected' : '',
					'i18n_key' => 'viewsource',
					'href' => $this->makeUrl($thispage, 'action=edit'),
					'akey' => wfMsg('accesskeyedit'));
				}
				$content_actions['history'] = array('class' => ($action == 'history') ? 'selected' : '',
				'i18n_key' => 'history',
				'href' => $this->makeUrl($thispage, 'action=history'),
				'akey' => wfMsg('accesskeyhistory'));

				/*
				$content_actions['revert'] = array('class' => ($action == 'revert') ? 'selected' : '',
				'i18n_key' => 'revert',
				'href' => $this->makeUrl($wgTitle->getPrefixedDbKey(), 'action=revert'),
				'akey' => wfMsg('accesskeyrevert'));
				*/
				if( $wgUser->getNewtalk() ) {
					$content_actions['rollback'] = array('class' => ($action == 'rollback') ? 'selected' : '',
					'i18n_key' => 'rollback',
					'href' => $this->makeUrl($thispage, 'action=rollback'),
					'akey' => wfMsg('accesskeyrollback'));
				}
				if($wgUser->isSysop()){
					if(!$wgTitle->isProtected()){
						$content_actions['protect'] = array('class' => ($action == 'protect') ? 'selected' : '',
						'i18n_key' => 'protect',
						'href' => $this->makeUrl($thispage, 'action=protect'),
						'akey' => wfMsg('accesskeyprotect'));

					} else {
						$content_actions['unprotect'] = array('class' => ($action == 'unprotect') ? 'selected' : '',
						'i18n_key' => 'unprotect',
						'href' => $this->makeUrl($thispage, 'action=unprotect'),
						'akey' => wfMsg('accesskeyprotect'));
					}
					$content_actions['delete'] = array('class' => ($action == 'delete') ? 'selected' : '',
					'i18n_key' => 'delete',
					'href' => $this->makeUrl($thispage, 'action=delete'),
					'akey' => wfMsg('accesskeydelete'));
				}
				if ( $wgUser->getID() != 0 ) {
					if ( $wgTitle->userCanEdit()) {
						$content_actions['move'] = array('class' => ($wgTitle->getDbKey() == 'Movepage' and $wgTitle->getNamespace == Namespace::getSpecial()) ? 'selected' : '',
						'i18n_key' => 'move',
						'href' => $this->makeSpecialUrl('Movepage', 'target='.$thispage),
						'akey' => wfMsg('accesskeymove'));
					} else {
						$content_actions['move'] = array('class' => 'inactive',
						'i18n_key' => 'move',
						'href' => '',
						'akey' => '');

					}
				}
				if ( $wgUser->getID() != 0 and $action != 'edit' and $action != 'submit' ) {
					if( !$wgTitle->userIsWatching()) {
						$content_actions['watch'] = array('class' => ($action == 'watch' or $action == 'unwatch') ? 'selected' : '',
						'i18n_key' => 'watch',
						'href' => $this->makeUrl($thispage, 'action=watch'),
						'akey' => wfMsg('accesskey-watch'));
					} else {
						$content_actions['watch'] = array('class' => ($action == 'unwatch' or $action == 'watch') ? 'selected' : '',
						'i18n_key' => 'unwatch',
						'href' => $this->makeUrl($thispage, 'action=unwatch'),
						'akey' => wfMsg('accesskey-watch'));

					}
				}
			} else {
				/* show special page actions */

				if ($wgTitle->getDbKey() == 'Movepage') {
					$content_actions['move'] = array('class' => 'selected',
					'i18n_key' => 'move',
					'href' => '',
					'akey' => '');
				}
			}
			$tpl->setRef('content_actions', &$content_actions);


			/* prepare an array of common navigation links */

			$urls = array();
			$urls['mainpage'] = array('href' => $this->makeI18nUrl('mainpage'));
			$urls['randompage'] = array('href' => $this->makeSpecialUrl('Randompage'));
			$urls['recentchanges'] = array('href' => $this->makeSpecialUrl('Recentchanges'));
			$urls['whatlinkshere'] = array('href' => $this->makeSpecialUrl('Whatlinkshere', 'target='.$thispage));
			$urls['currentevents'] = array('href' => $this->makeI18nUrl('currentevents'));
			$urls['recentchangeslinked'] = array('href' => $this->makeSpecialUrl('Recentchangeslinked', 'target='.$thispage));
			$urls['bugreports'] = array('href' => $this->makeI18nUrl('bugreportspage'));
			$urls['sitesupport'] = array('href' => $this->makeI18nUrl('sitesupportpage'));
			$urls['help'] = array('href' => $this->makeI18nUrl('helppage'));
			$urls['upload'] = array('href' => $this->makeSpecialUrl('Upload'));
			$urls['specialpages'] = array('href' => $this->makeSpecialUrl('Specialpages'));
			$tpl->setRef( "urls", &$urls );

			$tpl->setRef( "searchaction", &$wgScriptPath );
			$tpl->setRef( "stylepath", &$wgStyleSheetPath );
			$tpl->setRef( "lang", &$wgLanguageCode );
			$tpl->set( "langname", $wgLang->getLanguageName( $wgLanguageCode ) );

			$tpl->set( "username", $wgUser->getName() );
			$tpl->set( "userpage", $wgLang->getNsText( Namespace::getUser() ) . ":" . $wgUser->getName() );
			$tpl->set( "loggedin", $wgUser->getID() != 0 );
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

			$tpl->setRef( "debug", &$out->mDebugtext );
			$tpl->set( "reporttime", $out->reportTime() );

			$tpl->setRef( "bodytext", &$out->mBodytext );

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
			return $title->escapeLocalURL( $urlaction );
		}
		/*static*/ function makeTalkUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			$title = $title->getTalkPage();
			return $title->escapeLocalURL( $urlaction );
		}
		/*static*/ function makeArticleUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			#$title->setNamespace(0);
			#$title = Title::makeTitle( Namespace::getSubject( $wgTitle->getNamespace() ), $wgTitle->getDbKey() );
			$title= $title->getSubjectPage();
			return $title->escapeLocalURL( $urlaction );
		}
		/*static*/ function makeI18nUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( wfMsg($name) );
			#$title->setNamespace(0);
			#$title = Title::makeTitle( Namespace::getSubject( $wgTitle->getNamespace() ), $wgTitle->getDbKey() );
			return $title->escapeLocalURL( $urlaction );
		}
		/*static*/ function makeUrl ( $name, $urlaction='' ) {
			$title = Title::newFromText( $name );
			return $title->escapeLocalURL( $urlaction ); 
		}

	}

	class SkinDaVinci extends SkinPHPTal {
		function initPage() {
			SkinPHPTal::initPage();
			$this->skinname = "davinci";
		}
	}
	
?>
