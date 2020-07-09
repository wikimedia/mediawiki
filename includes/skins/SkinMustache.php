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
		$templateParser = $this->getTemplateParser();
		return $templateParser->processTemplate(
			$this->options['template'] ?? 'skin',
			$this->getTemplateData()
		);
	}

	/**
	 * Subclasses may extend this method to add additional
	 * template data.
	 *
	 * @return array Data for a mustache template
	 */
	public function getTemplateData() {
		$out = $this->getOutput();
		$tail = [
			MWDebug::getDebugHTML( $this->getContext() ),
			$this->bottomScripts(),
			wfReportTime( $out->getCSP()->getNonce() ),
			MWDebug::getHTMLDebugLog() . '</body></html>',
		];

		return [
			// Array objects
			'array-indicators' => $this->getIndicatorsData( $out->getIndicators() ),

			// Data objects
			'data-search-box' => $this->buildSearchProps(),

			// HTML strings
			'html-headelement' => $out->headElement( $this ),
			'html-sitenotice' => $this->getSiteNotice(),
			'html-title' => $out->getPageTitle(),
			'html-subtitle' => $this->prepareSubtitle(),
			'html-bodycontent' => $this->wrapHTML( $out->getTitle(), $out->getHTML() ),
			'html-catlinks' => $this->getCategories(),
			'html-aftercontent' => $this->afterContentHook(),
			'html-undelete' => $this->prepareUndeleteLink() ?: null,
			'html-userlangattributes' => $this->prepareUserLanguageAttributes(),
			'html-printfooter' => $this->printSource(),
			'html-printtail' => WrappedStringList::join( "\n", $tail ),
		];
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
}
