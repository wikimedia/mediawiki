<?php

namespace MediaWiki\PageEdit;

use MediaWiki\Linker\LinkTarget;

/**
 * Data returned by an edit attempt that can be passed to the next one to allow bypassing
 * certain warnings displayed by edit constraints when the user resubmits an edit.
 *
 * @internal
 * @since 1.48
 */
class PageEditAttemptState {

	private bool $allowBlankArticle = false;
	private bool $allowBlankSummary = false;
	private bool $allowRecreation = false;
	private ?LinkTarget $allowedProblematicRedirectTarget = null;
	private bool $ignoreRevisionDeletedWarning = false;

	public function shouldAllowBlankArticle(): bool {
		return $this->allowBlankArticle;
	}

	public function setAllowBlankArticle( bool $allowBlankArticle ): void {
		$this->allowBlankArticle = $allowBlankArticle;
	}

	public function shouldAllowBlankSummary(): bool {
		return $this->allowBlankSummary;
	}

	public function setAllowBlankSummary( bool $allowBlankSummary ): void {
		$this->allowBlankSummary = $allowBlankSummary;
	}

	public function shouldAllowRecreation(): bool {
		return $this->allowRecreation;
	}

	public function setAllowRecreation( bool $allowRecreation ): void {
		$this->allowRecreation = $allowRecreation;
	}

	public function getAllowedProblematicRedirectTarget(): ?LinkTarget {
		return $this->allowedProblematicRedirectTarget;
	}

	public function setAllowedProblematicRedirectTarget( ?LinkTarget $allowedProblematicRedirectTarget ): void {
		$this->allowedProblematicRedirectTarget = $allowedProblematicRedirectTarget;
	}

	public function shouldIgnoreRevisionDeletedWarning(): bool {
		return $this->ignoreRevisionDeletedWarning;
	}

	public function setIgnoreRevisionDeletedWarning( bool $ignoreRevisionDeletedWarning ): void {
		$this->ignoreRevisionDeletedWarning = $ignoreRevisionDeletedWarning;
	}

}
