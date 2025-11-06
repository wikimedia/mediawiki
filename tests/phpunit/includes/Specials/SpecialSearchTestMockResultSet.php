<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Title\Title;
use SearchResultSet;

class SpecialSearchTestMockResultSet extends SearchResultSet {

	private ?string $suggestion;
	private ?string $rewrittenQuery;

	public function __construct(
		?string $suggestion = null,
		?string $rewrittenQuery = null,
		array $results = [],
		bool $containedSyntax = false
	) {
		$this->suggestion = $suggestion;
		$this->rewrittenQuery = $rewrittenQuery;
		$this->results = $results;
		$this->containedSyntax = $containedSyntax;
	}

	/** @inheritDoc */
	public function getTotalHits() {
		return $this->numRows();
	}

	/** @inheritDoc */
	public function hasSuggestion() {
		return $this->suggestion !== null;
	}

	/** @inheritDoc */
	public function getSuggestionQuery() {
		return $this->suggestion;
	}

	/** @inheritDoc */
	public function getSuggestionSnippet() {
		return $this->suggestion;
	}

	/** @inheritDoc */
	public function hasRewrittenQuery() {
		return $this->rewrittenQuery !== null;
	}

	/** @inheritDoc */
	public function getQueryAfterRewrite() {
		return $this->rewrittenQuery;
	}

	/** @inheritDoc */
	public function getQueryAfterRewriteSnippet() {
		return htmlspecialchars( $this->rewrittenQuery );
	}

	public function getFirstResult(): ?Title {
		$first = reset( $this->results );
		return $first ? $first->getTitle() : null;
	}
}
