<?php

namespace MediaWiki\Skins\Vector;

use ExtensionRegistry;
use MediaWiki\MediaWikiServices;
use MediaWiki\Skins\Vector\Components\VectorComponentButton;
use MediaWiki\Skins\Vector\Components\VectorComponentDropdown;
use MediaWiki\Skins\Vector\Components\VectorComponentLanguageDropdown;
use MediaWiki\Skins\Vector\Components\VectorComponentMainMenu;
use MediaWiki\Skins\Vector\Components\VectorComponentMenuVariants;
use MediaWiki\Skins\Vector\Components\VectorComponentPageTools;
use MediaWiki\Skins\Vector\Components\VectorComponentPinnableContainer;
use MediaWiki\Skins\Vector\Components\VectorComponentSearchBox;
use MediaWiki\Skins\Vector\Components\VectorComponentStickyHeader;
use MediaWiki\Skins\Vector\Components\VectorComponentTableOfContents;
use MediaWiki\Skins\Vector\Components\VectorComponentUserLinks;
use RuntimeException;
use SkinMustache;
use SkinTemplate;

/**
 * @ingroup Skins
 * @package Vector
 * @internal
 */
class SkinVector22 extends SkinMustache {
	private const STICKY_HEADER_ENABLED_CLASS = 'vector-sticky-header-enabled';
	/** @var null|array for caching purposes */
	private $languages;

	/**
	 * @inheritDoc
	 */
	protected function runOnSkinTemplateNavigationHooks( SkinTemplate $skin, &$content_navigation ) {
		parent::runOnSkinTemplateNavigationHooks( $skin, $content_navigation );
		Hooks::onSkinTemplateNavigation( $skin, $content_navigation );
	}

	/**
	 * @inheritDoc
	 */
	public function isResponsive() {
		// Check it's enabled by user preference and configuration
		$responsive = parent::isResponsive() && $this->getConfig()->get( 'VectorResponsive' );
		// For historic reasons, the viewport is added when Vector is loaded on the mobile
		// domain. This is only possible for 3rd parties or by useskin parameter as there is
		// no preference for changing mobile skin. Only need to check if $responsive is falsey.
		if ( !$responsive && ExtensionRegistry::getInstance()->isLoaded( 'MobileFrontend' ) ) {
			$mobFrontContext = MediaWikiServices::getInstance()->getService( 'MobileFrontend.Context' );
			if ( $mobFrontContext->shouldDisplayMobileView() ) {
				return true;
			}
		}
		return $responsive;
	}

	/**
	 * Provide styles required to present the server rendered page in this skin. Additional styles
	 * may be loaded dynamically by the client.
	 *
	 * Any styles returned by this method are loaded on the critical rendering path as linked
	 * stylesheets. I.e., they are required to load on the client before first paint.
	 *
	 * @return array
	 */
	public function getDefaultModules(): array {
		$modules = parent::getDefaultModules();
		$featureManager = VectorServices::getFeatureManager();
		$zebraUpdate = $featureManager->isFeatureEnabled(
			Constants::FEATURE_ZEBRA_DESIGN
		);

		if ( $zebraUpdate ) {
			$modules['styles']['skin'][] = 'skins.vector.zebra.styles';
		}
		return $modules;
	}

	/**
	 * This should be upstreamed to the Skin class in core once the logic is finalized.
	 * Returns false if the page is a special page without any languages, or if an action
	 * other than view is being used.
	 *
	 * @return bool
	 */
	private function canHaveLanguages(): bool {
		$action = $this->getContext()->getActionName();

		// FIXME: This logic should be moved into the ULS extension or core given the button is hidden,
		// it should not be rendered, short term fix for T328996.
		if ( $action === 'history' ) {
			return false;
		}

		$title = $this->getTitle();
		// Defensive programming - if a special page has added languages explicitly, best to show it.
		if ( $title && $title->isSpecialPage() && empty( $this->getLanguagesCached() ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Remove the add topic button from data-views if present
	 *
	 * @param array &$parentData Template data
	 * @return bool An add topic button was removed
	 */
	private function removeAddTopicButton( array &$parentData ): bool {
		$views = $parentData['data-portlets']['data-views']['array-items'];
		$hasAddTopicButton = false;
		$html = '';
		foreach ( $views as $i => $view ) {
			if ( $view['id'] === 'ca-addsection' ) {
					array_splice( $views, $i, 1 );
					$hasAddTopicButton = true;
					continue;
			}
			$html .= $view['html-item'];
		}
		$parentData['data-portlets']['data-views']['array-items'] = $views;
		$parentData['data-portlets']['data-views']['html-items'] = $html;
		return $hasAddTopicButton;
	}

	/**
	 * @param string $location Either 'top' or 'bottom' is accepted.
	 * @return bool
	 */
	protected function isLanguagesInContentAt( string $location ): bool {
		if ( !$this->canHaveLanguages() ) {
			return false;
		}
		$featureManager = VectorServices::getFeatureManager();
		$inContent = $featureManager->isFeatureEnabled(
			Constants::FEATURE_LANGUAGE_IN_HEADER
		);
		$isMainPage = $this->getTitle() ? $this->getTitle()->isMainPage() : false;

		switch ( $location ) {
			case 'top':
				return $isMainPage ? $inContent && $featureManager->isFeatureEnabled(
					Constants::FEATURE_LANGUAGE_IN_MAIN_PAGE_HEADER
				) : $inContent;
			case 'bottom':
				return $inContent && $isMainPage && !$featureManager->isFeatureEnabled(
					Constants::FEATURE_LANGUAGE_IN_MAIN_PAGE_HEADER
				);
			default:
				throw new RuntimeException( 'unknown language button location' );
		}
	}

	/**
	 * Whether or not the languages are out of the sidebar and in the content either at
	 * the top or the bottom.
	 *
	 * @return bool
	 */
	final protected function isLanguagesInContent(): bool {
		return $this->isLanguagesInContentAt( 'top' ) || $this->isLanguagesInContentAt( 'bottom' );
	}

	/**
	 * Calls getLanguages with caching.
	 *
	 * @return array
	 */
	protected function getLanguagesCached(): array {
		if ( $this->languages === null ) {
			$this->languages = $this->getLanguages();
		}
		return $this->languages;
	}

	/**
	 * Check whether ULS is enabled
	 *
	 * @return bool
	 */
	final protected function isULSExtensionEnabled(): bool {
		return ExtensionRegistry::getInstance()->isLoaded( 'UniversalLanguageSelector' );
	}

	/**
	 * Show the ULS button if it's modern Vector, languages in header is enabled,
	 * the ULS extension is enabled, and we are on a subect page. Hide it otherwise.
	 * There is no point in showing the language button if ULS extension is unavailable
	 * as there is no ways to add languages without it.
	 * @return bool
	 */
	protected function shouldHideLanguages(): bool {
		$title = $this->getTitle();
		$isSubjectPage = $title && $title->exists() && !$title->isTalkPage();
		return !$this->isLanguagesInContent() || !$this->isULSExtensionEnabled() || !$isSubjectPage;
	}

	/**
	 * Determines if the language switching alert box should be in the sidebar.
	 *
	 * @return bool
	 */
	private function shouldLanguageAlertBeInSidebar(): bool {
		$featureManager = VectorServices::getFeatureManager();
		$isMainPage = $this->getTitle() ? $this->getTitle()->isMainPage() : false;
		$shouldShowOnMainPage = $isMainPage && !empty( $this->getLanguagesCached() ) &&
			$featureManager->isFeatureEnabled( Constants::FEATURE_LANGUAGE_IN_MAIN_PAGE_HEADER );
		return ( $this->isLanguagesInContentAt( 'top' ) && !$isMainPage && !$this->shouldHideLanguages() &&
			$featureManager->isFeatureEnabled( Constants::FEATURE_LANGUAGE_IN_HEADER ) ) ||
			$shouldShowOnMainPage;
	}

	/**
	 * Merges the `view-overflow` menu into the `action` menu.
	 * This ensures that the previous state of the menu e.g. emptyPortlet class
	 * is preserved.
	 *
	 * @param array $data
	 * @return array
	 */
	private function mergeViewOverflowIntoActions( array $data ): array {
		$portlets = $data['data-portlets'];
		$actions = $portlets['data-actions'];
		$overflow = $portlets['data-views-overflow'];
		// if the views overflow menu is not empty, then signal that the more menu despite
		// being initially empty now has collapsible items.
		if ( !$overflow['is-empty'] ) {
			$data['data-portlets']['data-actions']['class'] .= ' vector-has-collapsible-items';
		}
		$data['data-portlets']['data-actions']['html-items'] = $overflow['html-items'] . $actions['html-items'];
		return $data;
	}

	/**
	 * @inheritDoc
	 */
	public function getHtmlElementAttributes() {
		$original = parent::getHtmlElementAttributes();
		$featureManager = VectorServices::getFeatureManager();
		$original['class'] .= ' ' . implode( ' ', $featureManager->getFeatureBodyClass() );

		if ( VectorServices::getFeatureManager()->isFeatureEnabled( Constants::FEATURE_STICKY_HEADER ) ) {
			// T290518: Add scroll padding to root element when the sticky header is
			// enabled. This class needs to be server rendered instead of added from
			// JS in order to correctly handle situations where the sticky header
			// isn't visible yet but we still need scroll padding applied (e.g. when
			// the user navigates to a page with a hash fragment in the URI). For this
			// reason, we can't rely on the `vector-sticky-header-visible` class as it
			// is added too late.
			//
			// Please note that this class applies scroll padding which does not work
			// when applied to the body tag in Chrome, Safari, and Firefox (and
			// possibly others). It must instead be applied to the html tag.
			$original['class'] = implode( ' ', [ $original['class'] ?? '', self::STICKY_HEADER_ENABLED_CLASS ] );
		}
		$original['class'] = trim( $original['class'] );

		return $original;
	}

	/**
	 * Pulls the page tools menu out of $sidebar into $pageToolsMenu
	 *
	 * @param array &$sidebar
	 * @param array &$pageToolsMenu
	 */
	private static function extractPageToolsFromSidebar( array &$sidebar, array &$pageToolsMenu ) {
		$restPortlets = $sidebar[ 'array-portlets-rest' ] ?? [];
		$toolboxMenuIndex = array_search(
			VectorComponentPageTools::TOOLBOX_ID,
			array_column(
				$restPortlets,
				'id'
			)
		);

		if ( $toolboxMenuIndex !== false ) {
			// Splice removes the toolbox menu from the $restPortlets array
			// and current returns the first value of array_splice, i.e. the $toolbox menu data.
			$pageToolsMenu = array_splice( $restPortlets, $toolboxMenuIndex );
			$sidebar['array-portlets-rest'] = $restPortlets;
		}
	}

	/**
	 * Get the ULS button label, accounting for the number of available
	 * languages.
	 *
	 * @return array
	 */
	final protected function getULSLabels(): array {
		$numLanguages = count( $this->getLanguagesCached() );

		if ( $numLanguages === 0 ) {
			return [
				'label' => $this->msg( 'vector-no-language-button-label' )->text(),
				'aria-label' => $this->msg( 'vector-no-language-button-aria-label' )->text()
			];
		} else {
			return [
				'label' => $this->msg( 'vector-language-button-label' )->numParams( $numLanguages )->escaped(),
				'aria-label' => $this->msg( 'vector-language-button-aria-label' )->numParams( $numLanguages )->escaped()
			];
		}
	}

	/**
	 * @return array
	 */
	public function getTemplateData(): array {
		$featureManager = VectorServices::getFeatureManager();
		$parentData = parent::getTemplateData();
		$localizer = $this->getContext();
		$parentData = $this->mergeViewOverflowIntoActions( $parentData );
		$portlets = $parentData['data-portlets'];

		$langData = $parentData['data-portlets']['data-languages'] ?? null;
		$config = $this->getConfig();

		$sidebar = $parentData[ 'data-portlets-sidebar' ];
		$pageToolsMenu = [];
		self::extractPageToolsFromSidebar( $sidebar, $pageToolsMenu );

		$hasAddTopicButton = $config->get( 'VectorPromoteAddTopic' ) &&
			$this->removeAddTopicButton( $parentData );

		$langButtonClass = $langData['class'] ?? '';
		$ulsLabels = $this->getULSLabels();
		$user = $this->getUser();
		$localizer = $this->getContext();

		$tocData = $parentData['data-toc'];
		$tocComponents = [];

		// If the table of contents has no items, we won't output it.
		// empty array is interpreted by Mustache as falsey.
		$isTocAvailable = !empty( $tocData ) && !empty( $tocData[ 'array-sections' ] );
		if ( $isTocAvailable ) {
			$dataToc = new VectorComponentTableOfContents(
				$parentData['data-toc'],
				$localizer,
				$this->getConfig(),
				VectorServices::getFeatureManager()
			);
			$tocComponents = [
				'data-toc' => $dataToc,
				'data-toc-pinnable-container' => new VectorComponentPinnableContainer(
					VectorComponentTableOfContents::ID,
					$dataToc->isPinned()
				),
				'data-page-titlebar-toc-dropdown' => new VectorComponentDropdown(
					'vector-page-titlebar-toc',
					// label
					$this->msg( 'vector-toc-collapsible-button-label' ),
					// class
					'vector-page-titlebar-toc mw-ui-icon-flush-left',
					// icon
					'listBullet',
				),
				'data-page-titlebar-toc-pinnable-container' => new VectorComponentPinnableContainer(
					'vector-page-titlebar-toc',
					$dataToc->isPinned()
				),
				'data-sticky-header-toc-dropdown' => new VectorComponentDropdown(
					'vector-sticky-header-toc',
					// label
					$this->msg( 'vector-toc-collapsible-button-label' ),
					// class
					'mw-portlet mw-portlet-sticky-header-toc vector-sticky-header-toc mw-ui-icon-flush-left',
					// icon
					'listBullet'
				),
				'data-sticky-header-toc-pinnable-container' => new VectorComponentPinnableContainer(
					'vector-sticky-header-toc',
					$dataToc->isPinned()
				),
			];
		}

		$isRegistered = $user->isRegistered();
		$userPage = $isRegistered ? $this->buildPersonalPageItem() : [];
		$components = $tocComponents + [
			'data-add-topic-button' => $hasAddTopicButton ? new VectorComponentButton(
				$this->msg( [ 'vector-2022-action-addsection', 'skin-action-addsection' ] )->text(),
				'speechBubbleAdd-progressive',
				'ca-addsection',
				'',
				[ 'data-event-name' => 'addsection-header' ],
				'quiet',
				'progressive',
				false,
				$this->getTitle()->getLocalURL( 'action=edit&section=new' )
			) : null,
			'data-vector-variants' => new VectorComponentMenuVariants(
				// @phan-suppress-next-line PhanTypeInvalidDimOffset, PhanTypeMismatchArgument
				$parentData['data-portlets']['data-variants'],
				$this->getTitle()->getPageLanguage(),
				$this->msg( 'vector-language-variant-switcher-label' )
			),
			'data-vector-user-links' => new VectorComponentUserLinks(
				$localizer,
				$user,
				$portlets,
				$this->getOptions()['link'],
				$userPage[ 'icon' ] ?? ''
			),
			'data-lang-dropdown' => $langData ? new VectorComponentLanguageDropdown(
				$ulsLabels['label'],
				$ulsLabels['aria-label'],
				$langButtonClass,
				count( $this->getLanguagesCached() ),
				$langData['html-items'] ?? '',
				$langData['html-before-portal'] ?? '',
				$langData['html-after-portal'] ?? '',
				$this->getTitle()
			) : null,
			'data-search-box' => new VectorComponentSearchBox(
				$parentData['data-search-box'],
				true,
				// is primary mode of search
				true,
				'searchform',
				true,
				$config,
				Constants::SEARCH_BOX_INPUT_LOCATION_MOVED,
				$localizer
			),
			'data-main-menu' => new VectorComponentMainMenu(
				$sidebar,
				$this->shouldLanguageAlertBeInSidebar(),
				$parentData['data-portlets']['data-languages'] ?? [],
				$localizer,
				$this->getUser(),
				VectorServices::getFeatureManager(),
				$this,
			),
			'data-main-menu-dropdown' => new VectorComponentDropdown(
				VectorComponentMainMenu::ID . '-dropdown',
				$this->msg( VectorComponentMainMenu::ID . '-label' )->text(),
				VectorComponentMainMenu::ID . '-dropdown' . ' mw-ui-icon-flush-left mw-ui-icon-flush-right',
				'menu'
			),
			'data-page-tools' => new VectorComponentPageTools(
				array_merge( [ $parentData['data-portlets']['data-actions'] ?? [] ], $pageToolsMenu ),
				$localizer,
				$this->getUser(),
				$featureManager
			),
			'data-page-tools-dropdown' => new VectorComponentDropdown(
				VectorComponentPageTools::ID . '-dropdown',
				$this->msg( 'toolbox' )->text(),
				VectorComponentPageTools::ID . '-dropdown',
			),
			'data-vector-sticky-header' => $featureManager->isFeatureEnabled(
				Constants::FEATURE_STICKY_HEADER
			) ? new VectorComponentStickyHeader(
				$localizer,
				new VectorComponentSearchBox(
					$parentData['data-search-box'],
					// Collapse inside search box is disabled.
					false,
					false,
					'vector-sticky-search-form',
					false,
					$config,
					Constants::SEARCH_BOX_INPUT_LOCATION_MOVED,
					$localizer
				),
				// Show sticky ULS if the ULS extension is enabled and the ULS in header is not hidden
				$this->isULSExtensionEnabled() && !$this->shouldHideLanguages() ?
					new VectorComponentButton(
						$ulsLabels[ 'label' ],
						'wikimedia-language',
						'p-lang-btn-sticky-header',
						'mw-interlanguage-selector',
						[
							'tabindex' => '-1',
							'data-event-name' => 'ui.dropdown-p-lang-btn-sticky-header'
						],
						'quiet',
					) : null,
				true
			) : null,
			'data-vector-settings-button' => new VectorComponentButton(
				$this->msg( 'vector-limited-width-toggle' ),
				$featureManager->isFeatureEnabled(
					Constants::FEATURE_LIMITED_WIDTH
				) ? 'fullScreen' : 'exitFullscreen',
				'',
				'vector-limited-width-toggle',
				[],
				'normal',
				'default',
				true
			)
		];
		foreach ( $components as $key => $component ) {
			// Array of components or null values.
			if ( $component ) {
				$parentData[$key] = $component->getTemplateData();
			}
		}

		return array_merge( $parentData, [
			'is-language-in-content' => $this->isLanguagesInContent(),
			'has-buttons-in-content-top' => $this->isLanguagesInContentAt( 'top' ) || $hasAddTopicButton,
			'is-language-in-content-bottom' => $this->isLanguagesInContentAt( 'bottom' ),
			// Cast empty string to null
			'html-subtitle' => $parentData['html-subtitle'] === '' ? null : $parentData['html-subtitle'],
		] );
	}
}
