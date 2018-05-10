<?php

class MockSearchResult extends SearchResult {
	private $isMissingRevision = false;
	private $isBrokenTitle = false;

	public function isMissingRevision() {
		return $this->isMissingRevision;
	}
	public function setMissingRevision( $isMissingRevision ) {
		$this->isMissingRevision = $isMissingRevision;
		return $this;
	}

	public function isBrokenTitle() {
		return $this->isBrokenTitle;
	}

	public function setBrokenTitle( $isBrokenTitle ) {
		$this->isBrokenTitle = $isBrokenTitle;
		return $this;
	}

	public function getInterwikiPrefix() {
		return $this->interwikiPrefix;
	}

	public function setInterwikiPrefix( $interwikiPrefix ) {
		$this->interwikiPrefix = $interwikiPrefix;
		return $this;
	}
}
