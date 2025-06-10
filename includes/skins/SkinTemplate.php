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

namespace MediaWiki\Skin;

use InvalidArgumentException;
use MediaWiki\Debug\MWDebug;
use MediaWiki\Exception\MWException;
use MediaWiki\Html\Html;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Permissions\Authority;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\Contribute\ContributeFactory;
use MediaWiki\Title\Title;
use Profiler;
use RuntimeException;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

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

	/** @var string */
	public $thispage;
	/** @var string */
	public $titletxt;
	/** @var string */
	public $userpage;
	/** @var bool TODO: Rename this to $isRegistered (but that's a breaking change) */
	public $loggedin;
	/** @var string */
	public $username;
	/** @var array */
	public $userpageUrlDetails;

	/** @var bool */
	private $isTempUser;

	/** @var bool */
	private $isNamedUser;

	/** @var bool */
	private $isAnonUser;

	/** @var bool */
	private $templateContextSet = false;
	/** @var array|null */
	private $contentNavigationCached;
	/** @var array|null */
	private $portletsCached;

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
		$this->loggedin = $user->isRegistered();
		$this->username = $user->getName();
		$this->isTempUser = $user->isTemp();
		$this->isNamedUser = $this->loggedin && !$this->isTempUser;
		$this->isAnonUser = $user->isAnon();

		if ( $this->isNamedUser ) {
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
	 * @return string HTML is the contents of the body tag e.g. <body>...</body>
	 */
	public function generateHTML() {
		$tpl = $this->prepareQuickTemplate();
		$options = $this->getOptions();
		$out = $this->getOutput();
		// execute template
		ob_start();
		$tpl->execute();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
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
	 * @inheritDoc
	 */
	public function getTemplateData() {
		return parent::getTemplateData() + $this->getPortletsTemplateData();
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

		$tpl->set( 'mimetype', $config->get( MainConfigNames::MimeType ) );
		$tpl->set( 'charset', 'UTF-8' );
		$tpl->set( 'wgScript', $config->get( MainConfigNames::Script ) );
		$tpl->set( 'skinname', $this->skinname );
		$tpl->set( 'skinclass', static::class );
		$tpl->set( 'skin', $this );
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
		$tpl->set( 'stylepath', $config->get( MainConfigNames::StylePath ) );
		$tpl->set( 'articlepath', $config->get( MainConfigNames::ArticlePath ) );
		$tpl->set( 'scriptpath', $config->get( MainConfigNames::ScriptPath ) );
		$tpl->set( 'serverurl', $config->get( MainConfigNames::Server ) );
		$tpl->set( 'sitename', $config->get( MainConfigNames::Sitename ) );

		$userLang = $this->getLanguage();
		$userLangCode = $userLang->getHtmlCode();
		$userLangDir = $userLang->getDir();

		$tpl->set( 'lang', $userLangCode );
		$tpl->set( 'dir', $userLangDir );
		$tpl->set( 'rtl', $userLang->isRTL() );

		$logos = RL\SkinModule::getAvailableLogos( $config, $userLangCode );
		$tpl->set( 'logopath', $logos['1x'] );

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

		$footerData = $this->getComponent( 'footer' )->getTemplateData();
		$tpl->set( 'copyright', $footerData['info']['copyright'] ?? false );
		// No longer used
		$tpl->set( 'viewcount', false );
		$tpl->set( 'lastmod', $footerData['info']['lastmod'] ?? false );
		$tpl->set( 'credits', $footerData['info']['credits'] ?? false );
		$tpl->set( 'numberofwatchingusers', false );

		$tpl->set( 'disclaimer', $footerData['places']['disclaimer'] ?? false );
		$tpl->set( 'privacy', $footerData['places']['privacy'] ?? false );
		$tpl->set( 'about', $footerData['places']['about'] ?? false );

		// Flatten for compat with the 'footerlinks' key in QuickTemplate-based skins.
		$flattenedfooterlinks = [];
		foreach ( $footerData as $category => $data ) {
			if ( $category !== 'data-icons' ) {
				foreach ( $data['array-items'] as $item ) {
					$key = str_replace( 'data-', '', $category );
					$flattenedfooterlinks[$key][] = $item['name'];
					// For full support with BaseTemplate we also need to
					// copy over the keys.
					$tpl->set( $item['name'], $item['html'] );
				}
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
		// expect it. (See T316196)
		unset(
			$content_navigation['user-menu'],
			$content_navigation['notifications'],
			$content_navigation['user-page'],
			$content_navigation['user-interface-preferences'],
			$content_navigation['category-normal'],
			$content_navigation['category-hidden'],
			$content_navigation['associated-pages']
		);
		$content_actions = $this->buildContentActionUrls( $content_navigation );
		$tpl->set( 'content_navigation', $content_navigation );
		$tpl->set( 'content_actions', $content_actions );

		$tpl->set( 'sidebar', $this->buildSidebar() );
		$tpl->set( 'nav_urls', $this->buildNavUrls() );

		$tpl->set( 'debug', '' );
		$tpl->set( 'debughtml', MWDebug::getHTMLDebugLog() );

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
	 * Get the HTML for the personal tools list
	 * @since 1.31
	 *
	 * @param array|null $personalTools
	 * @param array $options
	 * @return string
	 */
	public function makePersonalToolsList( $personalTools = null, $options = [] ) {
		$personalTools ??= $this->getPersonalToolsForMakeListItem(
			$this->buildPersonalUrls()
		);

		$html = '';
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
		$authority = $this->getAuthority();
		$request = $this->getRequest();
		$pageurl = $title->getLocalURL();
		$services = MediaWikiServices::getInstance();
		$authManager = $services->getAuthManager();
		$groupPermissionsLookup = $services->getGroupPermissionsLookup();
		$tempUserConfig = $services->getTempUserConfig();
		$returnto = SkinComponentUtils::getReturnToParam( $title, $request, $authority );
		$shouldHideUserLinks = $this->isAnonUser && $tempUserConfig->isKnown();

		/* set up the default links for the personal toolbar */
		$personal_urls = [];

		if ( $this->loggedin ) {
			$this->addPersonalPageItem( $personal_urls, '' );

			// Merge notifications into the personal menu for older skins.
			if ( $includeNotifications ) {
				$contentNavigation = $this->buildContentNavigationUrlsInternal();

				$personal_urls += $contentNavigation['notifications'];
			}

			$usertalkUrlDetails = $this->makeTalkUrlDetails( $this->userpage );
			$personal_urls['mytalk'] = [
				'text' => $this->msg( 'mytalk' )->text(),
				'href' => &$usertalkUrlDetails['href'],
				'class' => $usertalkUrlDetails['exists'] ? null : 'new',
				'exists' => $usertalkUrlDetails['exists'],
				'active' => ( $usertalkUrlDetails['href'] == $pageurl ),
				'icon' => 'userTalk'
			];
			if ( !$this->isTempUser ) {
				$href = SkinComponentUtils::makeSpecialUrl( 'Preferences' );
				$personal_urls['preferences'] = [
					'text' => $this->msg( 'mypreferences' )->text(),
					'href' => $href,
					'active' => ( $href == $pageurl ),
					'icon' => 'settings',
				];
			}

			if ( $authority->isAllowed( 'viewmywatchlist' ) ) {
				$personal_urls['watchlist'] = self::buildWatchlistData();
			}

			# We need to do an explicit check for Special:Contributions, as we
			# have to match both the title, and the target, which could come
			# from request values (Special:Contributions?target=Jimbo_Wales)
			# or be specified in "subpage" form
			# (Special:Contributions/Jimbo_Wales). The plot
			# thickens, because the Title object is altered for special pages,
			# so it doesn't contain the original alias-with-subpage.
			$origTitle = Title::newFromText( $request->getText( 'title' ) );
			if ( $origTitle instanceof Title && $origTitle->isSpecialPage() ) {
				[ $spName, $spPar ] =
					MediaWikiServices::getInstance()->getSpecialPageFactory()->
						resolveAlias( $origTitle->getText() );
				$active = $spName == 'Contributions'
					&& ( ( $spPar && $spPar == $this->username )
						|| $request->getText( 'target' ) == $this->username );
			} else {
				$active = false;
			}

			$personal_urls = $this->makeContributionsLink( $personal_urls, 'mycontris', $this->username, $active );

			// if we can't set the user, we can't unset it either
			if ( $request->getSession()->canSetUser() ) {
				$personal_urls['logout'] = $this->buildLogoutLinkData();
			}
		} elseif ( !$shouldHideUserLinks ) {
			$canEdit = $authority->isAllowed( 'edit' );
			$canEditWithTemp = $tempUserConfig->isAutoCreateAction( 'edit' );
			// No need to show Talk and Contributions to anons if they can't contribute!
			if ( $canEdit || $canEditWithTemp ) {
				// Non interactive placeholder for anonymous users.
				// It's unstyled by default (black color). Skin that
				// needs it, can style it using the 'pt-anonuserpage' id.
				// Skin that does not need it should unset it.
				$personal_urls['anonuserpage'] = [
					'text' => $this->msg( 'notloggedin' )->text(),
				];
			}
			if ( $canEdit ) {
				// Because of caching, we can't link directly to the IP talk and
				// contributions pages. Instead we use the special page shortcuts
				// (which work correctly regardless of caching). This means we can't
				// determine whether these links are active or not, but since major
				// skins (MonoBook, Vector) don't use this information, it's not a
				// huge loss.
				$personal_urls['anontalk'] = [
					'text' => $this->msg( 'anontalk' )->text(),
					'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Mytalk', false ),
					'active' => false,
					'icon' => 'userTalk',
				];
				$personal_urls = $this->makeContributionsLink( $personal_urls, 'anoncontribs', null, false );
			}
		}

		if ( !$this->loggedin ) {
			$useCombinedLoginLink = $this->useCombinedLoginLink();
			$login_url = $this->buildLoginData( $returnto, $useCombinedLoginLink );
			$createaccount_url = $this->buildCreateAccountData( $returnto );

			if (
				$authManager->canCreateAccounts()
				&& $authority->isAllowed( 'createaccount' )
				&& !$useCombinedLoginLink
			) {
				$personal_urls['createaccount'] = $createaccount_url;
			}

			if ( $authManager->canAuthenticateNow() ) {
				// TODO: easy way to get anon authority
				$key = $groupPermissionsLookup->groupHasPermission( '*', 'read' )
					? 'login'
					: 'login-private';
				$personal_urls[$key] = $login_url;
			}
		}

		return $personal_urls;
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
		$useCombinedLoginLink = $this->getConfig()->get( MainConfigNames::UseCombinedLoginLink );
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
			'single-id' => 'pt-login',
			'text' => $this->msg( $loginlink )->text(),
			'href' => SkinComponentUtils::makeSpecialUrl( 'Userlogin', $returnto ),
			'active' => $title->isSpecial( 'Userlogin' )
				|| ( $title->isSpecial( 'CreateAccount' ) && $useCombinedLoginLink ),
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
		foreach ( $links as $group => $groupLinks ) {
			$allLinks = [];
			$groupName = 'category-' . $group;
			foreach ( $groupLinks as $i => $link ) {
				$allLinks[$groupName . '-' . $i] = [
					'html' => $link,
				];
			}
			$categories[ $groupName ] = $allLinks;
		}
		return $categories;
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
	 * @return array of portlet data for all portlets
	 */
	private function getPortletsTemplateData() {
		if ( $this->portletsCached ) {
			return $this->portletsCached;
		}
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

		$this->portletsCached = [
			'data-portlets' => $portlets,
			'data-portlets-sidebar' => [
				'data-portlets-first' => $sidebar[0] ?? null,
				'array-portlets-rest' => array_slice( $sidebar, 1 ),
			],
		];
		return $this->portletsCached;
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
		$request = $this->getRequest();
		$authority = $this->getAuthority();
		$returnto = SkinComponentUtils::getReturnToParam( $title, $request, $authority );
		$isTemp = $this->isTempUser;
		$msg = $isTemp ? 'templogout' : 'pt-userlogout';

		return [
			'single-id' => 'pt-logout',
			'text' => $this->msg( $msg )->text(),
			'data-mw' => 'interface',
			'href' => SkinComponentUtils::makeSpecialUrl( 'Userlogout', $returnto ),
			'active' => false,
			'icon' => 'logOut'
		];
	}

	/**
	 * Build "Create Account" link data.
	 * @unstable
	 *
	 * @param string[] $returnto query params for the page to return to
	 * @return array
	 */
	protected function buildCreateAccountData( $returnto ) {
		$title = $this->getTitle();

		return [
			'single-id' => 'pt-createaccount',
			'text' => $this->msg( 'pt-createaccount' )->text(),
			'href' => SkinComponentUtils::makeSpecialUrl( 'CreateAccount', $returnto ),
			'active' => $title->isSpecial( 'CreateAccount' ),
			'icon' => 'userAdd'
		];
	}

	/**
	 * Add the userpage link to the array
	 *
	 * @param array &$links Links array to append to
	 * @param string $idSuffix Something to add to the IDs to make them unique
	 */
	private function addPersonalPageItem( &$links, $idSuffix ) {
		if ( $this->isNamedUser ) { // T340152
			$links['userpage'] = $this->buildPersonalPageItem( 'pt-userpage' . $idSuffix );
		}
	}

	/**
	 * Build a user page link data.
	 *
	 * @param string $id of user page item to be output in HTML attribute (optional)
	 * @return array
	 */
	protected function buildPersonalPageItem( $id = 'pt-userpage' ): array {
		$linkClasses = $this->userpageUrlDetails['exists'] ? [] : [ 'new' ];
		// T335440 Temp accounts dont show a user page link
		// But we still need to update the user icon, as its used by other UI elements
		$icon = $this->isTempUser ? 'userTemporary' : 'userAvatar';
		$href = &$this->userpageUrlDetails['href'];
		return [
			'id' => $id,
			'single-id' => 'pt-userpage',
			'text' => $this->username,
			'href' => $href,
			'link-class' => $linkClasses,
			'exists' => $this->userpageUrlDetails['exists'],
			'active' => ( $this->userpageUrlDetails['href'] == $this->getTitle()->getLocalURL() ),
			'icon' => $icon,
		];
	}

	/**
	 * Build a watchlist link data.
	 *
	 * @return array Array of data required to create a watchlist link.
	 */
	private function buildWatchlistData() {
		$href = SkinComponentUtils::makeSpecialUrl( 'Watchlist' );
		$pageurl = $this->getTitle()->getLocalURL();

		return [
			'single-id' => 'pt-watchlist',
			'text' => $this->msg( 'mywatchlist' )->text(),
			'href' => $href,
			'active' => ( $href == $pageurl ),
			'icon' => 'watchlist'
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
		$services = MediaWikiServices::getInstance();
		$linkClass = $services->getLinkRenderer()->getLinkClasses( $title );
		if ( $checkEdit && !$title->isKnown() ) {
			// Selected tabs should not show as red link. It doesn't make sense
			// to show a red link on a page the user has already navigated to.
			// https://phabricator.wikimedia.org/T294129#7451549
			if ( !$selected ) {
				// For historic reasons we add to the LI element
				$classes[] = 'new';
				// but adding the class to the A element is more appropriate.
				$linkClass .= ' new';
			}
			$exists = false;
			if ( $query !== '' ) {
				$query = 'action=edit&redlink=1&' . $query;
			} else {
				$query = 'action=edit&redlink=1';
			}
		} elseif ( $title->isRedirect() ) {
			// Do not redirect on redirect pages, see T5324
			$origTitle = $this->getRelevantTitle();
			// FIXME: If T385340 is resolved, this check can be removed
			$action = $this->getContext()->getActionName();
			$out = $this->getOutput();
			$notCurrentActionView = $action !== 'view' || !$out->isRevisionCurrent();

			if ( $origTitle instanceof Title && $title->isSamePageAs( $origTitle ) && $notCurrentActionView ) {
				if ( $query !== '' ) {
					$query .= '&redirect=no';
				} else {
					$query = 'redirect=no';
				}
			}
		}

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
			// Use a string instead of a class list for hook compatibility (T393504)
			'class' => implode( ' ', $classes ),
			'text' => $text,
			'href' => $title->getLocalURL( $query ),
			'exists' => $exists,
			'primary' => true ];
		if ( $linkClass !== '' ) {
			$result['link-class'] = trim( $linkClass );
		}

		return $result;
	}

	/**
	 * Get a message label that skins can override.
	 *
	 * @param string $labelMessageKey
	 * @param MessageParam|MessageSpecifier|string|int|float|null $param for the message
	 * @return string
	 */
	private function getSkinNavOverrideableLabel( $labelMessageKey, $param = null ) {
		$skname = $this->skinname;
		// The following messages can be used here:
		// * skin-action-addsection
		// * skin-action-delete
		// * skin-action-move
		// * skin-action-protect
		// * skin-action-undelete
		// * skin-action-unprotect
		// * skin-action-viewdeleted
		// * skin-action-viewsource
		// * skin-view-create
		// * skin-view-create-local
		// * skin-view-edit
		// * skin-view-edit-local
		// * skin-view-foreign
		// * skin-view-history
		// * skin-view-view
		$msg = wfMessageFallback(
				"$skname-$labelMessageKey",
				"skin-$labelMessageKey"
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
		$title = Title::newFromTextThrow( $name )->getTalkPage();
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
		$isWatchMode = $action == 'watch';
		$class = 'mw-watchlink ' . (
			$onPage && ( $isWatchMode || $action == 'unwatch' ) ? 'selected' : ''
			);

		$services = MediaWikiServices::getInstance();
		$watchlistManager = $services->getWatchlistManager();
		$watchIcon = $watchlistManager->isWatched( $performer, $title ) ? 'unStar' : 'star';
		$watchExpiry = null;
		// Modify tooltip and add class identifying the page is temporarily watched, if applicable.
		if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) &&
			$watchlistManager->isTempWatched( $performer, $title )
		) {
			$class .= ' mw-watchlink-temp';
			$watchIcon = 'halfStar';

			$watchStore = $services->getWatchedItemStore();
			$watchedItem = $watchStore->getWatchedItem( $performer->getUser(), $title );
			$diffInDays = $watchedItem->getExpiryInDays();
			$watchExpiry = $watchedItem->getExpiry( TS_ISO_8601 );
			if ( $diffInDays ) {
				$msgParams = [ $diffInDays ];
				// Resolves to tooltip-ca-unwatch-expiring message
				$tooltip = 'ca-unwatch-expiring';
			} else {
				// Resolves to tooltip-ca-unwatch-expiring-hours message
				$tooltip = 'ca-unwatch-expiring-hours';
			}
		}

		return [
			'class' => $class,
			'icon' => $watchIcon,
			// uses 'watch' or 'unwatch' message
			'text' => $this->msg( $mode )->text(),
			'single-id' => $tooltip ?? null,
			'tooltip-params' => $msgParams ?? null,
			'href' => $title->getLocalURL( [ 'action' => $mode ] ),
			// Set a data-mw=interface attribute, which the mediawiki.page.ajax
			// module will look for to make sure it's a trusted link
			'data' => [
				'mw' => 'interface',
				'mw-expiry' => $watchExpiry,
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
		$beforeHookAssociatedPages = array_keys( $content_navigation['associated-pages'] );
		$beforeHookNamespaces = array_keys( $content_navigation['namespaces'] );

		// Equiv to SkinTemplateContentActions, run
		$this->getHookRunner()->onSkinTemplateNavigation__Universal(
			$skin, $content_navigation );
		// The new `associatedPages` menu (added in 1.39)
		// should be backwards compatibile with `namespaces`.
		// To do this we look for hook modifications to both keys. If modifications are not
		// made the new key, but are made to the old key, associatedPages reverts back to the
		// links in the namespaces menu.
		// It's expected in future that `namespaces` menu will become an alias for `associatedPages`
		// at which point this code can be removed.
		$afterHookNamespaces = array_keys( $content_navigation[ 'namespaces' ] );
		$afterHookAssociatedPages = array_keys( $content_navigation[ 'associated-pages' ] );
		$associatedPagesChanged = count( array_diff( $afterHookAssociatedPages, $beforeHookAssociatedPages ) ) > 0;
		$namespacesChanged = count( array_diff( $afterHookNamespaces, $beforeHookNamespaces ) ) > 0;
		// If some change occurred to namespaces via the hook, revert back to namespaces.
		if ( !$associatedPagesChanged && $namespacesChanged ) {
			$content_navigation['associated-pages'] = $content_navigation['namespaces'];
		}
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
		$action = $this->getContext()->getActionName();
		$services = MediaWikiServices::getInstance();
		$permissionManager = $services->getPermissionManager();
		$categoriesData = $this->getCategoryPortletsData( $this->getOutput()->getCategoryLinks() );
		$userPageLink = [];
		$this->addPersonalPageItem( $userPageLink, '-2' );

		$content_navigation = $categoriesData + [
			// Modern keys: Please ensure these get unset inside Skin::prepareQuickTemplate
			'user-interface-preferences' => [],
			'user-page' => $userPageLink,
			'user-menu' => $this->buildPersonalUrls( false ),
			'notifications' => [],
			'associated-pages' => [],
			// Added in 1.44: a fixed position menu at bottom of page
			'dock-bottom' => [],
			// Legacy keys
			'namespaces' => [],
			'views' => [],
			'actions' => [],
			'variants' => []
		];

		$associatedPages = [];
		$namespaces = [];
		$userCanRead = $this->getAuthority()->probablyCan( 'read', $title );

		// Checks if page is some kind of content
		if ( $title->canExist() ) {
			// Gets page objects for the associatedPages namespaces
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

			// Adds namespace links
			if ( $subjectId === 'user' ) {
				$subjectMsg = $this->msg( 'nstab-user', $subjectPage->getRootText() );
			} else {
				// The following messages are used here:
				// * nstab-main
				// * nstab-media
				// * nstab-special
				// * nstab-project
				// * nstab-image
				// * nstab-mediawiki
				// * nstab-template
				// * nstab-help
				// * nstab-category
				// * nstab-<subject namespace key>
				$subjectMsg = [ "nstab-$subjectId" ];

				if ( $subjectPage->isMainPage() ) {
					array_unshift( $subjectMsg, 'nstab-mainpage' );
				}
			}

			$associatedPages[$subjectId] = $this->tabAction(
				$subjectPage, $subjectMsg, !$isTalk, '', $userCanRead
			);
			$associatedPages[$subjectId]['context'] = 'subject';
			// Use the following messages if defined or talk if not:
			// * nstab-talk, nstab-user_talk, nstab-media_talk, nstab-project_talk
			// * nstab-image_talk, nstab-mediawiki_talk, nstab-template_talk
			// * nstab-help_talk, nstab-category_talk,
			// * nstab-<subject namespace key>_talk
			$associatedPages[$talkId] = $this->tabAction(
				$talkPage, [ "nstab-$talkId", "talk" ], $isTalk, '', $userCanRead
			);
			$associatedPages[$talkId]['context'] = 'talk';

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
					$content_navigation['views']['view']['icon'] = 'eye';
					// signal to hide this from simple content_actions
					$content_navigation['views']['view']['redundant'] = true;
				}

				$page = $this->canUseWikiPage() ? $this->getWikiPage() : false;
				$isRemoteContent = $page && !$page->isLocal();

				// If it is a non-local file, show a link to the file in its own repository
				// @todo abstract this for remote content that isn't a file
				if ( $isRemoteContent ) {
					$content_navigation['views']['view-foreign'] = [
						'text' => $this->getSkinNavOverrideableLabel(
							'view-foreign', $page->getWikiDisplayName()
						),
						'icon' => 'eye',
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
					$isRedirect = $page && $page->isRedirect();
					// Whether to show the "Add a new section" tab
					// Checks if this is a current rev of talk page and is not forced to be hidden
					$showNewSection = !$out->getOutputFlag( ParserOutputFlags::HIDE_NEW_SECTION ) && (
						(
							$isTalk && !$isRedirect && $out->isRevisionCurrent()
						) ||
						$out->getOutputFlag( ParserOutputFlags::NEW_SECTION )
					);
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
							: null
						) . $isTalkClass,
						'icon' => 'edit',
						'text' => $this->getSkinNavOverrideableLabel(
							"view-$msgKey"
						),
						'single-id' => "ca-$msgKey",
						'href' => $title->getLocalURL( $this->editUrlOptions() ),
						'primary' => !$isRemoteContent, // don't collapse this in vector
					];

					// section link
					if ( $showNewSection ) {
						// Adds new section link
						// $content_navigation['actions']['addsection']
						$content_navigation['views']['addsection'] = [
							'class' => ( $isEditing && $section == 'new' ) ? 'selected' : null,
							'text' => $this->getSkinNavOverrideableLabel(
								"action-addsection"
							),
							'icon' => 'speechBubbleAdd',
							'href' => $title->getLocalURL( 'action=edit&section=new' )
						];
					}
				// Checks if the page has some kind of viewable source content
				} elseif ( $title->hasSourceText() ) {
					// Adds view source view link
					$content_navigation['views']['viewsource'] = [
						'class' => ( $onPage && $action == 'edit' ) ? 'selected' : null,
						'text' => $this->getSkinNavOverrideableLabel(
							"action-viewsource"
						),
						'icon' => 'editLock',
						'href' => $title->getLocalURL( $this->editUrlOptions() ),
						'primary' => true, // don't collapse this in vector
					];
				}

				// Checks if the page exists
				if ( $title->exists() ) {
					// Adds history view link
					$content_navigation['views']['history'] = [
						'class' => ( $onPage && $action == 'history' ) ? 'selected' : null,
						'text' => $this->getSkinNavOverrideableLabel(
							'view-history'
						),
						'icon' => 'history',
						'href' => $title->getLocalURL( 'action=history' ),
					];

					if ( $this->getAuthority()->probablyCan( 'delete', $title ) ) {
						$content_navigation['actions']['delete'] = [
							'icon' => 'trash',
							'class' => ( $onPage && $action == 'delete' ) ? 'selected' : null,
							'text' => $this->getSkinNavOverrideableLabel(
								'action-delete'
							),
							'href' => $title->getLocalURL( [
								'action' => 'delete',
								'oldid' => $title->getLatestRevID(),
							] )
						];
					}

					if ( $this->getAuthority()->probablyCan( 'move', $title ) ) {
						$moveTitle = SpecialPage::getTitleFor( 'Movepage', $title->getPrefixedDBkey() );
						$content_navigation['actions']['move'] = [
							'class' => $this->getTitle()->isSpecial( 'Movepage' ) ? 'selected' : null,
							'text' => $this->getSkinNavOverrideableLabel(
								'action-move'
							),
							'icon' => 'move',
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
								'class' => $this->getTitle()->isSpecial( 'Undelete' ) ? 'selected' : null,
								'text' => $this->getSkinNavOverrideableLabel(
									"action-$msgKey", $n
								),
								'icon' => 'trash',
								'href' => $undelTitle->getLocalURL()
							];
						}
					}
				}

				$restrictionStore = $services->getRestrictionStore();
				if ( $this->getAuthority()->probablyCan( 'protect', $title ) &&
					$restrictionStore->listApplicableRestrictionTypes( $title ) &&
					$permissionManager->getNamespaceRestrictionLevels(
						$title->getNamespace(),
						$performer->getUser()
					) !== [ '' ]
				) {
					$isProtected = $restrictionStore->isProtected( $title );
					$mode = $isProtected ? 'unprotect' : 'protect';
					$content_navigation['actions'][$mode] = [
						'class' => ( $onPage && $action == $mode ) ? 'selected' : null,
						'text' => $this->getSkinNavOverrideableLabel(
							"action-$mode"
						),
						'icon' => $isProtected ? 'unLock' : 'lock',
						'href' => $title->getLocalURL( "action=$mode" )
					];
				}

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
							'class' => ( $code == $preferred ) ? 'selected' : null,
							'text' => $varname,
							'href' => $title->getLocalURL( [ 'variant' => $code ] + $params ),
							'lang' => LanguageCode::bcp47( $code ),
							'hreflang' => LanguageCode::bcp47( $code ),
						];
					}
				}
			}
			$namespaces = $associatedPages;
		} else {
			// If it's not content, and a request URL is set it's got to be a special page
			try {
				$url = $request->getRequestURL();
			} catch ( MWException ) {
				$url = false;
			}
			$namespaces['special'] = [
				'class' => 'selected',
				'text' => $this->msg( 'nstab-special' )->text(),
				'href' => $url, // @see: T4457, T4510
				'context' => 'subject'
			];
			$associatedPages += $this->getSpecialPageAssociatedNavigationLinks( $title );
		}

		$content_navigation['namespaces'] = $namespaces;
		$content_navigation['associated-pages'] = $associatedPages;
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
					// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
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
	 * Return a list of pages that have been marked as related to/associated with
	 * the special page for display.
	 *
	 * @param Title $title
	 * @return array
	 */
	private function getSpecialPageAssociatedNavigationLinks( Title $title ): array {
		$specialAssociatedNavigationLinks = [];
		$specialFactory = MediaWikiServices::getInstance()->getSpecialPageFactory();
		$special = $specialFactory->getPage( $title->getText() );
		if ( $special === null ) {
			// not a valid special page
			return [];
		}
		$special->setContext( $this );
		$associatedNavigationLinks = $special->getAssociatedNavigationLinks();
		// If there are no subpages, we should not render
		if ( count( $associatedNavigationLinks ) === 0 ) {
			return [];
		}

		foreach ( $associatedNavigationLinks as $i => $relatedTitleText ) {
			$relatedTitle = Title::newFromText( $relatedTitleText );
			$special = $specialFactory->getPage( $relatedTitle->getText() );
			if ( $special === null ) {
				$text = $relatedTitle->getText();
			} else {
				$text = $special->getShortDescription( $relatedTitle->getSubpageText() );
			}
			$specialAssociatedNavigationLinks['special-specialAssociatedNavigationLinks-link-' . $i ] = [
				'text' => $text,
				'href' => $relatedTitle->getLocalURL(),
				'class' => $relatedTitle->fixSpecialName()->equals( $title->fixSpecialName() ) ? 'selected' : null,
			];
		}
		return $specialAssociatedNavigationLinks;
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
						"content_navigation into content_actions." );
					continue;
				}

				$content_actions[$key] = $value;
			}
		}

		return $content_actions;
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

	/**
	 * @since 1.35
	 * @param array $attrs (optional) will be passed to tooltipAndAccesskeyAttribs
	 *  and decorate the resulting input
	 * @return string of HTML input
	 */
	public function makeSearchInput( $attrs = [] ) {
		// It's possible that getTemplateData might be calling
		// Skin::makeSearchInput. To avoid infinite recursion create a
		// new instance of the search component here.
		$searchBox = $this->getComponent( 'search-box' );
		$data = $searchBox->getTemplateData();

		return Html::element( 'input',
			$data[ 'array-input-attributes' ] + $attrs
		);
	}

	/**
	 * @since 1.35
	 * @param string $mode representing the type of button wanted
	 *  either `go`, `fulltext` or `image`
	 * @param array $attrs (optional)
	 * @return string of HTML button
	 */
	final public function makeSearchButton( $mode, $attrs = [] ) {
		// It's possible that getTemplateData might be calling
		// Skin::makeSearchInput. To avoid infinite recursion create a
		// new instance of the search component here.
		$searchBox = $this->getComponent( 'search-box' );
		$searchData = $searchBox->getTemplateData();

		switch ( $mode ) {
			case 'go':
				$attrs['value'] ??= $this->msg( 'searcharticle' )->text();
				return Html::element(
					'input',
					array_merge(
						$searchData[ 'array-button-go-attributes' ], $attrs
					)
				);
			case 'fulltext':
				$attrs['value'] ??= $this->msg( 'searchbutton' )->text();
				return Html::element(
					'input',
					array_merge(
						$searchData[ 'array-button-fulltext-attributes' ], $attrs
					)
				);
			case 'image':
				$buttonAttrs = [
					'type' => 'submit',
					'name' => 'button',
				];
				$buttonAttrs = array_merge(
					$buttonAttrs,
					Linker::tooltipAndAccesskeyAttribs( 'search-fulltext' ),
					$attrs
				);
				unset( $buttonAttrs['src'] );
				unset( $buttonAttrs['alt'] );
				unset( $buttonAttrs['width'] );
				unset( $buttonAttrs['height'] );
				$imgAttrs = [
					'src' => $attrs['src'],
					'alt' => $attrs['alt'] ?? $this->msg( 'searchbutton' )->text(),
					'width' => $attrs['width'] ?? null,
					'height' => $attrs['height'] ?? null,
				];
				return Html::rawElement( 'button', $buttonAttrs, Html::element( 'img', $imgAttrs ) );
			default:
				throw new InvalidArgumentException( 'Unknown mode passed to ' . __METHOD__ );
		}
	}

	private function isSpecialContributeShowable(): bool {
		return ContributeFactory::isEnabledOnCurrentSkin(
			$this,
			$this->getConfig()->get( MainConfigNames::SpecialContributeSkinsEnabled )
		);
	}

	/**
	 * @param array &$personal_urls
	 * @param string $key
	 * @param string|null $userName
	 * @param bool $active
	 *
	 * @return array
	 */
	private function makeContributionsLink(
		array &$personal_urls,
		string $key,
		?string $userName = null,
		bool $active = false
	): array {
		$isSpecialContributeShowable = $this->isSpecialContributeShowable();
		$subpage = $userName ?? false;
		$user = $this->getUser();
		// If the "Contribute" page is showable and the user is anon. or has no edit count,
		// direct them to the "Contribute" page instead of the "Contributions" or "Mycontributions" pages.
		// Explanation:
		// a. For logged-in users: In wikis where the "Contribute" page is enabled, we only want
		// to navigate logged-in users to the "Contribute", when they have done no edits. Otherwise, we
		// want to navigate them to the "Mycontributions" page to easily access their edits/contributions.
		// Currently, the "Contribute" page is used as target for all logged-in users.
		// b. For anon. users: In wikis where the "Contribute" page is enabled, we still navigate the
		// anonymous users to the "Contribute" page.
		// Task: T369041
		if ( $isSpecialContributeShowable && (int)$user->getEditCount() === 0 ) {
			$href = SkinComponentUtils::makeSpecialUrlSubpage(
				'Contribute',
				false
			);
			$personal_urls['contribute'] = [
				'text' => $this->msg( 'contribute' )->text(),
				'href' => $href,
				'active' => $href == $this->getTitle()->getLocalURL(),
				'icon' => 'edit'
			];
		} else {
			$href = SkinComponentUtils::makeSpecialUrlSubpage(
				$subpage !== false ? 'Contributions' : 'Mycontributions',
				$subpage
			);
			$personal_urls[$key] = [
				'text' => $this->msg( $key )->text(),
				'href' => $href,
				'active' => $active,
				'icon' => 'userContributions'
			];
		}
		return $personal_urls;
	}

}

/** @deprecated class alias since 1.44 */
class_alias( SkinTemplate::class, 'SkinTemplate' );
