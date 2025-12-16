<?php

namespace MediaWiki\Navigation;

use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Title\Title;
use MediaWiki\Xml\XmlSelect;
use RuntimeException;

class CodexPagerNavigationBuilder extends PagerNavigationBuilder {

	private bool $hideLast = false;

	private ?string $navClass = 'cdx-table-pager';

	private array $queryValues;

	public function __construct( IContextSource $context, array $queryValues ) {
		$this->queryValues = $queryValues;
		parent::__construct( $context );
		$context->getOutput()->addModuleStyles( 'mediawiki.pager.codex.styles' );
		$context->getOutput()->addModules( 'mediawiki.pager.codex' );
		$context->getOutput()->addJsConfigVars( [
			'wgCodexTablePagerLimit' => $queryValues['limit'] ?? $this->currentLimit,
		] );
	}

	/**
	 * Set custom CSS class for the navigation wrapper.
	 *
	 * @param string $navClass
	 * @return $this
	 */
	public function setNavClass( string $navClass ): self {
		$this->navClass = $navClass;
		return $this;
	}

	/**
	 * Set to true to hide the "go to last page" item in the pager navigation
	 *
	 * @param bool $value
	 * @return void
	 */
	public function setHideLast( bool $value ) {
		$this->hideLast = $value;
	}

	/**
	 * @inheritDoc
	 */
	protected function makeLink(
		?array $query, ?string $class, string $text, ?string $tooltip, ?string $rel = null
	): string {
		if ( !$this->page ) {
			throw new RuntimeException( 'page must be set' );
		}

		$title = Title::newFromPageReference( $this->page );
		$isDisabled = $query === null;

		$type = null;
		if ( $class ) {
			if ( str_contains( $class, 'mw-prevlink' ) ) {
				$type = 'prev';
			} elseif ( str_contains( $class, 'mw-nextlink' ) ) {
				$type = 'next';
			} elseif ( str_contains( $class, 'mw-firstlink' ) ) {
				$type = 'first';
			} elseif ( str_contains( $class, 'mw-lastlink' ) ) {
				$type = 'last';
			}
		}

		$attrs = [
			'class' => [
				'cdx-button',
				'cdx-button--fake-button',
				'cdx-button--fake-button--' . ( $isDisabled ? 'disabled' : 'enabled' ),
				'cdx-button--weight-quiet',
				'cdx-button--icon-only'
			],
			'role' => 'button',
			'aria-label' => $tooltip ?: $text,
			'title' => $tooltip ?: $text,
		];

		if ( $isDisabled ) {
			$attrs['disabled'] = true;
			$attrs['href'] = null;
		} else {
			$attrs['href'] = $title->getLocalURL( array_merge( $this->linkQuery, $query ?? [] ) );
		}

		if ( $rel ) {
			$attrs['rel'] = $rel;
		}

		$classSuffix = ( $type === 'prev' ) ? 'previous' : $type;
		$iconHtml = Html::rawElement( 'span',
			[ 'class' => [ 'cdx-button__icon', 'cdx-table-pager__icon--' . $classSuffix ] ]
		);

		return Html::rawElement( 'a', $attrs, $iconHtml );
	}

	/**
	 * Get a form with the items-per-page select.
	 *
	 * @return string HTML fragment
	 */
	public function getLimitForm(): string {
		return Html::rawElement(
				'form',
				[
					'method' => 'get',
					'action' => wfScript(),
					'class' => 'cdx-table-pager__limit-form'
				],
				"\n" . $this->getLimitSelect( [ 'class' => 'cdx-select' ] ) . "\n" .
				Html::element( 'button',
					[ 'class' => [ 'cdx-button', 'cdx-table-pager__limit-form__submit' ] ],
					$this->msg( 'table_pager_limit_submit' )->text()
				) . "\n" .
				$this->getHiddenFields( [ 'limit' ] )
			) . "\n";
	}

	/**
	 * Get a "<select>" element which has options for each of the allowed limits
	 *
	 * @param string[] $attribs Extra attributes to set
	 * @return string HTML fragment
	 */
	public function getLimitSelect( array $attribs = [] ): string {
		$select = new XmlSelect( 'limit', false, $this->currentLimit );
		$select->addOptions( $this->getLimitSelectList() );
		foreach ( $attribs as $name => $value ) {
			$select->setAttribute( $name, $value );
		}
		return $select->getHTML();
	}

	/**
	 * Get a list of items to show as options in the item-per-page select.
	 */
	public function getLimitSelectList(): array {
		$options = [];
		// Add the current limit from the query string
		// to avoid that the limit is lost after clicking Go next time
		if ( !in_array( $this->currentLimit, $this->limits ) ) {
			$this->limits[] = $this->currentLimit;
			sort( $this->limits );
		}
		foreach ( $this->limits as $key => $value ) {
			// The pair is either $index => $limit, in which case the $value
			// will be numeric, or $limit => $text, in which case the $value
			// will be a string.
			if ( is_int( $value ) ) {
				$limit = $value;
				$text = $this->msg( 'cdx-table-pager-items-per-page-current' )->numParams( $limit );
			} else {
				$limit = $key;
				$text = $value;
			}
			$options[ $this->msg( 'rawmessage', $text )->text() ] = $limit;
		}

		return $options;
	}

	/**
	 * Get \<input type="hidden"\> elements for use in a method="get" form.
	 * Resubmits all defined elements of the query string, except for a
	 * exclusion list, passed in the $noResubmit parameter.
	 * Also array values are discarded for security reasons (per WebRequest::getVal)
	 *
	 * @param array $noResubmit Parameters from the request query which should not be resubmitted
	 * @return string HTML fragment
	 */
	public function getHiddenFields( array $noResubmit = [] ): string {
		$query = $this->queryValues;
		foreach ( $noResubmit as $name ) {
			unset( $query[$name] );
		}
		$s = '';
		foreach ( $query as $name => $value ) {
			if ( is_array( $value ) ) {
				// Per WebRequest::getVal: Array values are discarded for security reasons.
				continue;
			}
			$s .= Html::hidden( $name, $value ) . "\n";
		}
		return $s;
	}

	/**
	 * Get the navigation HTML
	 *
	 * @return string HTML
	 */
	public function getHtml(): string {
		if ( !$this->page ) {
			throw new RuntimeException( 'page must be set' );
		}

		$types = [ 'first', 'prev', 'next', 'last' ];
		$queries = [
			'first' => $this->firstLinkQuery,
			'prev' => $this->prevLinkQuery,
			'next' => $this->nextLinkQuery,
			'last' => $this->lastLinkQuery,
		];

		$buttons = [];
		foreach ( $types as $type ) {
			$msgKey = 'table_pager_' . $type;
			$tooltip = $this->msg( $msgKey )->text();
			$class = 'mw-' . $type . 'link';
			$buttons[$type] = $this->makeLink( $queries[$type], $class, $tooltip, $tooltip, $type );
		}

		if ( $this->hideLast ) {
			unset( $buttons['last'] );
		}

		$html = Html::openElement( 'div', [ 'class' => $this->navClass ] ) . "\n";

		$html .= Html::rawElement( 'div', [ 'class' => 'cdx-table-pager__start' ], $this->getLimitForm() ) . "\n";

		$html .= Html::rawElement( 'div', [ 'class' => 'cdx-table-pager__end' ], implode( '', $buttons ) ) . "\n";
		$html .= Html::closeElement( 'div' );

		return $html;
	}
}
