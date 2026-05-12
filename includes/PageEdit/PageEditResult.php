<?php

namespace MediaWiki\PageEdit;

/**
 * @internal
 * @since 1.47
 */
class PageEditResult {

	public function __construct(
		private readonly PageEditStatus $status,
		private readonly int|false $contentLength,
		private readonly bool $isConflict,
		private readonly ?bool $nullEdit,
		private readonly int $parentRevId,
		private readonly ?bool $redirect,
		private readonly string $section,
		private readonly ?string $sectionanchor,
		private readonly string $summary,
		private readonly string $textbox1,
	) {
	}

	public function getStatus(): PageEditStatus {
		return $this->status;
	}

	public function getContentLength(): false|int {
		return $this->contentLength;
	}

	public function isConflict(): bool {
		return $this->isConflict;
	}

	public function isNullEdit(): ?bool {
		return $this->nullEdit;
	}

	public function getParentRevId(): int {
		return $this->parentRevId;
	}

	public function isRedirect(): ?bool {
		return $this->redirect;
	}

	public function getSection(): string {
		return $this->section;
	}

	public function getSectionanchor(): ?string {
		return $this->sectionanchor;
	}

	public function getSummary(): string {
		return $this->summary;
	}

	public function getTextbox1(): string {
		return $this->textbox1;
	}

}
