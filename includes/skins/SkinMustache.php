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
use Wikimedia\WrappedStringList;

/**
 * Generic template for use with Mustache templates.
 * @since 1.35
 */
class SkinMustache extends SkinTemplate {
	/**
	 * @var TemplateParser|null
	 */
	private $templateParser = null;

	/**
	 * @since 1.36
	 * @stable for overriding
	 * @param string $name of the portal e.g. p-personal the name is personal.
	 * @param array $items that are accepted input to Skin::makeListItem
	 * @return array data that can be passed to a Mustache template that
	 *   represents a single menu.
	 */
	protected function getPortletData( $name, array $items ) {
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

		$id = Sanitizer::escapeIdForAttribute( "p-$name" );
		$data = [
			'id' => $id,
			'class' => 'mw-portlet ' . Sanitizer::escapeClass( "mw-portlet-$name" ),
			'html-tooltip' => Linker::tooltip( $id ),
			'html-items' => '',
			// Will be populated by SkinAfterPortlet hook.
			'html-after-portal' => '',
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
		$data['class'] .= ( count( $items ) === 0 && $content === '' )
			? ' emptyPortlet' : '';
		return $data;
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
	 * Get the template parser, it will be lazily created if not already set.
	 * The template directory is defined in the skin options passed to
	 * the class constructor.
	 *
	 * @return TemplateParser
	 */
	protected function getTemplateParser() {
		if ( $this->templateParser === null ) {
			$this->templateParser = new TemplateParser( $this->options['templateDirectory'] );
		}
		return $this->templateParser;
	}

	/**
	 * @inheritDoc
	 * Render the associated template. The master template is assumed
	 * to be 'skin' unless `template` has been passed in the skin options
	 * to the constructor.
	 */
	public function generateHTML() {
		$this->setupTemplateContext();
		$out = $this->getOutput();
		$tp = $this->getTemplateParser();
		$template = $this->options['template'] ?? 'skin';
		$data = $this->getTemplateData();

		// T259955: OutputPage::headElement must be called last (after getTemplateData)
		// as it calls OutputPage::getRlClient, which freezes the ResourceLoader
		// modules queue for the current page load.
		$html = $out->headElement( $this );

		$html .= $tp->processTemplate( $template, $data );
		$html .= $this->tailElement( $out );
		return $html;
	}

	/**
	 * Subclasses may extend this method to add additional
	 * template data.
	 *
	 * The data keys should be valid English words. Compound words should
	 * be hypenated except if they are normally written as one word. Each
	 * key should be prefixed with a type hint, this may be enforced by the
	 * class PHPUnit test.
	 *
	 * Plain strings are prefixed with 'html-', plain arrays with 'array-'
	 * and complex array data with 'data-'. 'is-' and 'has-' prefixes can
	 * be used for boolean variables.
	 * Messages are prefixed with 'msg-', followed by their message key.
	 * All messages specified in the skin option 'messages' will be available
	 * under 'msg-MESSAGE-NAME'.
	 *
	 * @return array Data for a mustache template
	 */
	public function getTemplateData() {
		$out = $this->getOutput();
		$printSource = Html::rawElement( 'div', [ 'class' => 'printfooter' ], $this->printSource() );
		$bodyContent = $out->getHTML() . "\n" . $printSource;

		$newTalksHtml = $this->getNewtalks() ?: null;

		$data = [
			'data-logos' => $this->getLogoData(),
			// Array objects
			'array-indicators' => $this->getIndicatorsData( $out->getIndicators() ),
			// Data objects
			'data-search-box' => $this->buildSearchProps(),
			// HTML strings
			'html-site-notice' => $this->getSiteNotice() ?: null,
			'html-user-message' => $newTalksHtml ?
				Html::rawElement( 'div', [ 'class' => 'usermessage' ], $newTalksHtml ) : null,
			'html-title' => $out->getPageTitle(),
			'html-subtitle' => $this->prepareSubtitle(),
			'html-body-content' => $this->wrapHTML( $out->getTitle(), $bodyContent ),
			'html-categories' => $this->getCategories(),
			'html-after-content' => $this->afterContentHook(),
			'html-undelete-link' => $this->prepareUndeleteLink(),
			'html-user-language-attributes' => $this->prepareUserLanguageAttributes(),

			// links
			'link-mainpage' => Title::newMainPage()->getLocalUrl(),
		];

		foreach ( $this->options['messages'] ?? [] as $message ) {
			$data["msg-{$message}"] = $this->msg( $message )->text();
		}
		return $data + $this->getPortletsTemplateData() + $this->getFooterTemplateData();
	}

	/**
	 * @return array of portlet data for all portlets
	 */
	private function getPortletsTemplateData() {
		$portlets = [];
		$contentNavigation = $this->buildContentNavigationUrls();
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
				$items = $this->getPersonalToolsForMakeListItem( $items );
			}

			$portlets['data-' . $name] = $this->getPortletData( $name, $items );
		}

		// A menu that includes the notifications. This will be deprecated in future versions
		// of the skin API spec.
		$portlets['data-personal'] = $this->getPortletData(
			'personal',
			$this->getPersonalToolsForMakeListItem(
				$this->insertNotificationsIntoPersonalTools( $contentNavigation )
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
	private function getFooterTemplateData() : array {
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
	 * @return array of logo data localised to the current language variant
	 */
	private function getLogoData() : array {
		$logoData = ResourceLoaderSkinModule::getAvailableLogos( $this->getConfig() );
		// check if the logo supports variants
		$variantsLogos = $logoData['variants'] ?? null;
		if ( $variantsLogos ) {
			$preferred = $this->getOutput()->getTitle()
				->getPageViewLanguage()->getCode();
			$variantOverrides = $variantsLogos[$preferred] ?? null;
			// Overrides the logo
			if ( $variantOverrides ) {
				foreach ( $variantOverrides as $key => $val ) {
					$logoData[$key] = $val;
				}
			}
		}
		return $logoData;
	}

	/**
	 * @return array
	 */
	private function buildSearchProps() : array {
		$config = $this->getConfig();

		$props = [
			'form-action' => $config->get( 'Script' ),
			'html-button-search-fallback' => $this->makeSearchButton(
				'fulltext',
				[ 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' ]
			),
			'html-button-search' => $this->makeSearchButton(
				'go',
				[ 'id' => 'searchButton', 'class' => 'searchButton' ]
			),
			'html-input' => $this->makeSearchInput( [ 'id' => 'searchInput' ] ),
			'msg-search' => $this->msg( 'search' )->text(),
			'page-title' => $this->getSearchPageTitle()->getPrefixedDBkey(),
		];

		return $props;
	}

	/**
	 * The final bits that go to the bottom of a page
	 * HTML document including the closing tags
	 *
	 * @param OutputPage $out
	 * @return string
	 */
	private function tailElement( $out ) {
		$tail = [
			MWDebug::getDebugHTML( $this ),
			$this->bottomScripts(),
			wfReportTime( $out->getCSP()->getNonce() ),
			MWDebug::getHTMLDebugLog()
			. Html::closeElement( 'body' )
			. Html::closeElement( 'html' )
		];

		return WrappedStringList::join( "\n", $tail );
	}
}
