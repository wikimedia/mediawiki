<?php

namespace MediaWiki\Skin;

use InvalidArgumentException;
use MediaWiki\Config\Config;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use MessageLocalizer;

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
 * @internal for use inside Skin and SkinTemplate classes only
 */
class SkinComponentSearch implements SkinComponent {
	/** @var Config */
	private $config;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var Title */
	private $searchTitle;
	/** @var array|null */
	private $cachedData;

	/**
	 * @param Config $config
	 * @param MessageLocalizer $localizer
	 * @param Title $searchTitle
	 */
	public function __construct(
		Config $config,
		MessageLocalizer $localizer,
		Title $searchTitle
	) {
		$this->config = $config;
		$this->localizer = $localizer;
		$this->searchTitle = $searchTitle;
		$this->cachedData = null;
	}

	private function getMessageLocalizer(): MessageLocalizer {
		return $this->localizer;
	}

	private function msg( string $key ): Message {
		return $this->localizer->msg( $key );
	}

	private function getConfig(): Config {
		return $this->config;
	}

	/**
	 * @param array $attrs (optional) will be passed to tooltipAndAccesskeyAttribs
	 *  and decorate the resulting input
	 * @return string of HTML input
	 */
	private function makeSearchInput( array $attrs = [] ) {
		return Html::element( 'input', $this->getSearchInputAttributes( $attrs ) );
	}

	/**
	 * @param string $mode representing the type of button wanted
	 *  either `go` OR `fulltext`.
	 * @param array $attrs (optional)
	 * @return string of HTML button
	 */
	private function makeSearchButton( string $mode, array $attrs = [] ) {
		switch ( $mode ) {
			case 'go':
			case 'fulltext':
				$realAttrs = [
					'type' => 'submit',
					'name' => $mode,
					'value' => $this->msg( $mode == 'go' ? 'searcharticle' : 'searchbutton' )->text(),
				];
				$realAttrs = array_merge(
					$realAttrs,
					Linker::tooltipAndAccesskeyAttribs(
						"search-$mode",
						[],
						null,
						$this->getMessageLocalizer()
					),
					$attrs
				);
				return Html::element( 'input', $realAttrs );
			default:
				throw new InvalidArgumentException( 'Unknown mode passed to ' . __METHOD__ );
		}
	}

	/**
	 * @param array $attrs (optional) will be passed to tooltipAndAccesskeyAttribs
	 *  and decorate the resulting input
	 * @return array attributes of HTML input
	 */
	private function getSearchInputAttributes( array $attrs = [] ) {
		$autoCapHint = $this->getConfig()->get( MainConfigNames::CapitalLinks );
		$realAttrs = [
			'type' => 'search',
			'name' => 'search',
			'placeholder' => $this->msg( 'searchsuggest-search' )->text(),
			'aria-label' => $this->msg( 'searchsuggest-search' )->text(),
			// T251664: Disable autocapitalization of input
			// method when using fully case-sensitive titles.
			'autocapitalize' => $autoCapHint ? 'sentences' : 'none',
			// T385525: Disable spellcheck to stop webkit-based browsers from
			// applying smart quotes, as someone using quotes almost
			// certainly wants to be searching for a literal phrase, not
			// searching for actual curly quotes.
			'spellcheck' => 'false',
		];

		return array_merge(
			$realAttrs,
			Linker::tooltipAndAccesskeyAttribs(
				'search',
				[],
				null,
				$this->getMessageLocalizer()
			),
			$attrs
		);
	}

	/**
	 * @inheritDoc
	 * Since 1.38:
	 * - string html-button-fulltext-attributes HTML attributes for usage on a button
	 *    that redirects user to a search page with the current query.
	 * - string html-button-go-attributes HTML attributes for usage on a search
	 *   button that redirects user to a title that matches the query.
	 * - string html-input-attributes HTML attributes for input on an input field
	 *    that is used to construct a search query.
	 * Since 1.35:
	 * - string form-action Where the form should post to e.g. /w/index.php
	 * - string html-button-search Search button with label
	 *    derived from `html-button-go-attributes`.
	 * - string html-button-search-fallback Search button with label
	 *    derived from `html-button-fulltext-attributes`.
	 * - string html-input An input element derived from `html-input-attributes`.
	 */
	public function getTemplateData(): array {
		// Optimization: Generate once.
		if ( $this->cachedData ) {
			return $this->cachedData;
		}

		$config = $this->getConfig();
		$localizer = $this->getMessageLocalizer();
		$searchButtonAttributes = [
			'class' => 'searchButton'
		];
		$fallbackButtonAttributes = [
			'class' => 'searchButton mw-fallbackSearchButton'
		];
		$buttonAttributes = [
			'type' => 'submit',
		];

		$searchTitle = $this->searchTitle;

		$inputAttrs = $this->getSearchInputAttributes( [] );
		$goButtonAttributes = $searchButtonAttributes + $buttonAttributes + [
			'name' => 'go',
		] + Linker::tooltipAndAccesskeyAttribs(
			'search-go',
			[],
			null,
			$localizer
		);
		$fulltextButtonAttributes = $fallbackButtonAttributes + $buttonAttributes + [
			'name' => 'fulltext'
		] + Linker::tooltipAndAccesskeyAttribs(
			'search-fulltext',
			[],
			null,
			$localizer
		);

		$this->cachedData = [
			'search-special-page-title' => $searchTitle->getText(),
			'form-action' => $config->get( MainConfigNames::Script ),
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
			'page-title' => $searchTitle->getPrefixedDBkey(),
			'array-button-go-attributes' => $goButtonAttributes,
			'html-button-go-attributes' => Html::expandAttributes(
				$goButtonAttributes
			),
			'array-button-fulltext-attributes' => $fulltextButtonAttributes,
			'html-button-fulltext-attributes' => Html::expandAttributes(
				$fulltextButtonAttributes
			),
			'array-input-attributes' => $inputAttrs,
			'html-input-attributes' => Html::expandAttributes(
				$inputAttrs
			),
		];
		return $this->cachedData;
	}
}
