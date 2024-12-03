<?php

use MediaWiki\Title\Title;

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

	public function getTotalHits() {
		return $this->numRows();
	}

	public function hasSuggestion() {
		return $this->suggestion !== null;
	}

	public function getSuggestionQuery() {
		return $this->suggestion;
	}

	public function getSuggestionSnippet() {
		return $this->suggestion;
	}

	public function hasRewrittenQuery() {
		return $this->rewrittenQuery !== null;
	}

	public function getQueryAfterRewrite() {
		return $this->rewrittenQuery;
	}

	public function getQueryAfterRewriteSnippet() {
		return htmlspecialchars( $this->rewrittenQuery );
	}

	public function getFirstResult(): ?Title {
		$first = reset( $this->results );
		return $first ? $first->getTitle() : null;
	}
}
