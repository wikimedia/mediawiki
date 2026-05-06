<?php

namespace MediaWiki\PageEdit;

use MediaWiki\Context\IContextSource;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use RuntimeException;
use Wikimedia\Message\MessageSpecifier;

/**
 * @internal
 * @since 1.47
 */
class PageEditInputs {
	// TODO This class should have setters

	/** @var Article */
	private $article;

	/** @var ?Title */
	private $contextTitle;

	/**
	 * @param bool $allowBlankArticle
	 * @param bool $allowBlankSummary
	 * @param ?LinkTarget $allowedProblematicRedirectTarget
	 * @param Article $article
	 * @param Authority $authority
	 * @param string $autoSumm
	 * @param string[] $changeTags
	 * @param string|null $contentFormat
	 * @param string $contentModel
	 * @param IContextSource $context
	 * @param ?Title $contextTitle
	 * @param string|null $edittime
	 * @param int|null $editRevId
	 * @param bool $enableApiEditOverride
	 * @param bool $ignoreProblematicRedirects
	 * @param bool $ignoreRevisionDeletedWarning
	 * @param bool $markAsBot
	 * @param bool $markAsMinor
	 * @param string|null $newSectionAnchor
	 * @param int $oldid
	 * @param int $parentRevId
	 * @param bool $recreate
	 * @param string $section
	 * @param string|null $sectiontitle
	 * @param string|null $starttime
	 * @param MessageSpecifier $submitButtonLabel
	 * @param string $summary
	 * @param string $textbox1
	 * @param int $undidRev
	 * @param int $undoAfter
	 * @param UserIdentity $userForPreview
	 * @param UserIdentity $userForSave
	 * @param string|null $watchlistExpiry
	 * @param int[] $watchlistLabels
	 * @param bool $watchthis
	 */
	public function __construct(
		// TODO Most parameters should be optional
		private bool $allowBlankArticle,
		private bool $allowBlankSummary,
		private ?LinkTarget $allowedProblematicRedirectTarget,
		$article,
		private Authority $authority,
		private string $autoSumm,
		private array $changeTags,
		private ?string $contentFormat,
		private string $contentModel,
		private IContextSource $context,
		$contextTitle,
		private ?string $edittime,
		private ?int $editRevId,
		private bool $enableApiEditOverride,
		private bool $ignoreProblematicRedirects,
		private bool $ignoreRevisionDeletedWarning,
		private bool $markAsBot,
		private bool $markAsMinor,
		private ?string $newSectionAnchor,
		private int $oldid,
		private int $parentRevId,
		private bool $recreate,
		private string $section,
		private ?string $sectiontitle,
		private ?string $starttime,
		private MessageSpecifier $submitButtonLabel,
		private string $summary,
		private string $textbox1,
		private int $undidRev,
		private int $undoAfter,
		private UserIdentity $userForPreview,
		private UserIdentity $userForSave,
		private ?string $watchlistExpiry,
		private array $watchlistLabels,
		private bool $watchthis,
	) {
		$this->article = $article;
		$this->contextTitle = $contextTitle;
	}

	public function shouldAllowBlankArticle(): bool {
		return $this->allowBlankArticle;
	}

	public function shouldAllowBlankSummary(): bool {
		return $this->allowBlankSummary;
	}

	public function getAllowedProblematicRedirectTarget(): ?LinkTarget {
		return $this->allowedProblematicRedirectTarget;
	}

	/**
	 * @return Article
	 */
	public function getArticle() {
		return $this->article;
	}

	public function getAuthority(): Authority {
		return $this->authority;
	}

	public function getAutoSumm(): string {
		return $this->autoSumm;
	}

	public function getChangeTags(): array {
		return $this->changeTags;
	}

	public function getContentFormat(): ?string {
		return $this->contentFormat;
	}

	public function getContentModel(): string {
		return $this->contentModel;
	}

	public function getContext(): IContextSource {
		return $this->context;
	}

	/**
	 * @return Title
	 */
	public function getContextTitle() {
		// TODO Remove this logic and either remove $contextTitle from this class or enforce setting it
		if ( $this->contextTitle === null ) {
			throw new RuntimeException( "PageEditInputs do not have a context title set" );
		} else {
			return $this->contextTitle;
		}
	}

	public function getEdittime(): ?string {
		return $this->edittime;
	}

	public function getEditRevId(): ?int {
		return $this->editRevId;
	}

	public function shouldEnableApiEditOverride(): bool {
		return $this->enableApiEditOverride;
	}

	public function shouldIgnoreProblematicRedirects(): bool {
		return $this->ignoreProblematicRedirects;
	}

	public function shouldIgnoreRevisionDeletedWarning(): bool {
		return $this->ignoreRevisionDeletedWarning;
	}

	public function shouldMarkAsBot(): bool {
		return $this->markAsBot;
	}

	public function shouldMarkAsMinor(): bool {
		return $this->markAsMinor;
	}

	public function getNewSectionAnchor(): ?string {
		return $this->newSectionAnchor;
	}

	public function getOldid(): int {
		return $this->oldid;
	}

	public function getParentRevId(): int {
		return $this->parentRevId;
	}

	public function shouldRecreate(): bool {
		return $this->recreate;
	}

	public function getSection(): string {
		return $this->section;
	}

	public function getSectiontitle(): ?string {
		return $this->sectiontitle;
	}

	public function getStarttime(): ?string {
		return $this->starttime;
	}

	public function getSubmitButtonLabel(): MessageSpecifier {
		return $this->submitButtonLabel;
	}

	public function getSummary(): string {
		return $this->summary;
	}

	public function getTextbox1(): string {
		return $this->textbox1;
	}

	public function getUndidRev(): int {
		return $this->undidRev;
	}

	public function getUndoAfter(): int {
		return $this->undoAfter;
	}

	public function getUserForPreview(): UserIdentity {
		return $this->userForPreview;
	}

	public function getUserForSave(): UserIdentity {
		return $this->userForSave;
	}

	public function getWatchlistExpiry(): ?string {
		return $this->watchlistExpiry;
	}

	/**
	 * @return int[]
	 */
	public function getWatchlistLabels(): array {
		return $this->watchlistLabels;
	}

	public function shouldWatchthis(): bool {
		return $this->watchthis;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->article->getTitle();
	}

	/**
	 * @return WikiPage
	 */
	public function getPage() {
		return $this->article->getPage();
	}

}
