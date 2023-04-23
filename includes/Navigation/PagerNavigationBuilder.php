<?php

namespace MediaWiki\Navigation;

use MediaWiki\Html\Html;
use MediaWiki\Language\RawMessage;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;
use Message;
use MessageLocalizer;
use RuntimeException;

/**
 * Build the navigation for a pager, with links to prev/next page, links to change limits, and
 * optionally links to first/last page.
 *
 * @since 1.39
 */
class PagerNavigationBuilder {
	/** @var MessageLocalizer */
	private $messageLocalizer;

	/** @var PageReference */
	protected $page;
	/** @var array<string,?string> */
	protected $linkQuery = [];

	/** @var array<string,?string>|null */
	private $prevLinkQuery = null;
	/** @var string */
	private $prevMsg = 'prevn';
	/** @var string|null */
	private $prevTooltipMsg = null;

	/** @var array<string,?string>|null */
	private $nextLinkQuery = null;
	/** @var string */
	private $nextMsg = 'nextn';
	/** @var string|null */
	private $nextTooltipMsg = null;

	/** @var array<string,?string>|null */
	private $firstLinkQuery = null;
	/** @var string|null */
	private $firstMsg = null;
	/** @var string|null */
	private $firstTooltipMsg = null;

	/** @var array<string,?string>|null */
	private $lastLinkQuery = null;
	/** @var string|null */
	private $lastMsg = null;
	/** @var string|null */
	private $lastTooltipMsg = null;

	/** @var int */
	private $currentLimit = 50;
	/** @var int[] */
	private $limits = [ 20, 50, 100, 250, 500 ];
	/** @var string */
	private $limitLinkQueryParam = 'limit';
	/** @var string|null */
	private $limitTooltipMsg = null;

	/**
	 * @param MessageLocalizer $messageLocalizer
	 */
	public function __construct( MessageLocalizer $messageLocalizer ) {
		$this->messageLocalizer = $messageLocalizer;
	}

	/**
	 * @param PageReference $page
	 * @return $this
	 */
	public function setPage( PageReference $page ): PagerNavigationBuilder {
		$this->page = $page;
		return $this;
	}

	/**
	 * @param array<string,?string> $linkQuery
	 * @return $this
	 */
	public function setLinkQuery( array $linkQuery ): PagerNavigationBuilder {
		$this->linkQuery = $linkQuery;
		return $this;
	}

	/**
	 * @param array<string,?string>|null $prevLinkQuery
	 * @return $this
	 */
	public function setPrevLinkQuery( ?array $prevLinkQuery ): PagerNavigationBuilder {
		$this->prevLinkQuery = $prevLinkQuery;
		return $this;
	}

	/**
	 * @param string $prevMsg
	 * @return $this
	 */
	public function setPrevMsg( string $prevMsg ): PagerNavigationBuilder {
		$this->prevMsg = $prevMsg;
		return $this;
	}

	/**
	 * @param string|null $prevTooltipMsg
	 * @return $this
	 */
	public function setPrevTooltipMsg( ?string $prevTooltipMsg ): PagerNavigationBuilder {
		$this->prevTooltipMsg = $prevTooltipMsg;
		return $this;
	}

	/**
	 * @param array<string,?string>|null $nextLinkQuery
	 * @return $this
	 */
	public function setNextLinkQuery( ?array $nextLinkQuery ): PagerNavigationBuilder {
		$this->nextLinkQuery = $nextLinkQuery;
		return $this;
	}

	/**
	 * @param string $nextMsg
	 * @return $this
	 */
	public function setNextMsg( string $nextMsg ): PagerNavigationBuilder {
		$this->nextMsg = $nextMsg;
		return $this;
	}

	/**
	 * @param string|null $nextTooltipMsg
	 * @return $this
	 */
	public function setNextTooltipMsg( ?string $nextTooltipMsg ): PagerNavigationBuilder {
		$this->nextTooltipMsg = $nextTooltipMsg;
		return $this;
	}

	/**
	 * @param array<string,?string>|null $firstLinkQuery
	 * @return $this
	 */
	public function setFirstLinkQuery( ?array $firstLinkQuery ): PagerNavigationBuilder {
		$this->firstLinkQuery = $firstLinkQuery;
		return $this;
	}

	/**
	 * @param string|null $firstMsg
	 * @return $this
	 */
	public function setFirstMsg( ?string $firstMsg ): PagerNavigationBuilder {
		$this->firstMsg = $firstMsg;
		return $this;
	}

	/**
	 * @param string|null $firstTooltipMsg
	 * @return $this
	 */
	public function setFirstTooltipMsg( ?string $firstTooltipMsg ): PagerNavigationBuilder {
		$this->firstTooltipMsg = $firstTooltipMsg;
		return $this;
	}

	/**
	 * @param array<string,?string>|null $lastLinkQuery
	 * @return $this
	 */
	public function setLastLinkQuery( ?array $lastLinkQuery ): PagerNavigationBuilder {
		$this->lastLinkQuery = $lastLinkQuery;
		return $this;
	}

	/**
	 * @param string|null $lastMsg
	 * @return $this
	 */
	public function setLastMsg( ?string $lastMsg ): PagerNavigationBuilder {
		$this->lastMsg = $lastMsg;
		return $this;
	}

	/**
	 * @param string|null $lastTooltipMsg
	 * @return $this
	 */
	public function setLastTooltipMsg( ?string $lastTooltipMsg ): PagerNavigationBuilder {
		$this->lastTooltipMsg = $lastTooltipMsg;
		return $this;
	}

	/**
	 * @param int $currentLimit
	 * @return $this
	 */
	public function setCurrentLimit( int $currentLimit ): PagerNavigationBuilder {
		$this->currentLimit = $currentLimit;
		return $this;
	}

	/**
	 * @param int[] $limits
	 * @return $this
	 */
	public function setLimits( array $limits ): PagerNavigationBuilder {
		$this->limits = $limits;
		return $this;
	}

	/**
	 * @param string $limitLinkQueryParam
	 * @return $this
	 */
	public function setLimitLinkQueryParam( string $limitLinkQueryParam ): PagerNavigationBuilder {
		$this->limitLinkQueryParam = $limitLinkQueryParam;
		return $this;
	}

	/**
	 * @param string|null $limitTooltipMsg
	 * @return $this
	 */
	public function setLimitTooltipMsg( ?string $limitTooltipMsg ): PagerNavigationBuilder {
		$this->limitTooltipMsg = $limitTooltipMsg;
		return $this;
	}

	/**
	 * @param mixed $key
	 * @param mixed ...$params
	 * @return Message
	 */
	private function msg( $key, ...$params ): Message {
		return $this->messageLocalizer
			->msg( $key, ...$params )
			->page( $this->page );
	}

	/**
	 * @stable to override
	 * @param array|null $query
	 * @param string|null $class
	 * @param string $text
	 * @param string|null $tooltip
	 * @param string|null $rel
	 * @return string HTML
	 */
	protected function makeLink(
		?array $query, ?string $class, string $text, ?string $tooltip, ?string $rel = null
	): string {
		if ( $query !== null ) {
			$title = Title::newFromPageReference( $this->page );
			return Html::element(
				'a',
				[
					'href' => $title->getLocalURL( array_merge( $this->linkQuery, $query ) ),
					'rel' => $rel,
					'title' => $tooltip,
					'class' => $class,
				],
				$text
			);
		} else {
			return Html::element(
				'span',
				[
					'class' => $class,
				],
				$text
			);
		}
	}

	/**
	 * Get the navigation HTML.
	 * @return string HTML
	 */
	public function getHtml(): string {
		if ( !isset( $this->page ) ) {
			throw new RuntimeException( 'page must be set' );
		}
		if ( isset( $this->firstMsg ) !== isset( $this->lastMsg ) ) {
			throw new RuntimeException( 'firstMsg and lastMsg must be both set or both unset' );
		}

		$prevText = $this->msg( $this->prevMsg )->numParams( $this->currentLimit )->text();
		$prevTooltip = $this->prevTooltipMsg ?
			$this->msg( $this->prevTooltipMsg )->numParams( $this->currentLimit )->text() :
			null;
		$prevLink = $this->makeLink( $this->prevLinkQuery, 'mw-prevlink', $prevText, $prevTooltip, 'prev' );

		$nextText = $this->msg( $this->nextMsg )->numParams( $this->currentLimit )->text();
		$nextTooltip = $this->nextTooltipMsg ?
			$this->msg( $this->nextTooltipMsg )->numParams( $this->currentLimit )->text() :
			null;
		$nextLink = $this->makeLink( $this->nextLinkQuery, 'mw-nextlink', $nextText, $nextTooltip, 'next' );

		if ( $this->firstMsg ) {
			$firstText = $this->msg( $this->firstMsg )->text();
			$firstTooltip = $this->firstTooltipMsg ?
				$this->msg( $this->firstTooltipMsg )->text() :
				null;
			$firstLink = $this->makeLink( $this->firstLinkQuery, 'mw-firstlink', $firstText, $firstTooltip );
		}

		if ( $this->lastMsg ) {
			$lastText = $this->msg( $this->lastMsg )->text();
			$lastTooltip = $this->lastTooltipMsg ?
				$this->msg( $this->lastTooltipMsg )->text() :
				null;
			$lastLink = $this->makeLink( $this->lastLinkQuery, 'mw-lastlink', $lastText, $lastTooltip );
		}

		$limitLinks = [];
		foreach ( $this->limits as $limit ) {
			$limitText = $this->msg( new RawMessage( '$1' ) )->numParams( $limit )->text();
			$limitTooltip = $this->limitTooltipMsg ?
				$this->msg( $this->limitTooltipMsg )->numParams( $limit )->text() :
				null;
			$limitQuery = $limit === $this->currentLimit ? null : [ $this->limitLinkQueryParam => $limit ];
			$limitLinks[] = $this->makeLink( $limitQuery, 'mw-numlink', $limitText, $limitTooltip );
		}

		$html = '';
		if ( isset( $firstLink ) && isset( $lastLink ) ) {
			$html .= $this->msg( 'parentheses' )->params(
				Message::listParam( [
					Message::rawParam( $firstLink ),
					Message::rawParam( $lastLink )
				], 'pipe' )
			)->escaped() . ' ';
		}
		$html .= $this->msg( 'viewprevnext' )->params(
			Message::rawParam( $prevLink ),
			Message::rawParam( $nextLink ),
			Message::listParam( array_map( static function ( $limitLink ) {
				return Message::rawParam( $limitLink );
			}, $limitLinks ), 'pipe' )
		)->escaped();

		return Html::rawElement( 'div', [ 'class' => 'mw-pager-navigation-bar' ], $html );
	}
}
