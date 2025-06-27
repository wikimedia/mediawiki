<?php

class MockSearchResult extends RevisionSearchResult {
	/** @var bool */
	private $isMissingRevision = false;
	/** @var bool */
	private $isBrokenTitle = false;
	/** @var string */
	private $interwikiPrefix = '';

	public function isMissingRevision() {
		return $this->isMissingRevision;
	}

	public function setMissingRevision( bool $isMissingRevision ): self {
		$this->isMissingRevision = $isMissingRevision;
		return $this;
	}

	public function isBrokenTitle() {
		return $this->isBrokenTitle;
	}

	public function setBrokenTitle( bool $isBrokenTitle ): self {
		$this->isBrokenTitle = $isBrokenTitle;
		return $this;
	}

	public function getInterwikiPrefix() {
		return $this->interwikiPrefix;
	}

	public function setInterwikiPrefix( string $interwikiPrefix ): self {
		$this->interwikiPrefix = $interwikiPrefix;
		return $this;
	}
}
