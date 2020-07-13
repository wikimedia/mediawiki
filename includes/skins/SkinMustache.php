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

		$html = $out->headElement( $this );
		$html .= $tp->processTemplate( $template, $this->getTemplateData() );
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
	 *
	 * @return array Data for a mustache template
	 */
	public function getTemplateData() {
		$out = $this->getOutput();
		$printSource = Html::rawElement( 'div', [ 'class' => 'printfooter' ], $this->printSource() );
		$bodyContent = $out->getHTML() . "\n" . $printSource;

		$data = [
			// Array objects
			'array-indicators' => $this->getIndicatorsData( $out->getIndicators() ),
			// Data objects
			'data-search-box' => $this->buildSearchProps(),
			// HTML strings
			'html-site-notice' => $this->getSiteNotice(),
			'html-title' => $out->getPageTitle(),
			'html-subtitle' => $this->prepareSubtitle(),
			'html-body-content' => $this->wrapHTML( $out->getTitle(), $bodyContent ),
			'html-categories' => $this->getCategories(),
			'html-after-content' => $this->afterContentHook(),
			'html-undelete-link' => $this->prepareUndeleteLink(),
			'html-user-language-attributes' => $this->prepareUserLanguageAttributes(),
		];

		return $data;
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
			'page-title' => SpecialPage::getTitleFor( 'Search' )->getPrefixedDBkey(),
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
			Html::rawElement(
				'div',
				[ 'id' => 'mw-html-debug-log' ],
				MWDebug::getHTMLDebugLog()
			)
			. Html::closeElement( 'body' )
			. Html::closeElement( 'html' )
		];

		return WrappedStringList::join( "\n", $tail );
	}
}
