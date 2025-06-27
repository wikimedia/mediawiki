<?php

namespace MediaWiki\Skin;

use MediaWiki\Actions\Action;
use MediaWiki\Actions\CreditsAction;
use MediaWiki\Config\Config;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Article;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Title\Title;

class SkinComponentFooter implements SkinComponent {
	use ProtectedHookAccessorTrait;

	/** @var SkinComponentRegistryContext */
	private $skinContext;

	public function __construct( SkinComponentRegistryContext $skinContext ) {
		$this->skinContext = $skinContext;
	}

	/**
	 * Run SkinAddFooterLinks hook on menu data to insert additional menu items specifically in footer.
	 */
	private function getTemplateDataFooter(): array {
		$data = [
			'info' => $this->formatFooterInfoData(
				$this->getFooterInfoData()
			),
			'places' => $this->getSiteFooterLinks(),
		];
		$skin = $this->skinContext->getContextSource()->getSkin();
		foreach ( $data as $key => $existingItems ) {
			$newItems = [];
			$this->getHookRunner()->onSkinAddFooterLinks( $skin, $key, $newItems );
			foreach ( $newItems as $index => $linkHTML ) {
				$data[ $key ][ $index ] = [
					'id' => 'footer-' . $key . '-' . $index,
					'html' => $linkHTML,
				];
			}
		}
		return $data;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$footerData = $this->getTemplateDataFooter();

		// Create the menu components from the footer data.
		$footerInfoMenuData = new SkinComponentMenu(
			'footer-info',
			$footerData['info'],
			$this->skinContext->getMessageLocalizer()
		);
		$footerSiteMenuData = new SkinComponentMenu(
			'footer-places',
			$footerData['places'],
			$this->skinContext->getMessageLocalizer()
		);

		// To conform the footer menu data to the current SkinMustache specification,
		// run the derived data through a cleanup function to unset unexpected data properties
		// until the spec is updated to reflect the new properties introduced by the menu component.
		// See https://www.mediawiki.org/wiki/Manual:SkinMustache.php#DataFooter
		$footerMenuData = [];
		$footerMenuData['data-info'] = $footerInfoMenuData->getTemplateData();
		$footerMenuData['data-places'] = $footerSiteMenuData->getTemplateData();
		$footerMenuData['data-icons'] = $this->getFooterIcons();
		$footerMenuData = $this->formatFooterDataForCurrentSpec( $footerMenuData );

		return [
			'data-info' => $footerMenuData['data-info'],
			'data-places' => $footerMenuData['data-places'],
			'data-icons' => $footerMenuData['data-icons']
		];
	}

	/**
	 * Get the footer data containing standard footer links.
	 *
	 * All values are resolved and can be added to by the
	 * SkinAddFooterLinks hook.
	 *
	 * @since 1.40
	 * @internal
	 * @return array
	 */
	private function getFooterInfoData(): array {
		$action = null;
		$skinContext = $this->skinContext;
		$out = $skinContext->getOutput();
		$ctx = $skinContext->getContextSource();
		// This needs to be the relevant Title rather than just the raw Title for e.g. special pages that render content
		$title = $skinContext->getRelevantTitle();
		$titleExists = $title && $title->exists();
		$config = $skinContext->getConfig();
		$maxCredits = $config->get( MainConfigNames::MaxCredits );
		$showCreditsIfMax = $config->get( MainConfigNames::ShowCreditsIfMax );
		$useCredits = $titleExists
			&& $out->isArticle()
			&& $out->isRevisionCurrent()
			&& $maxCredits !== 0;

		/** @var CreditsAction $action */
		if ( $useCredits ) {
			$article = Article::newFromWikiPage( $skinContext->getWikiPage(), $ctx );
			$action = Action::factory( 'credits', $article, $ctx );
		}

		'@phan-var CreditsAction $action';
		return [
			'lastmod' => !$useCredits ? $this->lastModified() : null,
			'numberofwatchingusers' => null,
			'credits' => $useCredits && $action ?
				$action->getCredits( $maxCredits, $showCreditsIfMax ) : null,
			'copyright' => $titleExists &&
			$out->showsCopyright() ? $this->getCopyright() : null,
		];
	}

	/**
	 * @return string
	 */
	private function getCopyright() {
		$copyright = new SkinComponentCopyright( $this->skinContext );
		return $copyright->getTemplateData()[ 'html' ];
	}

	/**
	 * Format the footer data containing standard footer links for passing
	 * into SkinComponentMenu.
	 *
	 * @since 1.40
	 * @internal
	 * @param array $data raw footer data
	 * @return array
	 */
	private function formatFooterInfoData( array $data ): array {
		$formattedData = [];
		foreach ( $data as $key => $item ) {
			if ( $item ) {
				$formattedData[ $key ] = [
					'id' => 'footer-info-' . $key,
					'html' => $item
				];
			}
		}
		return $formattedData;
	}

	/**
	 * Gets the link to the wiki's privacy policy, about page, and disclaimer page
	 *
	 * @internal
	 * @return array data array for 'privacy', 'about', 'disclaimer'
	 */
	private function getSiteFooterLinks(): array {
		$siteLinksData = [];
		$siteLinks = [
			'privacy' => [ 'privacy', 'privacypage' ],
			'about' => [ 'aboutsite', 'aboutpage' ],
			'disclaimers' => [ 'disclaimers', 'disclaimerpage' ]
		];
		$localizer = $this->skinContext->getMessageLocalizer();

		foreach ( $siteLinks as $key => $siteLink ) {
			// Check if the link description has been disabled in the default language.
			// If disabled, it is disabled for all languages.
			if ( !$localizer->msg( $siteLink[0] )->inContentLanguage()->isDisabled() ) {
				// Display the link for the user, described in their language (which may or may not be the same as the
				// default language), but make the link target be the one site-wide page.
				$title = Title::newFromText( $localizer->msg( $siteLink[1] )->inContentLanguage()->text() );
				if ( $title !== null ) {
					$siteLinksData[$key] = [
						'id' => "footer-places-$key",
						'text' => $localizer->msg( $siteLink[0] )->text(),
						'href' => $title->fixSpecialName()->getLinkURL()
					];
				}
			}
		}
		return $siteLinksData;
	}

	/**
	 * Renders a $wgFooterIcons icon according to the method's arguments
	 *
	 * @param Config $config
	 * @param array|string $icon The icon to build the html for, see $wgFooterIcons
	 *   for the format of this array.
	 * @param string $withImage Whether to use the icon's image or output
	 *   a text-only footer icon.
	 * @return string HTML
	 * @internal for use in Skin only
	 */
	public static function makeFooterIconHTML( Config $config, $icon, string $withImage = 'withImage' ): string {
		if ( is_string( $icon ) ) {
			$html = $icon;
		} else { // Assuming array
			$url = $icon['url'] ?? null;
			unset( $icon['url'] );

			$sources = '';
			if ( isset( $icon['sources'] ) ) {
				foreach ( $icon['sources'] as $source ) {
					$sources .= Html::element( 'source', $source );
				}
				unset( $icon['sources'] );
			}

			if ( isset( $icon['src'] ) && $withImage === 'withImage' ) {
				// Lazy-load footer icons, since they're not part of the printed view.
				$icon['loading'] = 'lazy';
				// do this the lazy way, just pass icon data as an attribute array
				$html = Html::element( 'img', $icon );
				if ( $sources ) {
					$html = Html::openElement( 'picture' ) . $sources . $html . Html::closeElement( 'picture' );
				}
			} else {
				$html = htmlspecialchars( $icon['alt'] ?? '' );
			}
			if ( $url ) {
				$html = Html::rawElement(
					'a',
					[
						'href' => $url,
						// Using a fake Codex link button, as this is the long-expected UX; our apologies.
						'class' => [
							'cdx-button', 'cdx-button--fake-button',
							'cdx-button--size-large', 'cdx-button--fake-button--enabled'
						],
						'target' => $config->get( MainConfigNames::ExternalLinkTarget ),
					],
					$html
				);
			}
		}
		return $html;
	}

	/**
	 * Get data representation of icons
	 *
	 * @internal for use in Skin only
	 * @param Config $config
	 * @return array
	 */
	public static function getFooterIconsData( Config $config ) {
		$footericons = [];
		foreach (
			$config->get( MainConfigNames::FooterIcons ) as $footerIconsKey => &$footerIconsBlock
		) {
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
	 * Gets the link to the wiki's privacy policy, about page, and disclaimer page
	 *
	 * @internal
	 * @return array data array for 'privacy', 'about', 'disclaimer'
	 * @suppress SecurityCheck-DoubleEscaped
	 */
	private function getFooterIcons(): array {
		$dataIcons = [];
		$skinContext = $this->skinContext;
		$config = $skinContext->getConfig();
		// If footer icons are enabled append to the end of the rows
		$footerIcons = self::getFooterIconsData(
			$config
		);

		if ( count( $footerIcons ) > 0 ) {
			$icons = [];
			foreach ( $footerIcons as $blockName => $blockIcons ) {
				$html = '';
				foreach ( $blockIcons as $icon ) {
					$html .= self::makeFooterIconHTML(
						$config, $icon
					);
				}
				// For historic reasons this mimics the `icononly` option
				// for BaseTemplate::getFooterIcons. Empty rows should not be output.
				if ( $html ) {
					$block = htmlspecialchars( $blockName );
					$icons[$block] = [
						'name' => $block,
						'id' => 'footer-' . $block . 'ico',
						'html' => $html,
						'class' => [ 'noprint' ],
					];
				}
			}

			// Empty rows should not be output.
			// This is how Vector has behaved historically but we can revisit later if necessary.
			if ( count( $icons ) > 0 ) {
				$dataIcons = new SkinComponentMenu(
					'footer-icons',
					$icons,
					$this->skinContext->getMessageLocalizer(),
					'',
					[]
				);
			}
		}

		return $dataIcons ? $dataIcons->getTemplateData() : [];
	}

	/**
	 * Get finalized footer menu data and reformat to fit current specification.
	 *
	 * See https://www.mediawiki.org/wiki/Manual:SkinMustache.php#DataFooter
	 * This method should be removed once the specification is updated and
	 * new data properties provided by the menu component are ok to output.
	 *
	 * @internal
	 * @param array $data
	 * @return array
	 */
	private function formatFooterDataForCurrentSpec( array $data ): array {
		$formattedData = [];
		foreach ( $data as $key => $item ) {
			unset( $item['html-tooltip'] );
			unset( $item['html-items'] );
			unset( $item['html-after-portal'] );
			unset( $item['html-before-portal'] );
			unset( $item['label'] );
			unset( $item['class'] );
			foreach ( $item['array-items'] ?? [] as $index => $arrayItem ) {
				unset( $item['array-items'][$index]['html-item'] );
			}
			$formattedData[$key] = $item;
			$formattedData[$key]['className'] = $key === 'data-icons' ? 'noprint' : null;
		}
		return $formattedData;
	}

	/**
	 * Get the timestamp of the latest revision, formatted in user language
	 *
	 * @internal for use in Skin.php only
	 * @return string
	 */
	private function lastModified() {
		$skinContext = $this->skinContext;
		$out = $skinContext->getOutput();
		$timestamp = $out->getRevisionTimestamp();
		$useParsoid = $out->getOutputFlag( ParserOutputFlags::USE_PARSOID );

		// No cached timestamp, load it from the database
		// TODO: This code shouldn't be necessary, revision ID should always be available
		// Move this logic to OutputPage::getRevisionTimestamp if needed.
		if ( $timestamp === null ) {
			$revId = $out->getRevisionId();
			if ( $revId !== null ) {
				$timestamp = MediaWikiServices::getInstance()->getRevisionLookup()->getTimestampFromId( $revId );
			}
		}

		$lastModified = new SkinComponentLastModified(
			$skinContext,
			$timestamp,
			$useParsoid
		);

		return $lastModified->getTemplateData()['text'];
	}
}
