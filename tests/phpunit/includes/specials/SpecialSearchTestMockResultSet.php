<?php

class SpecialSearchTestMockResultSet extends SearchResultSet {
	protected $results;
	protected $suggestion;
	protected $rewrittenQuery;
	protected $containedSyntax;

	public function __construct(
		$suggestion = null,
		$rewrittenQuery = null,
		array $results = [],
		$containedSyntax = false
	) {
		$this->suggestion = $suggestion;
		$this->rewrittenQuery = $rewrittenQuery;
		$this->results = $results;
		$this->containedSyntax = $containedSyntax;
	}

	public function expandResults() {
		return $this->results;
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

	public function getFirstResult() {
		if ( count( $this->results ) === 0 ) {
			return null;
		}
		return $this->results[0]->getTitle();
	}
}
