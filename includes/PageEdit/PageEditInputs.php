<?php

namespace MediaWiki\PageEdit;

use MediaWiki\Context\IContextSource;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;
use Wikimedia\Message\MessageSpecifier;

/**
 * @internal
 * @since 1.47
 */
class PageEditInputs {

	private bool $allowBlankArticle = false;
	private bool $allowBlankSummary = false;
	private ?LinkTarget $allowedProblematicRedirectTarget = null;
	private string $autoSumm = '';
	/** @var string[] */
	private array $changeTags = [];
	private ?string $contentFormat = null;
	private ?PageReference $contextPage = null;
	private ?string $edittime = null;
	private ?int $editRevId = null;
	private bool $enableApiEditOverride = false;
	private bool $ignoreProblematicRedirects = false;
	private bool $ignoreRevisionDeletedWarning = false;
	private bool $markAsBot = false;
	private bool $markAsMinor = false;
	private int $oldid = 0;
	private int $parentRevId = 0;
	private bool $recreate = false;
	private string $section = '';
	private ?string $sectiontitle = null;
	private ?string $starttime = null;
	private ?MessageSpecifier $submitButtonLabel = null;
	private int $undidRev = 0;
	private int $undoAfter = 0;
	private ?UserIdentity $userForPreview = null;
	private ?UserIdentity $userForSave = null;
	private ?string $watchlistExpiry = null;
	/** @var ?int[] */
	private ?array $watchlistLabels = null;
	private bool $watchthis = false;

	public function __construct(
		private Authority $authority,
		private string $contentModel,
		private IContextSource $context,
		private ProperPageIdentity $page,
		private string $summary,
		private string $textbox1,
	) {
		$this->starttime = wfTimestampNow();
	}

	public function shouldAllowBlankArticle(): bool {
		return $this->allowBlankArticle;
	}

	public function setAllowBlankArticle( bool $allowBlankArticle ): self {
		$this->allowBlankArticle = $allowBlankArticle;
		return $this;
	}

	public function shouldAllowBlankSummary(): bool {
		return $this->allowBlankSummary;
	}

	public function setAllowBlankSummary( bool $allowBlankSummary ): self {
		$this->allowBlankSummary = $allowBlankSummary;
		return $this;
	}

	public function getAllowedProblematicRedirectTarget(): ?LinkTarget {
		return $this->allowedProblematicRedirectTarget;
	}

	public function setAllowedProblematicRedirectTarget( ?LinkTarget $allowedProblematicRedirectTarget ): self {
		$this->allowedProblematicRedirectTarget = $allowedProblematicRedirectTarget;
		return $this;
	}

	public function getAuthority(): Authority {
		return $this->authority;
	}

	public function getAutoSumm(): string {
		return $this->autoSumm;
	}

	public function setAutoSumm( string $autoSumm ): self {
		$this->autoSumm = $autoSumm;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getChangeTags(): array {
		return $this->changeTags;
	}

	/**
	 * @param string[] $changeTags
	 */
	public function setChangeTags( array $changeTags ): self {
		$this->changeTags = $changeTags;
		return $this;
	}

	public function getContentFormat(): ?string {
		return $this->contentFormat;
	}

	public function setContentFormat( ?string $contentFormat ): self {
		$this->contentFormat = $contentFormat;
		return $this;
	}

	public function getContentModel(): string {
		return $this->contentModel;
	}

	public function getContext(): IContextSource {
		return $this->context;
	}

	public function getContextPage(): PageReference {
		return $this->contextPage ?? $this->getPage();
	}

	public function setContextPage( ?PageReference $contextPage ): self {
		$this->contextPage = $contextPage;
		return $this;
	}

	public function getEdittime(): ?string {
		return $this->edittime;
	}

	public function setEdittime( ?string $edittime ): self {
		$this->edittime = $edittime;
		return $this;
	}

	public function getEditRevId(): ?int {
		return $this->editRevId;
	}

	public function setEditRevId( ?int $editRevId ): self {
		$this->editRevId = $editRevId;
		return $this;
	}

	public function shouldEnableApiEditOverride(): bool {
		return $this->enableApiEditOverride;
	}

	public function setEnableApiEditOverride( bool $enableApiEditOverride ): self {
		$this->enableApiEditOverride = $enableApiEditOverride;
		return $this;
	}

	public function shouldIgnoreProblematicRedirects(): bool {
		return $this->ignoreProblematicRedirects;
	}

	public function setIgnoreProblematicRedirects( bool $ignoreProblematicRedirects ): self {
		$this->ignoreProblematicRedirects = $ignoreProblematicRedirects;
		return $this;
	}

	public function shouldIgnoreRevisionDeletedWarning(): bool {
		return $this->ignoreRevisionDeletedWarning;
	}

	public function setIgnoreRevisionDeletedWarning( bool $ignoreRevisionDeletedWarning ): self {
		$this->ignoreRevisionDeletedWarning = $ignoreRevisionDeletedWarning;
		return $this;
	}

	/**
	 * @return bool Whether the user requested that this edit is marked as a bot edit.
	 * Note that this method returning `true` doesn't mean the user actually has the
	 * permission to do that, it needs to be checked separately!
	 */
	public function shouldMarkAsBot(): bool {
		return $this->markAsBot;
	}

	public function setMarkAsBot( bool $markAsBot ): self {
		$this->markAsBot = $markAsBot;
		return $this;
	}

	/**
	 * @return bool Whether the user requested that this edit is marked as a minor edit.
	 * Note that this method returning `true` doesn't mean the user actually has the
	 * permission to do that, it needs to be checked separately!
	 */
	public function shouldMarkAsMinor(): bool {
		return $this->markAsMinor;
	}

	public function setMarkAsMinor( bool $markAsMinor ): self {
		$this->markAsMinor = $markAsMinor;
		return $this;
	}

	public function getOldid(): int {
		return $this->oldid;
	}

	public function setOldid( int $oldid ): self {
		$this->oldid = $oldid;
		return $this;
	}

	public function getPage(): ProperPageIdentity {
		return $this->page;
	}

	public function getParentRevId(): int {
		return $this->parentRevId;
	}

	public function setParentRevId( int $parentRevId ): self {
		$this->parentRevId = $parentRevId;
		return $this;
	}

	public function shouldRecreate(): bool {
		return $this->recreate;
	}

	public function setRecreate( bool $recreate ): self {
		$this->recreate = $recreate;
		return $this;
	}

	public function getSection(): string {
		return $this->section;
	}

	public function setSection( string $section ): self {
		$this->section = $section;
		return $this;
	}

	public function getSectiontitle(): ?string {
		return $this->sectiontitle;
	}

	public function setSectiontitle( ?string $sectiontitle ): self {
		$this->sectiontitle = $sectiontitle;
		return $this;
	}

	public function getStarttime(): ?string {
		return $this->starttime;
	}

	public function setStarttime( ?string $starttime ): self {
		$this->starttime = $starttime;
		return $this;
	}

	public function getSubmitButtonLabel(): ?MessageSpecifier {
		return $this->submitButtonLabel;
	}

	public function setSubmitButtonLabel( ?MessageSpecifier $submitButtonLabel ): self {
		$this->submitButtonLabel = $submitButtonLabel;
		return $this;
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

	public function setUndidRev( int $undidRev ): self {
		$this->undidRev = $undidRev;
		return $this;
	}

	public function getUndoAfter(): int {
		return $this->undoAfter;
	}

	public function setUndoAfter( int $undoAfter ): self {
		$this->undoAfter = $undoAfter;
		return $this;
	}

	public function getUserForPreview(): UserIdentity {
		return $this->userForPreview ?? $this->getAuthority()->getUser();
	}

	public function setUserForPreview( ?UserIdentity $userForPreview ): self {
		$this->userForPreview = $userForPreview;
		return $this;
	}

	public function getUserForSave(): UserIdentity {
		return $this->userForSave ?? $this->getAuthority()->getUser();
	}

	public function setUserForSave( ?UserIdentity $userForSave ): self {
		$this->userForSave = $userForSave;
		return $this;
	}

	public function getWatchlistExpiry(): ?string {
		return $this->watchlistExpiry;
	}

	public function setWatchlistExpiry( ?string $watchlistExpiry ): self {
		$this->watchlistExpiry = $watchlistExpiry;
		return $this;
	}

	/**
	 * @return ?int[]
	 */
	public function getWatchlistLabels(): ?array {
		return $this->watchlistLabels;
	}

	/**
	 * @param ?int[] $watchlistLabels
	 */
	public function setWatchlistLabels( ?array $watchlistLabels ): self {
		$this->watchlistLabels = $watchlistLabels;
		return $this;
	}

	public function shouldWatchthis(): bool {
		return $this->watchthis;
	}

	public function setWatchthis( bool $watchthis ): self {
		$this->watchthis = $watchthis;
		return $this;
	}

}
