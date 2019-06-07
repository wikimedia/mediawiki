<?php
/**
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

use MediaWiki\Auth\AuthManager;
use MediaWiki\MediaWikiServices;

/**
 * Base class for template-based skins.
 *
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
	/**
	 * @var string Name of our skin, it probably needs to be all lower case.
	 *   Child classes should override the default.
	 */
	public $skinname = 'monobook';

	/**
	 * @var string For QuickTemplate, the name of the subclass which will
	 *   actually fill the template.  Child classes should override the default.
	 */
	public $template = QuickTemplate::class;

	public $thispage;
	public $titletxt;
	public $userpage;
	public $thisquery;
	public $loggedin;
	public $username;
	public $userpageUrlDetails;

	/**
	 * Add specific styles for this skin
	 *
	 * @param OutputPage $out
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		$moduleStyles = [
			'mediawiki.legacy.shared',
			'mediawiki.legacy.commonPrint',
			'mediawiki.sectionAnchor'
		];
		if ( $out->isSyndicated() ) {
			$moduleStyles[] = 'mediawiki.feedlink';
		}

		// Deprecated since 1.26: Unconditional loading of mediawiki.ui.button
		// on every page is deprecated. Express a dependency instead.
		if ( strpos( $out->getHTML(), 'mw-ui-button' ) !== false ) {
			$moduleStyles[] = 'mediawiki.ui.button';
		}

		$out->addModuleStyles( $moduleStyles );
	}

	/**
	 * Create the template engine object; we feed it a bunch of data
	 * and eventually it spits out some HTML. Should have interface
	 * roughly equivalent to PHPTAL 0.7.
	 *
	 * @param string $classname
	 * @param bool|string $repository Subdirectory where we keep template files
	 * @param bool|string $cache_dir
	 * @return QuickTemplate
	 * @private
	 */
	function setupTemplate( $classname, $repository = false, $cache_dir = false ) {
		return new $classname( $this->getConfig() );
	}

	/**
	 * Generates array of language links for the current page
	 *
	 * @return array
	 */
	public function getLanguages() {
		global $wgHideInterlanguageLinks;
		if ( $wgHideInterlanguageLinks ) {
			return [];
		}

		$userLang = $this->getLanguage();
		$languageLinks = [];

		foreach ( $this->getOutput()->getLanguageLinks() as $languageLinkText ) {
			$class = 'interlanguage-link interwiki-' . explode( ':', $languageLinkText, 2 )[0];

			$languageLinkTitle = Title::newFromText( $languageLinkText );
			if ( $languageLinkTitle ) {
				$ilInterwikiCode = $languageLinkTitle->getInterwiki();
				$ilLangName = Language::fetchLanguageName( $ilInterwikiCode );

				if ( strval( $ilLangName ) === '' ) {
					$ilDisplayTextMsg = wfMessage( "interlanguage-link-$ilInterwikiCode" );
					if ( !$ilDisplayTextMsg->isDisabled() ) {
						// Use custom MW message for the display text
						$ilLangName = $ilDisplayTextMsg->text();
					} else {
						// Last resort: fallback to the language link target
						$ilLangName = $languageLinkText;
					}
				} else {
					// Use the language autonym as display text
					$ilLangName = $this->formatLanguageName( $ilLangName );
				}

				// CLDR extension or similar is required to localize the language name;
				// otherwise we'll end up with the autonym again.
				$ilLangLocalName = Language::fetchLanguageName(
					$ilInterwikiCode,
					$userLang->getCode()
				);

				$languageLinkTitleText = $languageLinkTitle->getText();
				if ( $ilLangLocalName === '' ) {
					$ilFriendlySiteName = wfMessage( "interlanguage-link-sitename-$ilInterwikiCode" );
					if ( !$ilFriendlySiteName->isDisabled() ) {
						if ( $languageLinkTitleText === '' ) {
							$ilTitle = wfMessage(
								'interlanguage-link-title-nonlangonly',
								$ilFriendlySiteName->text()
							)->text();
						} else {
							$ilTitle = wfMessage(
								'interlanguage-link-title-nonlang',
								$languageLinkTitleText,
								$ilFriendlySiteName->text()
							)->text();
						}
					} else {
						// we have nothing friendly to put in the title, so fall back to
						// displaying the interlanguage link itself in the title text
						// (similar to what is done in page content)
						$ilTitle = $languageLinkTitle->getInterwiki() .
							":$languageLinkTitleText";
					}
				} elseif ( $languageLinkTitleText === '' ) {
					$ilTitle = wfMessage(
						'interlanguage-link-title-langonly',
						$ilLangLocalName
					)->text();
				} else {
					$ilTitle = wfMessage(
						'interlanguage-link-title',
						$languageLinkTitleText,
						$ilLangLocalName
					)->text();
				}

				$ilInterwikiCodeBCP47 = LanguageCode::bcp47( $ilInterwikiCode );
				$languageLink = [
					'href' => $languageLinkTitle->getFullURL(),
					'text' => $ilLangName,
					'title' => $ilTitle,
					'class' => $class,
					'link-class' => 'interlanguage-link-target',
					'lang' => $ilInterwikiCodeBCP47,
					'hreflang' => $ilInterwikiCodeBCP47,
				];
				Hooks::run(
					'SkinTemplateGetLanguageLink',
					[ &$languageLink, $languageLinkTitle, $this->getTitle(), $this->getOutput() ]
				);
				$languageLinks[] = $languageLink;
			}
		}

		return $languageLinks;
	}

	protected function setupTemplateForOutput() {
		$request = $this->getRequest();
		$user = $this->getUser();
		$title = $this->getTitle();

		$tpl = $this->setupTemplate( $this->template, 'skins' );

		$this->thispage = $title->getPrefixedDBkey();
		$this->titletxt = $title->getPrefixedText();
		$this->userpage = $user->getUserPage()->getPrefixedText();
		$query = [];
		if ( !$request->wasPosted() ) {
			$query = $request->getValues();
			unset( $query['title'] );
			unset( $query['returnto'] );
			unset( $query['returntoquery'] );
		}
		$this->thisquery = wfArrayToCgi( $query );
		$this->loggedin = $user->isLoggedIn();
		$this->username = $user->getName();

		if ( $this->loggedin ) {
			$this->userpageUrlDetails = self::makeUrlDetails( $this->userpage );
		} else {
			# This won't be used in the standard skins, but we define it to preserve the interface
			# To save time, we check for existence
			$this->userpageUrlDetails = self::makeKnownUrlDetails( $this->userpage );
		}

		return $tpl;
	}

	/**
	 * initialize various variables and generate the template
	 *
	 * @param OutputPage $out
	 */
	function outputPage( OutputPage $out = null ) {
		Profiler::instance()->setTemplated( true );

		$oldContext = null;
		if ( $out !== null ) {
			// Deprecated since 1.20, note added in 1.25
			wfDeprecated( __METHOD__, '1.25' );
			$oldContext = $this->getContext();
			$this->setContext( $out->getContext() );
		}

		$out = $this->getOutput();

		$this->initPage( $out );
		$tpl = $this->prepareQuickTemplate();
		// execute template
		$res = $tpl->execute();

		// result may be an error
		$this->printOrError( $res );

		if ( $oldContext ) {
			$this->setContext( $oldContext );
		}
	}

	/**
	 * Wrap the body text with language information and identifiable element
	 *
	 * @param Title $title
	 * @param string $html body text
	 * @return string html
	 */
	protected function wrapHTML( $title, $html ) {
		# An ID that includes the actual body text; without categories, contentSub, ...
		$realBodyAttribs = [ 'id' => 'mw-content-text' ];

		# Add a mw-content-ltr/rtl class to be able to style based on text
		# direction when the content is different from the UI language (only
		# when viewing)
		# Most information on special pages and file pages is in user language,
		# rather than content language, so those will not get this
		if ( Action::getActionName( $this ) === 'view' &&
			( !$title->inNamespaces( NS_SPECIAL, NS_FILE ) || $title->isRedirect() ) ) {
			$pageLang = $title->getPageViewLanguage();
			$realBodyAttribs['lang'] = $pageLang->getHtmlCode();
			$realBodyAttribs['dir'] = $pageLang->getDir();
			$realBodyAttribs['class'] = 'mw-content-' . $pageLang->getDir();
		}

		return Html::rawElement( 'div', $realBodyAttribs, $html );
	}

	/**
	 * initialize various variables and generate the template
	 *
	 * @since 1.23
	 * @return QuickTemplate The template to be executed by outputPage
	 */
	protected function prepareQuickTemplate() {
		global $wgContLang, $wgScript, $wgStylePath, $wgMimeType, $wgJsMimeType,
			$wgSitename, $wgLogo, $wgMaxCredits,
			$wgShowCreditsIfMax, $wgArticlePath,
			$wgScriptPath, $wgServer;

		$title = $this->getTitle();
		$request = $this->getRequest();
		$out = $this->getOutput();
		$tpl = $this->setupTemplateForOutput();

		$tpl->set( 'title', $out->getPageTitle() );
		$tpl->set( 'pagetitle', $out->getHTMLTitle() );
		$tpl->set( 'displaytitle', $out->mPageLinkTitle );

		$tpl->set( 'thispage', $this->thispage );
		$tpl->set( 'titleprefixeddbkey', $this->thispage );
		$tpl->set( 'titletext', $title->getText() );
		$tpl->set( 'articleid', $title->getArticleID() );

		$tpl->set( 'isarticle', $out->isArticle() );

		$subpagestr = $this->subPageSubtitle();
		if ( $subpagestr !== '' ) {
			$subpagestr = '<span class="subpages">' . $subpagestr . '</span>';
		}
		$tpl->set( 'subtitle', $subpagestr . $out->getSubtitle() );

		$undelete = $this->getUndeleteLink();
		if ( $undelete === '' ) {
			$tpl->set( 'undelete', '' );
		} else {
			$tpl->set( 'undelete', '<span class="subpages">' . $undelete . '</span>' );
		}

		$tpl->set( 'catlinks', $this->getCategories() );
		if ( $out->isSyndicated() ) {
			$feeds = [];
			foreach ( $out->getSyndicationLinks() as $format => $link ) {
				$feeds[$format] = [
					// Messages: feed-atom, feed-rss
					'text' => $this->msg( "feed-$format" )->text(),
					'href' => $link
				];
			}
			$tpl->set( 'feeds', $feeds );
		} else {
			$tpl->set( 'feeds', false );
		}

		$tpl->set( 'mimetype', $wgMimeType );
		$tpl->set( 'jsmimetype', $wgJsMimeType );
		$tpl->set( 'charset', 'UTF-8' );
		$tpl->set( 'wgScript', $wgScript );
		$tpl->set( 'skinname', $this->skinname );
		$tpl->set( 'skinclass', static::class );
		$tpl->set( 'skin', $this );
		$tpl->set( 'stylename', $this->stylename );
		$tpl->set( 'printable', $out->isPrintable() );
		$tpl->set( 'handheld', $request->getBool( 'handheld' ) );
		$tpl->set( 'loggedin', $this->loggedin );
		$tpl->set( 'notspecialpage', !$title->isSpecialPage() );
		$tpl->set( 'searchaction', $this->escapeSearchLink() );
		$tpl->set( 'searchtitle', SpecialPage::getTitleFor( 'Search' )->getPrefixedDBkey() );
		$tpl->set( 'search', trim( $request->getVal( 'search' ) ) );
		$tpl->set( 'stylepath', $wgStylePath );
		$tpl->set( 'articlepath', $wgArticlePath );
		$tpl->set( 'scriptpath', $wgScriptPath );
		$tpl->set( 'serverurl', $wgServer );
		$tpl->set( 'logopath', $wgLogo );
		$tpl->set( 'sitename', $wgSitename );

		$userLang = $this->getLanguage();
		$userLangCode = $userLang->getHtmlCode();
		$userLangDir = $userLang->getDir();

		$tpl->set( 'lang', $userLangCode );
		$tpl->set( 'dir', $userLangDir );
		$tpl->set( 'rtl', $userLang->isRTL() );

		$tpl->set( 'capitalizeallnouns', $userLang->capitalizeAllNouns() ? ' capitalize-all-nouns' : '' );
		$tpl->set( 'showjumplinks', true ); // showjumplinks preference has been removed
		$tpl->set( 'username', $this->loggedin ? $this->username : null );
		$tpl->set( 'userpage', $this->userpage );
		$tpl->set( 'userpageurl', $this->userpageUrlDetails['href'] );
		$tpl->set( 'userlang', $userLangCode );

		// Users can have their language set differently than the
		// content of the wiki. For these users, tell the web browser
		// that interface elements are in a different language.
		$tpl->set( 'userlangattributes', '' );
		$tpl->set( 'specialpageattributes', '' ); # obsolete
		// Used by VectorBeta to insert HTML before content but after the
		// heading for the page title. Defaults to empty string.
		$tpl->set( 'prebodyhtml', '' );

		if ( $userLangCode !== $wgContLang->getHtmlCode() || $userLangDir !== $wgContLang->getDir() ) {
			$escUserlang = htmlspecialchars( $userLangCode );
			$escUserdir = htmlspecialchars( $userLangDir );
			// Attributes must be in double quotes because htmlspecialchars() doesn't
			// escape single quotes
			$attrs = " lang=\"$escUserlang\" dir=\"$escUserdir\"";
			$tpl->set( 'userlangattributes', $attrs );
		}

		$tpl->set( 'newtalk', $this->getNewtalks() );
		$tpl->set( 'logo', $this->logoText() );

		$tpl->set( 'copyright', false );
		// No longer used
		$tpl->set( 'viewcount', false );
		$tpl->set( 'lastmod', false );
		$tpl->set( 'credits', false );
		$tpl->set( 'numberofwatchingusers', false );
		if ( $out->isArticle() && $title->exists() ) {
			if ( $this->isRevisionCurrent() ) {
				if ( $wgMaxCredits != 0 ) {
					$tpl->set( 'credits', Action::factory( 'credits', $this->getWikiPage(),
						$this->getContext() )->getCredits( $wgMaxCredits, $wgShowCreditsIfMax ) );
				} else {
					$tpl->set( 'lastmod', $this->lastModified() );
				}
			}
			$tpl->set( 'copyright', $this->getCopyright() );
		}

		$tpl->set( 'copyrightico', $this->getCopyrightIcon() );
		$tpl->set( 'poweredbyico', $this->getPoweredBy() );
		$tpl->set( 'disclaimer', $this->disclaimerLink() );
		$tpl->set( 'privacy', $this->privacyLink() );
		$tpl->set( 'about', $this->aboutLink() );

		$tpl->set( 'footerlinks', [
			'info' => [
				'lastmod',
				'numberofwatchingusers',
				'credits',
				'copyright',
			],
			'places' => [
				'privacy',
				'about',
				'disclaimer',
			],
		] );

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

		$tpl->set( 'indicators', $out->getIndicators() );

		$tpl->set( 'sitenotice', $this->getSiteNotice() );
		$tpl->set( 'printfooter', $this->printSource() );
		// Wrap the bodyText with #mw-content-text element
		$out->mBodytext = $this->wrapHTML( $title, $out->mBodytext );
		$tpl->set( 'bodytext', $out->mBodytext );

		$language_urls = $this->getLanguages();
		if ( count( $language_urls ) ) {
			$tpl->set( 'language_urls', $language_urls );
		} else {
			$tpl->set( 'language_urls', false );
		}

		# Personal toolbar
		$tpl->set( 'personal_urls', $this->buildPersonalUrls() );
		$content_navigation = $this->buildContentNavigationUrls();
		$content_actions = $this->buildContentActionUrls( $content_navigation );
		$tpl->set( 'content_navigation', $content_navigation );
		$tpl->set( 'content_actions', $content_actions );

		$tpl->set( 'sidebar', $this->buildSidebar() );
		$tpl->set( 'nav_urls', $this->buildNavUrls() );

		// Do this last in case hooks above add bottom scripts
		$tpl->set( 'bottomscripts', $this->bottomScripts() );

		// Set the head scripts near the end, in case the above actions resulted in added scripts
		$tpl->set( 'headelement', $out->headElement( $this ) );

		$tpl->set( 'debug', '' );
		$tpl->set( 'debughtml', $this->generateDebugHTML() );
		$tpl->set( 'reporttime', wfReportTime() );

		// Avoid PHP 7.1 warning of passing $this by reference
		$skinTemplate = $this;
		// original version by hansm
		if ( !Hooks::run( 'SkinTemplateOutputPageBeforeExec', [ &$skinTemplate, &$tpl ] ) ) {
			wfDebug( __METHOD__ . ": Hook SkinTemplateOutputPageBeforeExec broke outputPage execution!\n" );
		}

		// Set the bodytext to another key so that skins can just output it on its own
		// and output printfooter and debughtml separately
		$tpl->set( 'bodycontent', $tpl->data['bodytext'] );

		// Append printfooter and debughtml onto bodytext so that skins that
		// were already using bodytext before they were split out don't suddenly
		// start not outputting information.
		$tpl->data['bodytext'] .= Html::rawElement(
			'div',
			[ 'class' => 'printfooter' ],
			"\n{$tpl->data['printfooter']}"
		) . "\n";
		$tpl->data['bodytext'] .= $tpl->data['debughtml'];

		// allow extensions adding stuff after the page content.
		// See Skin::afterContentHook() for further documentation.
		$tpl->set( 'dataAfterContent', $this->afterContentHook() );

		return $tpl;
	}

	/**
	 * Get the HTML for the p-personal list
	 * @return string
	 */
	public function getPersonalToolsList() {
		return $this->makePersonalToolsList();
	}

	/**
	 * Get the HTML for the personal tools list
	 *
	 * @since 1.31
	 *
	 * @param array $personalTools
	 * @param array $options
	 * @return string
	 */
	public function makePersonalToolsList( $personalTools = null, $options = [] ) {
		$tpl = $this->setupTemplateForOutput();
		$tpl->set( 'personal_urls', $this->buildPersonalUrls() );
		$html = '';

		if ( $personalTools === null ) {
			$personalTools = $tpl->getPersonalTools();
		}

		foreach ( $personalTools as $key => $item ) {
			$html .= $tpl->makeListItem( $key, $item, $options );
		}

		return $html;
	}

	/**
	 * Get personal tools for the user
	 *
	 * @since 1.31
	 *
	 * @return array Array of personal tools
	 */
	public function getStructuredPersonalTools() {
		$tpl = $this->setupTemplateForOutput();
		$tpl->set( 'personal_urls', $this->buildPersonalUrls() );

		return $tpl->getPersonalTools();
	}

	/**
	 * Format language name for use in sidebar interlanguage links list.
	 * By default it is capitalized.
	 *
	 * @param string $name Language name, e.g. "English" or "español"
	 * @return string
	 * @private
	 */
	function formatLanguageName( $name ) {
		return $this->getLanguage()->ucfirst( $name );
	}

	/**
	 * Output the string, or print error message if it's
	 * an error object of the appropriate type.
	 * For the base class, assume strings all around.
	 *
	 * @param string $str
	 * @private
	 */
	function printOrError( $str ) {
		echo $str;
	}

	/**
	 * Output a boolean indicating if buildPersonalUrls should output separate
	 * login and create account links or output a combined link
	 * By default we simply return a global config setting that affects most skins
	 * This is setup as a method so that like with $wgLogo and getLogo() a skin
	 * can override this setting and always output one or the other if it has
	 * a reason it can't output one of the two modes.
	 * @return bool
	 */
	function useCombinedLoginLink() {
		global $wgUseCombinedLoginLink;
		return $wgUseCombinedLoginLink;
	}

	/**
	 * build array of urls for personal toolbar
	 * @return array
	 */
	protected function buildPersonalUrls() {
		$title = $this->getTitle();
		$request = $this->getRequest();
		$pageurl = $title->getLocalURL();
		$authManager = AuthManager::singleton();

		/* set up the default links for the personal toolbar */
		$personal_urls = [];

		# Due to T34276, if a user does not have read permissions,
		# $this->getTitle() will just give Special:Badtitle, which is
		# not especially useful as a returnto parameter. Use the title
		# from the request instead, if there was one.
		if ( $this->getUser()->isAllowed( 'read' ) ) {
			$page = $this->getTitle();
		} else {
			$page = Title::newFromText( $request->getVal( 'title', '' ) );
		}
		$page = $request->getVal( 'returnto', $page );
		$returnto = [];
		if ( strval( $page ) !== '' ) {
			$returnto['returnto'] = $page;
			$query = $request->getVal( 'returntoquery', $this->thisquery );
			$paramsArray = wfCgiToArray( $query );
			unset( $paramsArray['logoutToken'] );
			$query = wfArrayToCgi( $paramsArray );
			if ( $query != '' ) {
				$returnto['returntoquery'] = $query;
			}
		}

		if ( $this->loggedin ) {
			$personal_urls['userpage'] = [
				'text' => $this->username,
				'href' => &$this->userpageUrlDetails['href'],
				'class' => $this->userpageUrlDetails['exists'] ? false : 'new',
				'exists' => $this->userpageUrlDetails['exists'],
				'active' => ( $this->userpageUrlDetails['href'] == $pageurl ),
				'dir' => 'auto'
			];
			$usertalkUrlDetails = $this->makeTalkUrlDetails( $this->userpage );
			$personal_urls['mytalk'] = [
				'text' => $this->msg( 'mytalk' )->text(),
				'href' => &$usertalkUrlDetails['href'],
				'class' => $usertalkUrlDetails['exists'] ? false : 'new',
				'exists' => $usertalkUrlDetails['exists'],
				'active' => ( $usertalkUrlDetails['href'] == $pageurl )
			];
			$href = self::makeSpecialUrl( 'Preferences' );
			$personal_urls['preferences'] = [
				'text' => $this->msg( 'mypreferences' )->text(),
				'href' => $href,
				'active' => ( $href == $pageurl )
			];

			if ( $this->getUser()->isAllowed( 'viewmywatchlist' ) ) {
				$href = self::makeSpecialUrl( 'Watchlist' );
				$personal_urls['watchlist'] = [
					'text' => $this->msg( 'mywatchlist' )->text(),
					'href' => $href,
					'active' => ( $href == $pageurl )
				];
			}

			# We need to do an explicit check for Special:Contributions, as we
			# have to match both the title, and the target, which could come
			# from request values (Special:Contributions?target=Jimbo_Wales)
			# or be specified in "sub page" form
			# (Special:Contributions/Jimbo_Wales). The plot
			# thickens, because the Title object is altered for special pages,
			# so it doesn't contain the original alias-with-subpage.
			$origTitle = Title::newFromText( $request->getText( 'title' ) );
			if ( $origTitle instanceof Title && $origTitle->isSpecialPage() ) {
				list( $spName, $spPar ) = SpecialPageFactory::resolveAlias( $origTitle->getText() );
				$active = $spName == 'Contributions'
					&& ( ( $spPar && $spPar == $this->username )
						|| $request->getText( 'target' ) == $this->username );
			} else {
				$active = false;
			}

			$href = self::makeSpecialUrlSubpage( 'Contributions', $this->username );
			$personal_urls['mycontris'] = [
				'text' => $this->msg( 'mycontris' )->text(),
				'href' => $href,
				'active' => $active
			];

			// if we can't set the user, we can't unset it either
			if ( $request->getSession()->canSetUser() ) {
				$personal_urls['logout'] = [
					'text' => $this->msg( 'pt-userlogout' )->text(),
					'href' => self::makeSpecialUrl( 'Userlogout',
						// Note: userlogout link must always contain an & character, otherwise we might not be able
						// to detect a buggy precaching proxy (T19790)
						( $title->isSpecial( 'Preferences' ) ? [] : $returnto )
						+ [ 'logoutToken' => $this->getUser()->getEditToken( 'logoutToken', $this->getRequest() ) ] ),
					'active' => false
				];
			}
		} else {
			$useCombinedLoginLink = $this->useCombinedLoginLink();
			if ( !$authManager->canCreateAccounts() || !$authManager->canAuthenticateNow() ) {
				// don't show combined login/signup link if one of those is actually not available
				$useCombinedLoginLink = false;
			}

			$loginlink = $this->getUser()->isAllowed( 'createaccount' ) && $useCombinedLoginLink
				? 'nav-login-createaccount'
				: 'pt-login';

			$login_url = [
				'text' => $this->msg( $loginlink )->text(),
				'href' => self::makeSpecialUrl( 'Userlogin', $returnto ),
				'active' => $title->isSpecial( 'Userlogin' )
					|| $title->isSpecial( 'CreateAccount' ) && $useCombinedLoginLink,
			];
			$createaccount_url = [
				'text' => $this->msg( 'pt-createaccount' )->text(),
				'href' => self::makeSpecialUrl( 'CreateAccount', $returnto ),
				'active' => $title->isSpecial( 'CreateAccount' ),
			];

			// No need to show Talk and Contributions to anons if they can't contribute!
			if ( User::groupHasPermission( '*', 'edit' ) ) {
				// Because of caching, we can't link directly to the IP talk and
				// contributions pages. Instead we use the special page shortcuts
				// (which work correctly regardless of caching). This means we can't
				// determine whether these links are active or not, but since major
				// skins (MonoBook, Vector) don't use this information, it's not a
				// huge loss.
				$personal_urls['anontalk'] = [
					'text' => $this->msg( 'anontalk' )->text(),
					'href' => self::makeSpecialUrlSubpage( 'Mytalk', false ),
					'active' => false
				];
				$personal_urls['anoncontribs'] = [
					'text' => $this->msg( 'anoncontribs' )->text(),
					'href' => self::makeSpecialUrlSubpage( 'Mycontributions', false ),
					'active' => false
				];
			}

			if (
				$authManager->canCreateAccounts()
				&& $this->getUser()->isAllowed( 'createaccount' )
				&& !$useCombinedLoginLink
			) {
				$personal_urls['createaccount'] = $createaccount_url;
			}

			if ( $authManager->canAuthenticateNow() ) {
				$key = User::groupHasPermission( '*', 'read' )
					? 'login'
					: 'login-private';
				$personal_urls[$key] = $login_url;
			}
		}

		Hooks::runWithoutAbort( 'PersonalUrls', [ &$personal_urls, &$title, $this ] );
		return $personal_urls;
	}

	/**
	 * Builds an array with tab definition
	 *
	 * @param Title $title Page Where the tab links to
	 * @param string|array $message Message key or an array of message keys (will fall back)
	 * @param bool $selected Display the tab as selected
	 * @param string $query Query string attached to tab URL
	 * @param bool $checkEdit Check if $title exists and mark with .new if one doesn't
	 *
	 * @return array
	 */
	function tabAction( $title, $message, $selected, $query = '', $checkEdit = false ) {
		$classes = [];
		if ( $selected ) {
			$classes[] = 'selected';
		}
		$exists = true;
		if ( $checkEdit && !$title->isKnown() ) {
			$classes[] = 'new';
			$exists = false;
			if ( $query !== '' ) {
				$query = 'action=edit&redlink=1&' . $query;
			} else {
				$query = 'action=edit&redlink=1';
			}
		}

		$linkClass = MediaWikiServices::getInstance()->getLinkRenderer()->getLinkClasses( $title );

		// wfMessageFallback will nicely accept $message as an array of fallbacks
		// or just a single key
		$msg = wfMessageFallback( $message )->setContext( $this->getContext() );
		if ( is_array( $message ) ) {
			// for hook compatibility just keep the last message name
			$message = end( $message );
		}
		if ( $msg->exists() ) {
			$text = $msg->text();
		} else {
			global $wgContLang;
			$text = $wgContLang->getConverter()->convertNamespace(
				MWNamespace::getSubject( $title->getNamespace() ) );
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$skinTemplate = $this;
		$result = [];
		if ( !Hooks::run( 'SkinTemplateTabAction', [ &$skinTemplate,
				$title, $message, $selected, $checkEdit,
				&$classes, &$query, &$text, &$result ] ) ) {
			return $result;
		}

		$result = [
			'class' => implode( ' ', $classes ),
			'text' => $text,
			'href' => $title->getLocalURL( $query ),
			'exists' => $exists,
			'primary' => true ];
		if ( $linkClass !== '' ) {
			$result['link-class'] = $linkClass;
		}

		return $result;
	}

	function makeTalkUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		if ( !is_object( $title ) ) {
			throw new MWException( __METHOD__ . " given invalid pagename $name" );
		}
		$title = $title->getTalkPage();
		self::checkTitle( $title, $name );
		return [
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->isKnown(),
		];
	}

	/**
	 * @todo is this even used?
	 * @param string $name
	 * @param string $urlaction
	 * @return array
	 */
	function makeArticleUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		$title = $title->getSubjectPage();
		self::checkTitle( $title, $name );
		return [
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->exists(),
		];
	}

	/**
	 * a structured array of links usually used for the tabs in a skin
	 *
	 * There are 4 standard sections
	 * namespaces: Used for namespace tabs like special, page, and talk namespaces
	 * views: Used for primary page views like read, edit, history
	 * actions: Used for most extra page actions like deletion, protection, etc...
	 * variants: Used to list the language variants for the page
	 *
	 * Each section's value is a key/value array of links for that section.
	 * The links themselves have these common keys:
	 * - class: The css classes to apply to the tab
	 * - text: The text to display on the tab
	 * - href: The href for the tab to point to
	 * - rel: An optional rel= for the tab's link
	 * - redundant: If true the tab will be dropped in skins using content_actions
	 *   this is useful for tabs like "Read" which only have meaning in skins that
	 *   take special meaning from the grouped structure of content_navigation
	 *
	 * Views also have an extra key which can be used:
	 * - primary: If this is not true skins like vector may try to hide the tab
	 *            when the user has limited space in their browser window
	 *
	 * content_navigation using code also expects these ids to be present on the
	 * links, however these are usually automatically generated by SkinTemplate
	 * itself and are not necessary when using a hook. The only things these may
	 * matter to are people modifying content_navigation after it's initial creation:
	 * - id: A "preferred" id, most skins are best off outputting this preferred
	 *   id for best compatibility.
	 * - tooltiponly: This is set to true for some tabs in cases where the system
	 *   believes that the accesskey should not be added to the tab.
	 *
	 * @return array
	 */
	protected function buildContentNavigationUrls() {
		global $wgDisableLangConversion;

		// Display tabs for the relevant title rather than always the title itself
		$title = $this->getRelevantTitle();
		$onPage = $title->equals( $this->getTitle() );

		$out = $this->getOutput();
		$request = $this->getRequest();
		$user = $this->getUser();

		$content_navigation = [
			'namespaces' => [],
			'views' => [],
			'actions' => [],
			'variants' => []
		];

		// parameters
		$action = $request->getVal( 'action', 'view' );

		$userCanRead = $title->quickUserCan( 'read', $user );

		// Avoid PHP 7.1 warning of passing $this by reference
		$skinTemplate = $this;
		$preventActiveTabs = false;
		Hooks::run( 'SkinTemplatePreventOtherActiveTabs', [ &$skinTemplate, &$preventActiveTabs ] );

		// Checks if page is some kind of content
		if ( $title->canExist() ) {
			// Gets page objects for the related namespaces
			$subjectPage = $title->getSubjectPage();
			$talkPage = $title->getTalkPage();

			// Determines if this is a talk page
			$isTalk = $title->isTalkPage();

			// Generates XML IDs from namespace names
			$subjectId = $title->getNamespaceKey( '' );

			if ( $subjectId == 'main' ) {
				$talkId = 'talk';
			} else {
				$talkId = "{$subjectId}_talk";
			}

			$skname = $this->skinname;

			// Adds namespace links
			$subjectMsg = [ "nstab-$subjectId" ];
			if ( $subjectPage->isMainPage() ) {
				array_unshift( $subjectMsg, 'mainpage-nstab' );
			}
			$content_navigation['namespaces'][$subjectId] = $this->tabAction(
				$subjectPage, $subjectMsg, !$isTalk && !$preventActiveTabs, '', $userCanRead
			);
			$content_navigation['namespaces'][$subjectId]['context'] = 'subject';
			$content_navigation['namespaces'][$talkId] = $this->tabAction(
				$talkPage, [ "nstab-$talkId", 'talk' ], $isTalk && !$preventActiveTabs, '', $userCanRead
			);
			$content_navigation['namespaces'][$talkId]['context'] = 'talk';

			if ( $userCanRead ) {
				// Adds "view" view link
				if ( $title->isKnown() ) {
					$content_navigation['views']['view'] = $this->tabAction(
						$isTalk ? $talkPage : $subjectPage,
						[ "$skname-view-view", 'view' ],
						( $onPage && ( $action == 'view' || $action == 'purge' ) ), '', true
					);
					// signal to hide this from simple content_actions
					$content_navigation['views']['view']['redundant'] = true;
				}

				$page = $this->canUseWikiPage() ? $this->getWikiPage() : false;
				$isRemoteContent = $page && !$page->isLocal();

				// If it is a non-local file, show a link to the file in its own repository
				// @todo abstract this for remote content that isn't a file
				if ( $isRemoteContent ) {
					$content_navigation['views']['view-foreign'] = [
						'class' => '',
						'text' => wfMessageFallback( "$skname-view-foreign", 'view-foreign' )->
							setContext( $this->getContext() )->
							params( $page->getWikiDisplayName() )->text(),
						'href' => $page->getSourceURL(),
						'primary' => false,
					];
				}

				// Checks if user can edit the current page if it exists or create it otherwise
				if ( $title->quickUserCan( 'edit', $user )
					&& ( $title->exists() || $title->quickUserCan( 'create', $user ) )
				) {
					// Builds CSS class for talk page links
					$isTalkClass = $isTalk ? ' istalk' : '';
					// Whether the user is editing the page
					$isEditing = $onPage && ( $action == 'edit' || $action == 'submit' );
					// Whether to show the "Add a new section" tab
					// Checks if this is a current rev of talk page and is not forced to be hidden
					$showNewSection = !$out->forceHideNewSectionLink()
						&& ( ( $isTalk && $this->isRevisionCurrent() ) || $out->showNewSectionLink() );
					$section = $request->getVal( 'section' );

					if ( $title->exists()
						|| ( $title->getNamespace() == NS_MEDIAWIKI
							&& $title->getDefaultMessageText() !== false
						)
					) {
						$msgKey = $isRemoteContent ? 'edit-local' : 'edit';
					} else {
						$msgKey = $isRemoteContent ? 'create-local' : 'create';
					}
					$content_navigation['views']['edit'] = [
						'class' => ( $isEditing && ( $section !== 'new' || !$showNewSection )
							? 'selected'
							: ''
						) . $isTalkClass,
						'text' => wfMessageFallback( "$skname-view-$msgKey", $msgKey )
							->setContext( $this->getContext() )->text(),
						'href' => $title->getLocalURL( $this->editUrlOptions() ),
						'primary' => !$isRemoteContent, // don't collapse this in vector
					];

					// section link
					if ( $showNewSection ) {
						// Adds new section link
						// $content_navigation['actions']['addsection']
						$content_navigation['views']['addsection'] = [
							'class' => ( $isEditing && $section == 'new' ) ? 'selected' : false,
							'text' => wfMessageFallback( "$skname-action-addsection", 'addsection' )
								->setContext( $this->getContext() )->text(),
							'href' => $title->getLocalURL( 'action=edit&section=new' )
						];
					}
				// Checks if the page has some kind of viewable source content
				} elseif ( $title->hasSourceText() ) {
					// Adds view source view link
					$content_navigation['views']['viewsource'] = [
						'class' => ( $onPage && $action == 'edit' ) ? 'selected' : false,
						'text' => wfMessageFallback( "$skname-action-viewsource", 'viewsource' )
							->setContext( $this->getContext() )->text(),
						'href' => $title->getLocalURL( $this->editUrlOptions() ),
						'primary' => true, // don't collapse this in vector
					];
				}

				// Checks if the page exists
				if ( $title->exists() ) {
					// Adds history view link
					$content_navigation['views']['history'] = [
						'class' => ( $onPage && $action == 'history' ) ? 'selected' : false,
						'text' => wfMessageFallback( "$skname-view-history", 'history_short' )
							->setContext( $this->getContext() )->text(),
						'href' => $title->getLocalURL( 'action=history' ),
					];

					if ( $title->quickUserCan( 'delete', $user ) ) {
						$content_navigation['actions']['delete'] = [
							'class' => ( $onPage && $action == 'delete' ) ? 'selected' : false,
							'text' => wfMessageFallback( "$skname-action-delete", 'delete' )
								->setContext( $this->getContext() )->text(),
							'href' => $title->getLocalURL( 'action=delete' )
						];
					}

					if ( $title->quickUserCan( 'move', $user ) ) {
						$moveTitle = SpecialPage::getTitleFor( 'Movepage', $title->getPrefixedDBkey() );
						$content_navigation['actions']['move'] = [
							'class' => $this->getTitle()->isSpecial( 'Movepage' ) ? 'selected' : false,
							'text' => wfMessageFallback( "$skname-action-move", 'move' )
								->setContext( $this->getContext() )->text(),
							'href' => $moveTitle->getLocalURL()
						];
					}
				} else {
					// article doesn't exist or is deleted
					if ( $user->isAllowed( 'deletedhistory' ) ) {
						$n = $title->isDeleted();
						if ( $n ) {
							$undelTitle = SpecialPage::getTitleFor( 'Undelete', $title->getPrefixedDBkey() );
							// If the user can't undelete but can view deleted
							// history show them a "View .. deleted" tab instead.
							$msgKey = $user->isAllowed( 'undelete' ) ? 'undelete' : 'viewdeleted';
							$content_navigation['actions']['undelete'] = [
								'class' => $this->getTitle()->isSpecial( 'Undelete' ) ? 'selected' : false,
								'text' => wfMessageFallback( "$skname-action-$msgKey", "{$msgKey}_short" )
									->setContext( $this->getContext() )->numParams( $n )->text(),
								'href' => $undelTitle->getLocalURL()
							];
						}
					}
				}

				if ( $title->quickUserCan( 'protect', $user ) && $title->getRestrictionTypes() &&
					MWNamespace::getRestrictionLevels( $title->getNamespace(), $user ) !== [ '' ]
				) {
					$mode = $title->isProtected() ? 'unprotect' : 'protect';
					$content_navigation['actions'][$mode] = [
						'class' => ( $onPage && $action == $mode ) ? 'selected' : false,
						'text' => wfMessageFallback( "$skname-action-$mode", $mode )
							->setContext( $this->getContext() )->text(),
						'href' => $title->getLocalURL( "action=$mode" )
					];
				}

				// Checks if the user is logged in
				if ( $this->loggedin && $user->isAllowedAll( 'viewmywatchlist', 'editmywatchlist' ) ) {
					/**
					 * The following actions use messages which, if made particular to
					 * the any specific skins, would break the Ajax code which makes this
					 * action happen entirely inline. OutputPage::getJSVars
					 * defines a set of messages in a javascript object - and these
					 * messages are assumed to be global for all skins. Without making
					 * a change to that procedure these messages will have to remain as
					 * the global versions.
					 */
					$mode = $user->isWatched( $title ) ? 'unwatch' : 'watch';
					$content_navigation['actions'][$mode] = [
						'class' => 'mw-watchlink ' . (
							$onPage && ( $action == 'watch' || $action == 'unwatch' ) ? 'selected' : ''
						),
						// uses 'watch' or 'unwatch' message
						'text' => $this->msg( $mode )->text(),
						'href' => $title->getLocalURL( [ 'action' => $mode ] ),
						// Set a data-mw=interface attribute, which the mediawiki.page.ajax
						// module will look for to make sure it's a trusted link
						'data' => [
							'mw' => 'interface',
						],
					];
				}
			}

			// Avoid PHP 7.1 warning of passing $this by reference
			$skinTemplate = $this;
			Hooks::runWithoutAbort(
				'SkinTemplateNavigation',
				[ &$skinTemplate, &$content_navigation ]
			);

			if ( $userCanRead && !$wgDisableLangConversion ) {
				$pageLang = $title->getPageLanguage();
				// Gets list of language variants
				$variants = $pageLang->getVariants();
				// Checks that language conversion is enabled and variants exist
				// And if it is not in the special namespace
				if ( count( $variants ) > 1 ) {
					// Gets preferred variant (note that user preference is
					// only possible for wiki content language variant)
					$preferred = $pageLang->getPreferredVariant();
					if ( Action::getActionName( $this ) === 'view' ) {
						$params = $request->getQueryValues();
						unset( $params['title'] );
					} else {
						$params = [];
					}
					// Loops over each variant
					foreach ( $variants as $code ) {
						// Gets variant name from language code
						$varname = $pageLang->getVariantname( $code );
						// Appends variant link
						$content_navigation['variants'][] = [
							'class' => ( $code == $preferred ) ? 'selected' : false,
							'text' => $varname,
							'href' => $title->getLocalURL( [ 'variant' => $code ] + $params ),
							'lang' => LanguageCode::bcp47( $code ),
							'hreflang' => LanguageCode::bcp47( $code ),
						];
					}
				}
			}
		} else {
			// If it's not content, it's got to be a special page
			$content_navigation['namespaces']['special'] = [
				'class' => 'selected',
				'text' => $this->msg( 'nstab-special' )->text(),
				'href' => $request->getRequestURL(), // @see: T4457, T4510
				'context' => 'subject'
			];

			// Avoid PHP 7.1 warning of passing $this by reference
			$skinTemplate = $this;
			Hooks::runWithoutAbort( 'SkinTemplateNavigation::SpecialPage',
				[ &$skinTemplate, &$content_navigation ] );
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$skinTemplate = $this;
		// Equiv to SkinTemplateContentActions
		Hooks::runWithoutAbort( 'SkinTemplateNavigation::Universal',
			[ &$skinTemplate, &$content_navigation ] );

		// Setup xml ids and tooltip info
		foreach ( $content_navigation as $section => &$links ) {
			foreach ( $links as $key => &$link ) {
				$xmlID = $key;
				if ( isset( $link['context'] ) && $link['context'] == 'subject' ) {
					$xmlID = 'ca-nstab-' . $xmlID;
				} elseif ( isset( $link['context'] ) && $link['context'] == 'talk' ) {
					$xmlID = 'ca-talk';
					$link['rel'] = 'discussion';
				} elseif ( $section == 'variants' ) {
					$xmlID = 'ca-varlang-' . $xmlID;
				} else {
					$xmlID = 'ca-' . $xmlID;
				}
				$link['id'] = $xmlID;
			}
		}

		# We don't want to give the watch tab an accesskey if the
		# page is being edited, because that conflicts with the
		# accesskey on the watch checkbox.  We also don't want to
		# give the edit tab an accesskey, because that's fairly
		# superfluous and conflicts with an accesskey (Ctrl-E) often
		# used for editing in Safari.
		if ( in_array( $action, [ 'edit', 'submit' ] ) ) {
			if ( isset( $content_navigation['views']['edit'] ) ) {
				$content_navigation['views']['edit']['tooltiponly'] = true;
			}
			if ( isset( $content_navigation['actions']['watch'] ) ) {
				$content_navigation['actions']['watch']['tooltiponly'] = true;
			}
			if ( isset( $content_navigation['actions']['unwatch'] ) ) {
				$content_navigation['actions']['unwatch']['tooltiponly'] = true;
			}
		}

		return $content_navigation;
	}

	/**
	 * an array of edit links by default used for the tabs
	 * @param array $content_navigation
	 * @return array
	 */
	private function buildContentActionUrls( $content_navigation ) {
		// content_actions has been replaced with content_navigation for backwards
		// compatibility and also for skins that just want simple tabs content_actions
		// is now built by flattening the content_navigation arrays into one

		$content_actions = [];

		foreach ( $content_navigation as $links ) {
			foreach ( $links as $key => $value ) {
				if ( isset( $value['redundant'] ) && $value['redundant'] ) {
					// Redundant tabs are dropped from content_actions
					continue;
				}

				// content_actions used to have ids built using the "ca-$key" pattern
				// so the xmlID based id is much closer to the actual $key that we want
				// for that reason we'll just strip out the ca- if present and use
				// the latter potion of the "id" as the $key
				if ( isset( $value['id'] ) && substr( $value['id'], 0, 3 ) == 'ca-' ) {
					$key = substr( $value['id'], 3 );
				}

				if ( isset( $content_actions[$key] ) ) {
					wfDebug( __METHOD__ . ": Found a duplicate key for $key while flattening " .
						"content_navigation into content_actions.\n" );
					continue;
				}

				$content_actions[$key] = $value;
			}
		}

		return $content_actions;
	}

	/**
	 * build array of common navigation links
	 * @return array
	 */
	protected function buildNavUrls() {
		global $wgUploadNavigationUrl;

		$out = $this->getOutput();
		$request = $this->getRequest();

		$nav_urls = [];
		$nav_urls['mainpage'] = [ 'href' => self::makeMainPageUrl() ];
		if ( $wgUploadNavigationUrl ) {
			$nav_urls['upload'] = [ 'href' => $wgUploadNavigationUrl ];
		} elseif ( UploadBase::isEnabled() && UploadBase::isAllowed( $this->getUser() ) === true ) {
			$nav_urls['upload'] = [ 'href' => self::makeSpecialUrl( 'Upload' ) ];
		} else {
			$nav_urls['upload'] = false;
		}
		$nav_urls['specialpages'] = [ 'href' => self::makeSpecialUrl( 'Specialpages' ) ];

		$nav_urls['print'] = false;
		$nav_urls['permalink'] = false;
		$nav_urls['info'] = false;
		$nav_urls['whatlinkshere'] = false;
		$nav_urls['recentchangeslinked'] = false;
		$nav_urls['contributions'] = false;
		$nav_urls['log'] = false;
		$nav_urls['blockip'] = false;
		$nav_urls['emailuser'] = false;
		$nav_urls['userrights'] = false;

		// A print stylesheet is attached to all pages, but nobody ever
		// figures that out. :)  Add a link...
		if ( !$out->isPrintable() && ( $out->isArticle() || $this->getTitle()->isSpecialPage() ) ) {
			$nav_urls['print'] = [
				'text' => $this->msg( 'printableversion' )->text(),
				'href' => $this->getTitle()->getLocalURL(
					$request->appendQueryValue( 'printable', 'yes' ) )
			];
		}

		if ( $out->isArticle() ) {
			// Also add a "permalink" while we're at it
			$revid = $this->getRevisionId();
			if ( $revid ) {
				$nav_urls['permalink'] = [
					'text' => $this->msg( 'permalink' )->text(),
					'href' => $this->getTitle()->getLocalURL( "oldid=$revid" )
				];
			}

			// Avoid PHP 7.1 warning of passing $this by reference
			$skinTemplate = $this;
			// Use the copy of revision ID in case this undocumented, shady hook tries to mess with internals
			Hooks::run( 'SkinTemplateBuildNavUrlsNav_urlsAfterPermalink',
				[ &$skinTemplate, &$nav_urls, &$revid, &$revid ] );
		}

		if ( $out->isArticleRelated() ) {
			$nav_urls['whatlinkshere'] = [
				'href' => SpecialPage::getTitleFor( 'Whatlinkshere', $this->thispage )->getLocalURL()
			];

			$nav_urls['info'] = [
				'text' => $this->msg( 'pageinfo-toolboxlink' )->text(),
				'href' => $this->getTitle()->getLocalURL( "action=info" )
			];

			if ( $this->getTitle()->exists() || $this->getTitle()->inNamespace( NS_CATEGORY ) ) {
				$nav_urls['recentchangeslinked'] = [
					'href' => SpecialPage::getTitleFor( 'Recentchangeslinked', $this->thispage )->getLocalURL()
				];
			}
		}

		$user = $this->getRelevantUser();
		if ( $user ) {
			$rootUser = $user->getName();

			$nav_urls['contributions'] = [
				'text' => $this->msg( 'contributions', $rootUser )->text(),
				'href' => self::makeSpecialUrlSubpage( 'Contributions', $rootUser ),
				'tooltip-params' => [ $rootUser ],
			];

			$nav_urls['log'] = [
				'href' => self::makeSpecialUrlSubpage( 'Log', $rootUser )
			];

			if ( $this->getUser()->isAllowed( 'block' ) ) {
				$nav_urls['blockip'] = [
					'text' => $this->msg( 'blockip', $rootUser )->text(),
					'href' => self::makeSpecialUrlSubpage( 'Block', $rootUser )
				];
			}

			if ( $this->showEmailUser( $user ) ) {
				$nav_urls['emailuser'] = [
					'text' => $this->msg( 'tool-link-emailuser', $rootUser )->text(),
					'href' => self::makeSpecialUrlSubpage( 'Emailuser', $rootUser ),
					'tooltip-params' => [ $rootUser ],
				];
			}

			if ( !$user->isAnon() ) {
				$sur = new UserrightsPage;
				$sur->setContext( $this->getContext() );
				$canChange = $sur->userCanChangeRights( $user );
				$nav_urls['userrights'] = [
					'text' => $this->msg(
						$canChange ? 'tool-link-userrights' : 'tool-link-userrights-readonly',
						$rootUser
					)->text(),
					'href' => self::makeSpecialUrlSubpage( 'Userrights', $rootUser )
				];
			}
		}

		return $nav_urls;
	}

	/**
	 * Generate strings used for xml 'id' names
	 * @return string
	 */
	protected function getNameSpaceKey() {
		return $this->getTitle()->getNamespaceKey();
	}
}
