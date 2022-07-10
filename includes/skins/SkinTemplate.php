<?php
/**
 * Copyright © Gabriel Wicke -- http://www.aulinx.de/
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

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;

/**
 * Base class for QuickTemplate-based skins.
 *
 * The template data is filled in SkinTemplate::prepareQuickTemplate.
 *
 * @stable to extend
 * @ingroup Skins
 */
class SkinTemplate extends Skin {
	/**
	 * @var string For QuickTemplate, the name of the subclass which will
	 *   actually fill the template.
	 */
	public $template;

	public $thispage;
	public $titletxt;
	public $userpage;
	public $thisquery;
	// TODO: Rename this to $isRegistered (but that's a breaking change)
	public $loggedin;
	public $username;
	public $userpageUrlDetails;
	private $templateContextSet = false;
	/** @var array|null */
	private $contentNavigationCached;

	/**
	 * Create the template engine object; we feed it a bunch of data
	 * and eventually it spits out some HTML. Should have interface
	 * roughly equivalent to PHPTAL 0.7.
	 *
	 * @param string $classname
	 * @return QuickTemplate
	 */
	protected function setupTemplate( $classname ) {
		return new $classname( $this->getConfig() );
	}

	/**
	 * @return QuickTemplate
	 */
	protected function setupTemplateForOutput() {
		$this->setupTemplateContext();
		$template = $this->options['template'] ?? $this->template;
		if ( !$template ) {
			throw new RuntimeException(
				'SkinTemplate skins must define a `template` either as a public'
					. ' property of by passing in a`template` option to the constructor.'
			);
		}
		$tpl = $this->setupTemplate( $template );
		return $tpl;
	}

	/**
	 * Setup class properties that are necessary prior to calling
	 * setupTemplateForOutput. It must be called inside
	 * prepareQuickTemplate.
	 * This function may set local class properties that will be used
	 * by other methods, but should not make assumptions about the
	 * implementation of setupTemplateForOutput
	 * @since 1.35
	 */
	final protected function setupTemplateContext() {
		if ( $this->templateContextSet ) {
			return;
		}

		$request = $this->getRequest();
		$user = $this->getUser();
		$title = $this->getTitle();
		$this->thispage = $title->getPrefixedDBkey();
		$this->titletxt = $title->getPrefixedText();
		$userpageTitle = $user->getUserPage();
		$this->userpage = $userpageTitle->getPrefixedText();
		$query = [];
		if ( !$request->wasPosted() ) {
			$query = $request->getValues();
			unset( $query['title'] );
			unset( $query['returnto'] );
			unset( $query['returntoquery'] );
		}
		$this->thisquery = wfArrayToCgi( $query );
		$this->loggedin = $user->isRegistered();
		$this->username = $user->getName();

		if ( $this->loggedin ) {
			$this->userpageUrlDetails = self::makeUrlDetails( $userpageTitle );
		} else {
			# This won't be used in the standard skins, but we define it to preserve the interface
			# To save time, we check for existence
			$this->userpageUrlDetails = self::makeKnownUrlDetails( $userpageTitle );
		}

		$this->templateContextSet = true;
	}

	/**
	 * Subclasses not wishing to use the QuickTemplate
	 * render method can rewrite this method, for example to use
	 * TemplateParser::processTemplate
	 * @since 1.35
	 * @return string of complete document HTML to output to the page
	 *  which includes `<!DOCTYPE>` and opening and closing html tags.
	 */
	public function generateHTML() {
		$tpl = $this->prepareQuickTemplate();
		// execute template
		return $tpl->execute();
	}

	/**
	 * Initialize various variables and generate the template
	 * @stable to override
	 */
	public function outputPage() {
		Profiler::instance()->setAllowOutput();
		$out = $this->getOutput();

		$this->initPage( $out );
		$out->addJsConfigVars( $this->getJsConfigVars() );

		// result may be an error
		echo $this->generateHTML();
	}

	/**
	 * Get template representation of the footer.
	 * @since 1.35
	 * @return array
	 */
	protected function getFooterIcons() {
		$config = $this->getConfig();

		$footericons = [];
		foreach ( $config->get( 'FooterIcons' ) as $footerIconsKey => &$footerIconsBlock ) {
			if ( count( $footerIconsBlock ) > 0 ) {
				$footericons[$footerIconsKey] = [];
				foreach ( $footerIconsBlock as &$footerIcon ) {
					if ( isset( $footerIcon['src'] ) ) {
						if ( !isset( $footerIcon['width'] ) ) {
							$footerIcon['width'] = 88;
						}
						if ( !isset( $footerIcon['height'] ) ) {
							$footerIcon['height'] = 31;
						}
					}

					// Only output icons which have an image.
					// For historic reasons this mimics the `icononly` option
					// for BaseTemplate::getFooterIcons.
					// In some cases the icon may be an empty array.
					// Filter these out. (See T269776)
					if ( is_string( $footerIcon ) || isset( $footerIcon['src'] ) ) {
						$footericons[$footerIconsKey][] = $footerIcon;
					}
				}

				// If no valid icons with images were added, unset the parent array
				// Should also prevent empty arrays from when no copyright is set.
				if ( !count( $footericons[$footerIconsKey] ) ) {
					unset( $footericons[$footerIconsKey] );
				}
			}
		}
		return $footericons;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData() {
		return parent::getTemplateData() + [
			// Data objects
			'data-search-box' => $this->buildSearchProps(),
		] + $this->getPortletsTemplateData() + $this->getFooterTemplateData();
	}

	/**
	 * initialize various variables and generate the template
	 *
	 * @since 1.23
	 * @return QuickTemplate The template to be executed by outputPage
	 */
	protected function prepareQuickTemplate() {
		$title = $this->getTitle();
		$request = $this->getRequest();
		$out = $this->getOutput();
		$config = $this->getConfig();
		$tpl = $this->setupTemplateForOutput();

		$tpl->set( 'title', $out->getPageTitle() );
		$tpl->set( 'pagetitle', $out->getHTMLTitle() );
		$tpl->set( 'displaytitle', $out->mPageLinkTitle );

		$tpl->set( 'thispage', $this->thispage );
		$tpl->set( 'titleprefixeddbkey', $this->thispage );
		$tpl->set( 'titletext', $title->getText() );
		$tpl->set( 'articleid', $title->getArticleID() );

		$tpl->set( 'isarticle', $out->isArticle() );

		$tpl->set( 'subtitle', $this->prepareSubtitle() );
		$tpl->set( 'undelete', $this->prepareUndeleteLink() );

		$tpl->set( 'catlinks', $this->getCategories() );
		$feeds = $this->buildFeedUrls();
		$tpl->set( 'feeds', count( $feeds ) ? $feeds : false );

		$tpl->set( 'mimetype', $config->get( 'MimeType' ) );
		$tpl->set( 'charset', 'UTF-8' );
		$tpl->set( 'wgScript', $config->get( 'Script' ) );
		$tpl->set( 'skinname', $this->skinname );
		$tpl->set( 'skinclass', static::class );
		$tpl->set( 'skin', $this );
		$tpl->set( 'stylename', $this->stylename );
		$tpl->set( 'printable', $out->isPrintable() );
		$tpl->set( 'handheld', $request->getBool( 'handheld' ) );
		$tpl->set( 'loggedin', $this->loggedin );
		$tpl->set( 'notspecialpage', !$title->isSpecialPage() );

		$searchTitle = SpecialPage::newSearchPage( $this->getUser() );
		$searchLink = $searchTitle->getLocalURL();
		$tpl->set( 'searchaction', $searchLink );
		$tpl->deprecate( 'searchaction', '1.36' );

		$tpl->set( 'searchtitle', $searchTitle->getPrefixedDBkey() );
		$tpl->set( 'search', trim( $request->getVal( 'search', '' ) ) );
		$tpl->set( 'stylepath', $config->get( 'StylePath' ) );
		$tpl->set( 'articlepath', $config->get( 'ArticlePath' ) );
		$tpl->set( 'scriptpath', $config->get( 'ScriptPath' ) );
		$tpl->set( 'serverurl', $config->get( 'Server' ) );
		$logos = ResourceLoaderSkinModule::getAvailableLogos( $config );
		$tpl->set( 'logopath', $logos['1x'] );
		$tpl->set( 'sitename', $config->get( 'Sitename' ) );

		$userLang = $this->getLanguage();
		$userLangCode = $userLang->getHtmlCode();
		$userLangDir = $userLang->getDir();

		$tpl->set( 'lang', $userLangCode );
		$tpl->set( 'dir', $userLangDir );
		$tpl->set( 'rtl', $userLang->isRTL() );

		$tpl->set( 'showjumplinks', true ); // showjumplinks preference has been removed
		$tpl->set( 'username', $this->loggedin ? $this->username : null );
		$tpl->set( 'userpage', $this->userpage );
		$tpl->set( 'userpageurl', $this->userpageUrlDetails['href'] );
		$tpl->set( 'userlang', $userLangCode );

		// Users can have their language set differently than the
		// content of the wiki. For these users, tell the web browser
		// that interface elements are in a different language.
		$tpl->set( 'userlangattributes', $this->prepareUserLanguageAttributes() );
		$tpl->set( 'specialpageattributes', '' ); # obsolete
		// Used by VectorBeta to insert HTML before content but after the
		// heading for the page title. Defaults to empty string.
		$tpl->set( 'prebodyhtml', '' );

		$tpl->set( 'newtalk', $this->getNewtalks() );
		$tpl->set( 'logo', $this->logoText() );

		$footerData = $this->getFooterLinks();
		$tpl->set( 'copyright', $footerData['info']['copyright'] ?? false );
		// No longer used
		$tpl->set( 'viewcount', false );
		$tpl->set( 'lastmod', $footerData['info']['lastmod'] ?? false );
		$tpl->set( 'credits', $footerData['info']['credits'] ?? false );
		$tpl->set( 'numberofwatchingusers', false );

		$tpl->set( 'copyrightico', BaseTemplate::getCopyrightIconHTML( $config, $this ) );
		$tpl->deprecate( 'copyrightico', '1.37' ); // [[phab:T290583]]
		$poweredBy = BaseTemplate::getPoweredByHTML( $config );
		// Run deprecated hook.
		$this->getHookRunner()->onSkinGetPoweredBy( $poweredBy, $this );
		$tpl->set( 'poweredbyico', $poweredBy );
		$tpl->deprecate( 'poweredbyico', '1.37' ); // [[phab:T290583]]

		$tpl->set( 'disclaimer', $footerData['places']['disclaimer'] ?? false );
		$tpl->set( 'privacy', $footerData['places']['privacy'] ?? false );
		$tpl->set( 'about', $footerData['places']['about'] ?? false );

		// Flatten for compat with the 'footerlinks' key in QuickTemplate-based skins.
		$flattenedfooterlinks = [];
		foreach ( $footerData as $category => $links ) {
			$flattenedfooterlinks[$category] = array_keys( $links );
			foreach ( $links as $key => $value ) {
				// For full support with BaseTemplate we also need to
				// copy over the keys.
				$tpl->set( $key, $value );
			}
		}
		$tpl->set( 'footerlinks', $flattenedfooterlinks );
		$tpl->set( 'footericons', $this->getFooterIcons() );

		$tpl->set( 'indicators', $out->getIndicators() );

		$tpl->set( 'sitenotice', $this->getSiteNotice() );
		$tpl->set( 'printfooter', $this->printSource() );
		// Wrap the bodyText with #mw-content-text element
		$tpl->set( 'bodytext', $this->wrapHTML( $title, $out->getHTML() ) );

		$tpl->set( 'language_urls', $this->getLanguages() ?: false );

		$content_navigation = $this->buildContentNavigationUrlsInternal();
		# Personal toolbar
		$tpl->set( 'personal_urls', $this->makeSkinTemplatePersonalUrls( $content_navigation ) );
		// The user-menu, notifications, and user-interface-preferences are new content navigation entries which aren't
		// expected to be part of content_navigation or content_actions. Adding them in there breaks skins that do not
		// expect it.
		unset(
			$content_navigation['user-menu'],
			$content_navigation['notifications'],
			$content_navigation['user-page'],
			$content_navigation['user-interface-preferences'],
			$content_navigation['category-normal'],
			$content_navigation['category-hidden']
		);
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
		$tpl->set( 'debughtml', MWDebug::getHTMLDebugLog() );
		$tpl->set( 'reporttime', wfReportTime( $out->getCSP()->getNonce() ) );

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
	 * @deprecated since 1.35, hard deprecated since 1.38
	 * Use SkinTemplate::makePersonalToolsList() instead
	 * @return string
	 */
	public function getPersonalToolsList() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->makePersonalToolsList();
	}

	/**
	 * Get the HTML for the personal tools list
	 * @since 1.31
	 *
	 * @param array|null $personalTools
	 * @param array $options
	 * @return string
	 */
	public function makePersonalToolsList( $personalTools = null, $options = [] ) {
		$html = '';

		if ( $personalTools === null ) {
			$personalTools = $this->getPersonalToolsForMakeListItem(
				$this->buildPersonalUrls()
			);
		}

		foreach ( $personalTools as $key => $item ) {
			$html .= $this->makeListItem( $key, $item, $options );
		}

		return $html;
	}

	/**
	 * Get personal tools for the user
	 *
	 * @since 1.31
	 *
	 * @return array[]
	 */
	public function getStructuredPersonalTools() {
		return $this->getPersonalToolsForMakeListItem(
			$this->buildPersonalUrls()
		);
	}

	/**
	 * Build array of urls for personal toolbar
	 *
	 * @param bool $includeNotifications Since 1.36, notifications are optional
	 * @return array
	 */
	protected function buildPersonalUrls( bool $includeNotifications = true ) {
		$this->setupTemplateContext();
		$title = $this->getTitle();
		$request = $this->getRequest();
		$pageurl = $title->getLocalURL();
		$services = MediaWikiServices::getInstance();
		$authManager = $services->getAuthManager();
		$permissionManager = $services->getPermissionManager();
		$returnto = $this->getReturnToParam();

		/* set up the default links for the personal toolbar */
		$personal_urls = [];

		if ( $this->loggedin ) {
			$personal_urls['userpage'] = $this->buildPersonalPageItem( 'pt-userpage' );

			// Merge notifications into the personal menu for older skins.
			if ( $includeNotifications ) {
				$contentNavigation = $this->buildContentNavigationUrlsInternal();

				$personal_urls += $contentNavigation['notifications'];
			}

			$usertalkUrlDetails = $this->makeTalkUrlDetails( $this->userpage );
			$personal_urls['mytalk'] = [
				'text' => $this->msg( 'mytalk' )->text(),
				'href' => &$usertalkUrlDetails['href'],
				'class' => $usertalkUrlDetails['exists'] ? false : 'new',
				'exists' => $usertalkUrlDetails['exists'],
				'active' => ( $usertalkUrlDetails['href'] == $pageurl ),
				'icon' => 'userTalk'
			];
			$href = self::makeSpecialUrl( 'Preferences' );
			$personal_urls['preferences'] = [
				'text' => $this->msg( 'mypreferences' )->text(),
				'href' => $href,
				'active' => ( $href == $pageurl ),
				'icon' => 'settings'
			];

			if ( $this->getAuthority()->isAllowed( 'viewmywatchlist' ) ) {
				$href = self::makeSpecialUrl( 'Watchlist' );
				$personal_urls['watchlist'] = [
					'text' => $this->msg( 'mywatchlist' )->text(),
					'href' => $href,
					'active' => ( $href == $pageurl ),
					'icon' => 'watchlist'
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
				list( $spName, $spPar ) =
					MediaWikiServices::getInstance()->getSpecialPageFactory()->
						resolveAlias( $origTitle->getText() );
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
				'active' => $active,
				'icon' => 'userContributions'
			];

			// if we can't set the user, we can't unset it either
			if ( $request->getSession()->canSetUser() ) {
				$personal_urls['logout'] = $this->buildLogoutLinkData();
			}
		} else {
			$useCombinedLoginLink = $this->useCombinedLoginLink();
			$login_url = $this->buildLoginData( $returnto, $useCombinedLoginLink );
			$createaccount_url = $this->buildCreateAccountData( $returnto );

			// No need to show Talk and Contributions to anons if they can't contribute!
			// TODO: easy way to get anon authority!
			if ( $permissionManager->groupHasPermission( '*', 'edit' ) ) {
				// Non interactive placeholder for anonymous users.
				// It's unstyled by default (black color). Skin that
				// needs it, can style it using the 'pt-anonuserpage' id.
				// Skin that does not need it should unset it.
				$personal_urls['anonuserpage'] = [
					'text' => $this->msg( 'notloggedin' )->text(),
				];

				// Because of caching, we can't link directly to the IP talk and
				// contributions pages. Instead we use the special page shortcuts
				// (which work correctly regardless of caching). This means we can't
				// determine whether these links are active or not, but since major
				// skins (MonoBook, Vector) don't use this information, it's not a
				// huge loss.
				$personal_urls['anontalk'] = [
					'text' => $this->msg( 'anontalk' )->text(),
					'href' => self::makeSpecialUrlSubpage( 'Mytalk', false ),
					'active' => false,
					'icon' => 'userTalk'
				];
				$personal_urls['anoncontribs'] = [
					'text' => $this->msg( 'anoncontribs' )->text(),
					'href' => self::makeSpecialUrlSubpage( 'Mycontributions', false ),
					'active' => false,
					'icon' => 'userContributions'
				];
			}

			if (
				$authManager->canCreateAccounts()
				&& $this->getAuthority()->isAllowed( 'createaccount' )
				&& !$useCombinedLoginLink
			) {
				$personal_urls['createaccount'] = $createaccount_url;
			}

			if ( $authManager->canAuthenticateNow() ) {
				// TODO: easy way to get anon authority
				$key = $permissionManager->groupHasPermission( '*', 'read' )
					? 'login'
					: 'login-private';
				$personal_urls[$key] = $login_url;
			}
		}

		$this->getHookRunner()->onPersonalUrls( $personal_urls, $title, $this );

		return $personal_urls;
	}

	/**
	 * Builds query params for the page to return to, used when building links
	 * @unstable
	 *
	 * @return string[]
	 */
	protected function getReturnToParam() {
		$title = $this->getTitle();
		$request = $this->getRequest();

		# Due to T34276, if a user does not have read permissions,
		# $this->getTitle() will just give Special:Badtitle, which is
		# not especially useful as a returnto parameter. Use the title
		# from the request instead, if there was one.
		if ( $this->getAuthority()->isAllowed( 'read' ) ) {
			$page = $title;
		} else {
			$page = Title::newFromText( $request->getVal( 'title', '' ) );
		}
		$page = $request->getVal( 'returnto', $page );
		$returnto = [];
		if ( strval( $page ) !== '' ) {
			$returnto['returnto'] = $page;
			$query = $request->getVal( 'returntoquery', $this->thisquery );
			$paramsArray = wfCgiToArray( $query );
			$query = wfArrayToCgi( $paramsArray );
			if ( $query != '' ) {
				$returnto['returntoquery'] = $query;
			}
		}

		return $returnto;
	}

	/**
	 * Returns if a combined login/signup link will be used
	 * @unstable
	 *
	 * @return bool
	 */
	protected function useCombinedLoginLink() {
		$services = MediaWikiServices::getInstance();
		$authManager = $services->getAuthManager();
		$useCombinedLoginLink = $this->getConfig()->get( 'UseCombinedLoginLink' );
		if ( !$authManager->canCreateAccounts() || !$authManager->canAuthenticateNow() ) {
			// don't show combined login/signup link if one of those is actually not available
			$useCombinedLoginLink = false;
		}

		return $useCombinedLoginLink;
	}

	/**
	 * Build "Login" link
	 * @unstable
	 *
	 * @param string[] $returnto query params for the page to return to
	 * @param bool $useCombinedLoginLink when set a single link to login form will be created
	 *  with alternative label.
	 * @return array
	 */
	protected function buildLoginData( $returnto, $useCombinedLoginLink ) {
		$title = $this->getTitle();

		$loginlink = $this->getAuthority()->isAllowed( 'createaccount' )
			&& $useCombinedLoginLink ? 'nav-login-createaccount' : 'pt-login';

		$login_url = [
			'text' => $this->msg( $loginlink )->text(),
			'href' => self::makeSpecialUrl( 'Userlogin', $returnto ),
			'active' => $title->isSpecial( 'Userlogin' )
				|| $title->isSpecial( 'CreateAccount' ) && $useCombinedLoginLink,
			'icon' => 'logIn'
		];

		return $login_url;
	}

	/**
	 * @param array $links return value from OutputPage::getCategoryLinks
	 * @return array of data
	 */
	private function getCategoryPortletsData( array $links ): array {
		$categories = [];
		foreach ( $links as $group => $links ) {
			$allLinks = [];
			$groupName = 'category-' . $group;
			foreach ( $links as $i => $link ) {
				$allLinks[$groupName . '-' . $i] = [
					'html' => $link,
				];
			}
			$categories[ $groupName ] = $allLinks;
		}
		return $categories;
	}

	/**
	 * @since 1.37 for non-SkinMustache base classes. Available since 1.36 for SkinMustache
	 * @internal read only function, not for extension. Support for extending this method is
	 *  deprecated in 1.38. Please use getTemplateData instead.
	 * @param string $name of the portal e.g. p-personal the name is personal.
	 * @param array $items that are accepted input to Skin::makeListItem
	 * @return array data that can be passed to a Mustache template that
	 *   represents a single menu.
	 */
	protected function getPortletData( $name, array $items ) {
		MWDebug::detectDeprecatedOverride( $this, __CLASS__, 'getPortletData', '1.38' );
		// Monobook and Vector historically render this portal as an element with ID p-cactions
		// This inconsistency is regretful from a code point of view
		// However this ensures compatibility with gadgets.
		// In future we should port p-#cactions to #p-actions and drop this rename.
		if ( $name === 'actions' ) {
			$name = 'cactions';
		}

		// user-menu is the new personal tools, without the notifications.
		// A lot of user code and gadgets relies on it being named personal.
		// This allows it to function as a drop-in replacement.
		if ( $name === 'user-menu' ) {
			$name = 'personal';
		}

		$legacyClasses = '';
		if ( $name === 'category-normal' ) {
			// retain historic category IDs and classes
			$id = 'mw-normal-catlinks';
			$legacyClasses .= ' mw-normal-catlinks';
		} elseif ( $name === 'category-hidden' ) {
			// retain historic category IDs and classes
			$id = 'mw-hidden-catlinks';
			$legacyClasses .= ' mw-hidden-catlinks mw-hidden-cats-hidden';
		} else {
			$id = Sanitizer::escapeIdForAttribute( "p-$name" );
		}

		$data = [
			'id' => $id,
			'class' => 'mw-portlet ' . Sanitizer::escapeClass( "mw-portlet-$name" ) . $legacyClasses,
			'html-tooltip' => Linker::tooltip( $id ),
			'html-items' => '',
			// Will be populated by SkinAfterPortlet hook.
			'html-after-portal' => '',
			'html-before-portal' => '',
		];
		// Run the SkinAfterPortlet
		// hook and if content is added appends it to the html-after-portal
		// for output.
		// Currently in production this supports the wikibase 'edit' link.
		$content = $this->getAfterPortlet( $name );
		if ( $content !== '' ) {
			$data['html-after-portal'] = Html::rawElement(
				'div',
				[
					'class' => [
						'after-portlet',
						Sanitizer::escapeClass( "after-portlet-$name" ),
					],
				],
				$content
			);
		}

		foreach ( $items as $key => $item ) {
			$data['html-items'] .= $this->makeListItem( $key, $item );
		}

		$data['label'] = $this->getPortletLabel( $name );
		$data['is-empty'] = count( $items ) === 0 && $content === '';
		$data['class'] .= $data['is-empty'] ? ' emptyPortlet' : '';
		return $data;
	}

	/**
	 * Extends category links with Skin::getAfterPortlet functionality.
	 * @return string HTML
	 */
	public function getCategoryLinks() {
		$afterPortlet = $this->getPortletsTemplateData()['data-portlets']['data-category-normal']['html-after-portal']
			?? '';
		return parent::getCategoryLinks() . $afterPortlet;
	}

	/**
	 * @param string $name of the portal e.g. p-personal the name is personal.
	 * @return string that is human readable corresponding to the menu
	 */
	private function getPortletLabel( $name ) {
		// For historic reasons for some menu items,
		// there is no language key corresponding with its menu key.
		$mappings = [
			'tb' => 'toolbox',
			'personal' => 'personaltools',
			'lang' => 'otherlanguages',
		];
		$msgObj = $this->msg( $mappings[ $name ] ?? $name );
		// If no message exists fallback to plain text (T252727)
		$labelText = $msgObj->exists() ? $msgObj->text() : $name;
		return $labelText;
	}

	/**
	 * @return array of portlet data for all portlets
	 */
	private function getPortletsTemplateData() {
		$portlets = [];
		$contentNavigation = $this->buildContentNavigationUrlsInternal();
		$sidebar = [];
		$sidebarData = $this->buildSidebar();
		foreach ( $sidebarData as $name => $items ) {
			if ( is_array( $items ) ) {
				// Numeric strings gets an integer when set as key, cast back - T73639
				$name = (string)$name;
				switch ( $name ) {
					// ignore search
					case 'SEARCH':
						break;
					// Map toolbox to `tb` id.
					case 'TOOLBOX':
						$sidebar[] = $this->getPortletData( 'tb', $items );
						break;
					// Languages is no longer be tied to sidebar
					case 'LANGUAGES':
						// The language portal will be added provided either
						// languages exist or there is a value in html-after-portal
						// for example to show the add language wikidata link (T252800)
						$portal = $this->getPortletData( 'lang', $items );
						if ( count( $items ) || $portal['html-after-portal'] ) {
							$portlets['data-languages'] = $portal;
						}
						break;
					default:
						$sidebar[] = $this->getPortletData( $name, $items );
						break;
				}
			}
		}

		foreach ( $contentNavigation as $name => $items ) {
			if ( $name === 'user-menu' ) {
				$items = $this->getPersonalToolsForMakeListItem( $items, true );
			}

			$portlets['data-' . $name] = $this->getPortletData( $name, $items );
		}

		// A menu that includes the notifications. This will be deprecated in future versions
		// of the skin API spec.
		$portlets['data-personal'] = $this->getPortletData(
			'personal',
			$this->getPersonalToolsForMakeListItem(
				$this->injectLegacyMenusIntoPersonalTools( $contentNavigation )
			)
		);

		return [
			'data-portlets' => $portlets,
			'data-portlets-sidebar' => [
				'data-portlets-first' => $sidebar[0] ?? null,
				'array-portlets-rest' => array_slice( $sidebar, 1 ),
			],
		];
	}

	/**
	 * Get rows that make up the footer
	 * @return array for use in Mustache template describing the footer elements.
	 */
	private function getFooterTemplateData(): array {
		$data = [];
		foreach ( $this->getFooterLinks() as $category => $links ) {
			$items = [];
			$rowId = "footer-$category";

			foreach ( $links as $key => $link ) {
				// Link may be null. If so don't include it.
				if ( $link ) {
					$items[] = [
						// Monobook uses name rather than id.
						// We may want to change monobook to adhere to the same contract however.
						'name' => $key,
						'id' => "$rowId-$key",
						'html' => $link,
					];
				}
			}

			$data['data-' . $category] = [
				'id' => $rowId,
				'className' => null,
				'array-items' => $items
			];
		}

		// If footer icons are enabled append to the end of the rows
		$footerIcons = $this->getFooterIcons();

		if ( count( $footerIcons ) > 0 ) {
			$icons = [];
			foreach ( $footerIcons as $blockName => $blockIcons ) {
				$html = '';
				foreach ( $blockIcons as $key => $icon ) {
					$html .= $this->makeFooterIcon( $icon );
				}
				// For historic reasons this mimics the `icononly` option
				// for BaseTemplate::getFooterIcons. Empty rows should not be output.
				if ( $html ) {
					$block = htmlspecialchars( $blockName );
					$icons[] = [
						'name' => $block,
						'id' => 'footer-' . $block . 'ico',
						'html' => $html,
					];
				}
			}

			// Empty rows should not be output.
			// This is how Vector has behaved historically but we can revisit later if necessary.
			if ( count( $icons ) > 0 ) {
				$data['data-icons'] = [
					'id' => 'footer-icons',
					'className' => 'noprint',
					'array-items' => $icons,
				];
			}
		}

		return [
			'data-footer' => $data,
		];
	}

	/**
	 * @return array
	 */
	private function buildSearchProps(): array {
		$config = $this->getConfig();
		$searchButtonAttributes = [
			'class' => 'searchButton'
		];
		$fallbackButtonAttributes = [
			'class' => 'searchButton mw-fallbackSearchButton'
		];
		$buttonAttributes = [
			'type' => 'submit',
		];

		$props = [
			'form-action' => $config->get( 'Script' ),
			'html-button-search-fallback' => $this->makeSearchButton(
				'fulltext',
				$fallbackButtonAttributes + [
					'id' => 'mw-searchButton',
				]
			),
			'html-button-search' => $this->makeSearchButton(
				'go',
				$searchButtonAttributes + [
					'id' => 'searchButton',
				]
			),
			'html-input' => $this->makeSearchInput( [ 'id' => 'searchInput' ] ),
			'msg-search' => $this->msg( 'search' )->text(),
			'page-title' => SpecialPage::newSearchPage(
				$this->getUser()
			)->getPrefixedDBkey(),
			// @since 1.38
			'html-button-go-attributes' => Html::expandAttributes(
				$searchButtonAttributes + $buttonAttributes + [
					'name' => 'go',
				] + Linker::tooltipAndAccesskeyAttribs( 'search-go' )
			),
			// @since 1.38
			'html-button-fulltext-attributes' => Html::expandAttributes(
				$fallbackButtonAttributes + $buttonAttributes + [
					'name' => 'fulltext'
				] + Linker::tooltipAndAccesskeyAttribs( 'search-fulltext' )
			),
			// @since 1.38
			'html-input-attributes' => Html::expandAttributes(
				$this->getSearchInputAttributes( [] )
			),
		];

		return $props;
	}

	/**
	 * Build data required for "Logout" link.
	 *
	 * @unstable
	 *
	 * @since 1.37
	 *
	 * @return array Array of data required to create a logout link.
	 */
	final protected function buildLogoutLinkData() {
		$title = $this->getTitle();
		$returnto = $this->getReturnToParam();

		return [
			'text' => $this->msg( 'pt-userlogout' )->text(),
			'data-mw' => 'interface',
			'href' => self::makeSpecialUrl( 'Userlogout',
				// Note: userlogout link must always contain an & character, otherwise we might not be able
				// to detect a buggy precaching proxy (T19790)
				( $title->isSpecial( 'Preferences' ) ? [] : $returnto ) ),
			'active' => false,
			'icon' => 'logOut'
		];
	}

	/**
	 * Build "Create Account" link
	 * @unstable
	 *
	 * @param string[] $returnto query params for the page to return to
	 * @return array
	 */
	protected function buildCreateAccountData( $returnto ) {
		$title = $this->getTitle();

		$createaccount_url = [
			'text' => $this->msg( 'pt-createaccount' )->text(),
			'href' => self::makeSpecialUrl( 'CreateAccount', $returnto ),
			'active' => $title->isSpecial( 'CreateAccount' ),
			'icon' => 'userAvatar'
		];

		return $createaccount_url;
	}

	/**
	 * Build a personal page link.
	 * @param string $id of user page item to be output in HTML attribute (optional)
	 * @return array
	 */
	protected function buildPersonalPageItem( $id = 'pt-userpage' ): array {
		// Build the personal page link array.
		return [
			'text' => $this->username,
			'id' => $id,
			'href' => &$this->userpageUrlDetails['href'],
			'link-class' => $this->userpageUrlDetails['exists'] ? [] : [ 'new' ],
			'exists' => $this->userpageUrlDetails['exists'],
			'active' => ( $this->userpageUrlDetails['href'] == $this->getTitle()->getLocalURL() ),
			'icon' => 'userAvatar',
			'dir' => 'auto'
		];
	}

	/**
	 * Builds an array with tab definition
	 *
	 * @param Title $title Page Where the tab links to
	 * @param string|string[]|MessageSpecifier $message Message or an array of message keys
	 *   (will fall back)
	 * @param bool $selected Display the tab as selected
	 * @param string $query Query string attached to tab URL
	 * @param bool $checkEdit Check if $title exists and mark with .new if one doesn't
	 *
	 * @return array
	 * @param-taint $message tainted
	 */
	public function tabAction( $title, $message, $selected, $query = '', $checkEdit = false ) {
		$classes = [];
		if ( $selected ) {
			$classes[] = 'selected';
		}
		$exists = true;
		if ( $checkEdit && !$title->isKnown() ) {
			// Selected tabs should not show as red link. It doesn't make sense
			// to show a red link on a page the user has already navigated to.
			// https://phabricator.wikimedia.org/T294129#7451549
			if ( !$selected ) {
				$classes[] = 'new';
			}
			$exists = false;
			if ( $query !== '' ) {
				$query = 'action=edit&redlink=1&' . $query;
			} else {
				$query = 'action=edit&redlink=1';
			}
		}

		$services = MediaWikiServices::getInstance();
		$linkClass = $services->getLinkRenderer()->getLinkClasses( $title );

		if ( $message instanceof MessageSpecifier ) {
			$msg = new Message( $message );
		} else {
			// wfMessageFallback will nicely accept $message as an array of fallbacks
			// or just a single key
			$msg = wfMessageFallback( $message );
		}
		$msg->setContext( $this->getContext() );
		if ( !$msg->isDisabled() ) {
			$text = $msg->text();
		} else {
			$text = $services->getLanguageConverterFactory()
				->getLanguageConverter( $services->getContentLanguage() )
				->convertNamespace(
					$services->getNamespaceInfo()
						->getSubject( $title->getNamespace() )
				);
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

	/**
	 * Get a message label that skins can override.
	 *
	 * @param string $labelMessageKey
	 * @param mixed $param for the message
	 * @return string
	 */
	private function getSkinNavOverrideableLabel( $labelMessageKey, $param = null ) {
		$skname = $this->skinname;
		$msg = wfMessageFallback(
				"$skname-$labelMessageKey",
				"skin-$labelMessageKey",
				// @todo: Can be removed when every $labelMessageKey has a `skin-` prefixed message alternative.
				$labelMessageKey
			)->setContext( $this->getContext() );

		if ( $param ) {
			if ( is_numeric( $param ) ) {
				$msg->numParams( $param );
			} else {
				$msg->params( $param );
			}
		}
		return $msg->text();
	}

	/**
	 * @param string $name
	 * @param string|array $urlaction
	 * @return array
	 */
	private function makeTalkUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		if ( !is_object( $title ) ) {
			throw new MWException( __METHOD__ . " given invalid pagename $name" );
		}
		$title = $title->getTalkPage();
		return [
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->isKnown(),
		];
	}

	/**
	 * Get the attributes for the watch link.
	 * @param string $mode Either 'watch' or 'unwatch'
	 * @param Authority $performer
	 * @param Title $title
	 * @param string|null $action
	 * @param bool $onPage
	 * @return array
	 */
	private function getWatchLinkAttrs(
		string $mode, Authority $performer, Title $title, ?string $action, bool $onPage
	): array {
		$class = 'mw-watchlink ' . (
			$onPage && ( $action == 'watch' || $action == 'unwatch' ) ? 'selected' : ''
			);

		// Add class identifying the page is temporarily watched, if applicable.
		if ( $this->getConfig()->get( 'WatchlistExpiry' ) &&
			MediaWikiServices::getInstance()->getWatchlistManager()->isTempWatched( $performer, $title )
		) {
			$class .= ' mw-watchlink-temp';
		}

		return [
			'class' => $class,
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

	/**
	 * Run hooks relating to navigation menu data.
	 * Skins should extend this if they want to run opinionated transformations to the data after all
	 * hooks have been run. Note hooks are run in an arbitrary order.
	 *
	 * @param SkinTemplate $skin
	 * @param array &$content_navigation representing all menus.
	 * @since 1.37
	 */
	protected function runOnSkinTemplateNavigationHooks( SkinTemplate $skin, &$content_navigation ) {
		$title = $this->getRelevantTitle();
		if ( $title->canExist() ) {
			$this->getHookRunner()->onSkinTemplateNavigation( $skin, $content_navigation );
		} else {
			$this->getHookRunner()->onSkinTemplateNavigation__SpecialPage(
				$skin, $content_navigation );
		}

		// Equiv to SkinTemplateContentActions, run
		$this->getHookRunner()->onSkinTemplateNavigation__Universal(
			$skin, $content_navigation );
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
	private function buildContentNavigationUrlsInternal() {
		if ( $this->contentNavigationCached ) {
			return $this->contentNavigationCached;
		}
		// Display tabs for the relevant title rather than always the title itself
		$title = $this->getRelevantTitle();
		$onPage = $title->equals( $this->getTitle() );

		$out = $this->getOutput();
		$request = $this->getRequest();
		$performer = $this->getAuthority();
		$action = $this->getAction();
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		$categoriesData = $this->getCategoryPortletsData( $this->getOutput()->getCategoryLinks() );
		$content_navigation = $categoriesData + [
			// Modern keys: Please ensure these get unset inside Skin::prepareQuickTemplate
			'user-interface-preferences' => [],
			'user-page' => $this->loggedin ? [
				'userpage' => $this->buildPersonalPageItem( 'pt-userpage-2' )
			] : [],
			'user-menu' => $this->buildPersonalUrls( false ),
			'notifications' => [],
			// Legacy keys
			'namespaces' => [],
			'views' => [],
			'actions' => [],
			'variants' => []
		];

		$userCanRead = $this->getAuthority()->probablyCan( 'read', $title );

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
			if ( $subjectId === 'user' ) {
				$subjectMsg = wfMessage( 'nstab-user', $subjectPage->getRootText() );
			} else {
				$subjectMsg = [ "nstab-$subjectId" ];
			}
			if ( $subjectPage->isMainPage() ) {
				array_unshift( $subjectMsg, 'nstab-mainpage' );
			}

			$content_navigation['namespaces'][$subjectId] = $this->tabAction(
				$subjectPage, $subjectMsg, !$isTalk, '', $userCanRead
			);
			$content_navigation['namespaces'][$subjectId]['context'] = 'subject';
			// Use the following messages if defined or talk if not:
			// nstab-user_talk, nstab-media_talk, nstab-project_talk
			// nstab-image_talk, nstab-mediawiki_talk, nstab-template_talk
			// nstab-help_talk, nstab-category_talk
			$content_navigation['namespaces'][$talkId] = $this->tabAction(
				$talkPage, [ "nstab-$talkId", "talk" ], $isTalk, '', $userCanRead
			);
			$content_navigation['namespaces'][$talkId]['context'] = 'talk';

			if ( $userCanRead ) {
				// Adds "view" view link
				if ( $title->isKnown() ) {
					$content_navigation['views']['view'] = $this->tabAction(
						$isTalk ? $talkPage : $subjectPage,
						'view-view',
						( $onPage && ( $action == 'view' || $action == 'purge' ) ), '', true
					);
					$content_navigation['views']['view']['text'] = $this->getSkinNavOverrideableLabel(
						'view-view'
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
						'text' => $this->getSkinNavOverrideableLabel(
							'view-foreign', $page->getWikiDisplayName()
						),
						'href' => $page->getSourceURL(),
						'primary' => false,
					];
				}

				// Checks if user can edit the current page if it exists or create it otherwise
				if ( $this->getAuthority()->probablyCan( 'edit', $title ) ) {
					// Builds CSS class for talk page links
					$isTalkClass = $isTalk ? ' istalk' : '';
					// Whether the user is editing the page
					$isEditing = $onPage && ( $action == 'edit' || $action == 'submit' );
					// Whether to show the "Add a new section" tab
					// Checks if this is a current rev of talk page and is not forced to be hidden
					$showNewSection = !$out->forceHideNewSectionLink()
						&& ( ( $isTalk && $out->isRevisionCurrent() ) || $out->showNewSectionLink() );
					$section = $request->getVal( 'section' );

					if ( $title->exists()
						|| ( $title->inNamespace( NS_MEDIAWIKI )
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
						'text' => $this->getSkinNavOverrideableLabel(
							"view-$msgKey"
						),
						'href' => $title->getLocalURL( $this->editUrlOptions() ),
						'primary' => !$isRemoteContent, // don't collapse this in vector
					];

					// section link
					if ( $showNewSection ) {
						// Adds new section link
						// $content_navigation['actions']['addsection']
						$content_navigation['views']['addsection'] = [
							'class' => ( $isEditing && $section == 'new' ) ? 'selected' : false,
							'text' => $this->getSkinNavOverrideableLabel(
								"action-addsection"
							),
							'href' => $title->getLocalURL( 'action=edit&section=new' )
						];
					}
				// Checks if the page has some kind of viewable source content
				} elseif ( $title->hasSourceText() ) {
					// Adds view source view link
					$content_navigation['views']['viewsource'] = [
						'class' => ( $onPage && $action == 'edit' ) ? 'selected' : false,
						'text' => $this->getSkinNavOverrideableLabel(
							"action-viewsource"
						),
						'href' => $title->getLocalURL( $this->editUrlOptions() ),
						'primary' => true, // don't collapse this in vector
					];
				}

				// Checks if the page exists
				if ( $title->exists() ) {
					// Adds history view link
					$content_navigation['views']['history'] = [
						'class' => ( $onPage && $action == 'history' ) ? 'selected' : false,
						'text' => $this->getSkinNavOverrideableLabel(
							'view-history'
						),
						'href' => $title->getLocalURL( 'action=history' ),
					];

					if ( $this->getAuthority()->probablyCan( 'delete', $title ) ) {
						$content_navigation['actions']['delete'] = [
							'class' => ( $onPage && $action == 'delete' ) ? 'selected' : false,
							'text' => $this->getSkinNavOverrideableLabel(
								'action-delete'
							),
							'href' => $title->getLocalURL( 'action=delete' )
						];
					}

					if ( $this->getAuthority()->probablyCan( 'move', $title ) ) {
						$moveTitle = SpecialPage::getTitleFor( 'Movepage', $title->getPrefixedDBkey() );
						$content_navigation['actions']['move'] = [
							'class' => $this->getTitle()->isSpecial( 'Movepage' ) ? 'selected' : false,
							'text' => $this->getSkinNavOverrideableLabel(
								'action-move'
							),
							'href' => $moveTitle->getLocalURL()
						];
					}
				} else {
					// article doesn't exist or is deleted
					if ( $this->getAuthority()->probablyCan( 'deletedhistory', $title ) ) {
						$n = $title->getDeletedEditsCount();
						if ( $n ) {
							$undelTitle = SpecialPage::getTitleFor( 'Undelete', $title->getPrefixedDBkey() );
							// If the user can't undelete but can view deleted
							// history show them a "View .. deleted" tab instead.
							$msgKey = $this->getAuthority()->probablyCan( 'undelete', $title ) ?
								'undelete' : 'viewdeleted';
							$content_navigation['actions']['undelete'] = [
								'class' => $this->getTitle()->isSpecial( 'Undelete' ) ? 'selected' : false,
								'text' => $this->getSkinNavOverrideableLabel(
									"action-$msgKey", $n
								),
								'href' => $undelTitle->getLocalURL()
							];
						}
					}
				}

				if ( $this->getAuthority()->probablyCan( 'protect', $title ) &&
					$title->getRestrictionTypes() &&
					$permissionManager->getNamespaceRestrictionLevels(
						$title->getNamespace(),
						$performer->getUser()
					) !== [ '' ]
				) {
					$mode = $title->isProtected() ? 'unprotect' : 'protect';
					$content_navigation['actions'][$mode] = [
						'class' => ( $onPage && $action == $mode ) ? 'selected' : false,
						'text' => $this->getSkinNavOverrideableLabel(
							"action-$mode"
						),
						'href' => $title->getLocalURL( "action=$mode" )
					];
				}

				// Checks if the user is logged in
				if ( $this->loggedin && $this->getAuthority()
						->isAllowedAll( 'viewmywatchlist', 'editmywatchlist' )
				) {
					/**
					 * The following actions use messages which, if made particular to
					 * the any specific skins, would break the Ajax code which makes this
					 * action happen entirely inline. OutputPage::getJSVars
					 * defines a set of messages in a javascript object - and these
					 * messages are assumed to be global for all skins. Without making
					 * a change to that procedure these messages will have to remain as
					 * the global versions.
					 */
					$mode = MediaWikiServices::getInstance()->getWatchlistManager()
						->isWatched( $performer, $title ) ? 'unwatch' : 'watch';

					// Add the watch/unwatch link.
					$content_navigation['actions'][$mode] = $this->getWatchLinkAttrs(
						$mode,
						$performer,
						$title,
						$action,
						$onPage
					);
				}
			}

			// Add language variants
			$languageConverterFactory = MediaWikiServices::getInstance()->getLanguageConverterFactory();

			if ( $userCanRead && !$languageConverterFactory->isConversionDisabled() ) {
				$pageLang = $title->getPageLanguage();
				$converter = $languageConverterFactory
					->getLanguageConverter( $pageLang );
				// Checks that language conversion is enabled and variants exist
				// And if it is not in the special namespace
				if ( $converter->hasVariants() ) {
					// Gets list of language variants
					$variants = $converter->getVariants();
					// Gets preferred variant (note that user preference is
					// only possible for wiki content language variant)
					$preferred = $converter->getPreferredVariant();
					if ( $action === 'view' ) {
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
			// If it's not content, and a request URL is set it's got to be a special page
			try {
				$url = $request->getRequestURL();
			} catch ( MWException $e ) {
				$url = false;
			}
			$content_navigation['namespaces']['special'] = [
				'class' => 'selected',
				'text' => $this->msg( 'nstab-special' )->text(),
				'href' => $url, // @see: T4457, T4510
				'context' => 'subject'
			];
		}
		$this->runOnSkinTemplateNavigationHooks( $this, $content_navigation );

		// Setup xml ids and tooltip info
		foreach ( $content_navigation as $section => &$links ) {
			foreach ( $links as $key => &$link ) {
				// Allow links to set their own id for backwards compatibility reasons.
				if ( isset( $link['id'] ) || isset( $link['html' ] ) ) {
					continue;
				}
				$xmlID = $key;
				if ( isset( $link['context'] ) && $link['context'] == 'subject' ) {
					$xmlID = 'ca-nstab-' . $xmlID;
				} elseif ( isset( $link['context'] ) && $link['context'] == 'talk' ) {
					$xmlID = 'ca-talk';
					$link['rel'] = 'discussion';
				} elseif ( $section == 'variants' ) {
					$xmlID = 'ca-varlang-' . $xmlID;
					$link['class'] .= ' ca-variants-' . $link['lang'];
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

		$this->contentNavigationCached = $content_navigation;
		return $content_navigation;
	}

	/**
	 * Wrapper for private buildContentNavigationUrlsInternal
	 * @deprecated since 1.38 skins can use runOnSkinTemplateNavigationHooks instead.
	 * @return array
	 */
	protected function buildContentNavigationUrls() {
		wfDeprecated( __METHOD__, '1.38' );
		return $this->buildContentNavigationUrlsInternal();
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

		foreach ( $content_navigation as $navigation => $links ) {
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
						"content_navigation into content_actions." );
					continue;
				}

				$content_actions[$key] = $value;
			}
		}

		return $content_actions;
	}

	/**
	 * Generate strings used for xml 'id' names
	 * @deprecated since 1.35, use Title::getNamespaceKey() instead
	 * @return string
	 */
	protected function getNameSpaceKey() {
		return $this->getTitle()->getNamespaceKey();
	}

	/**
	 * Insert legacy menu items from content navigation into the personal toolbar.
	 *
	 * @internal
	 *
	 * @param array $contentNavigation
	 * @return array
	 */
	final protected function injectLegacyMenusIntoPersonalTools(
		array $contentNavigation
	): array {
		$userMenu = $contentNavigation['user-menu'] ?? [];
		// userpage is only defined for logged-in users, and wfArrayInsertAfter requires the
		// $after parameter to be a known key in the array.
		if ( isset( $contentNavigation['user-menu']['userpage'] ) && isset( $contentNavigation['notifications'] ) ) {
			$userMenu = wfArrayInsertAfter(
				$userMenu,
				$contentNavigation['notifications'],
				'userpage'
			);
		}
		if ( isset( $contentNavigation['user-interface-preferences'] ) ) {
			return array_merge(
				$contentNavigation['user-interface-preferences'],
				$userMenu
			);
		}
		return $userMenu;
	}

	/**
	 * Build the personal urls array.
	 *
	 * @internal
	 *
	 * @param array $contentNavigation
	 * @return array
	 */
	private function makeSkinTemplatePersonalUrls(
		array $contentNavigation
	): array {
		if ( isset( $contentNavigation['user-menu'] ) ) {
			return $this->injectLegacyMenusIntoPersonalTools( $contentNavigation );
		}
		return [];
	}

}
