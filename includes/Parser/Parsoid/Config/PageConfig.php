<?php
declare( strict_types = 1 );
/**
 * Copyright (C) 2011-2022 Wikimedia Foundation and others.
 *
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Parser\Parsoid\Config;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleHandler;
use MediaWiki\Title\Title;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Parsoid\Config\PageConfig as IPageConfig;
use Wikimedia\Parsoid\Config\PageContent as IPageContent;

/**
 * Page-level configuration interface for Parsoid
 *
 * This is effectively "Parsoid's view of ParserOptions".
 *
 * @since 1.39
 * @internal
 */
class PageConfig extends IPageConfig {
	public function __construct(
		private readonly ParserOptions $parserOptions,
		private readonly SlotRoleHandler $slotRoleHandler,
		private readonly Title $title,
		private readonly ?RevisionRecord $revision,
		private readonly Bcp47Code $pageLanguage,
		private readonly string $pageLanguageDir,
	) {
	}

	public function getContentModel(): string {
		// @todo Check just the main slot, or all slots, or what?
		$rev = $this->getRevision();
		if ( $rev ) {
			$content = $rev->getContent( SlotRecord::MAIN );
			if ( $content ) {
				return $content->getModel();
			} else {
				// The page does have a content model but we can't see it. Returning the
				// default model is not really correct. But we can't see the content either
				// so it won't matter much what we do here.
				return $this->slotRoleHandler->getDefaultModel( $this->title );
			}
		} else {
			return $this->slotRoleHandler->getDefaultModel( $this->title );
		}
	}

	/** @inheritDoc */
	public function getLinkTarget(): Title {
		return $this->title;
	}

	/** @inheritDoc */
	public function getPageId(): int {
		return $this->title->getArticleID();
	}

	/** @inheritDoc */
	public function getPageLanguageBcp47(): Bcp47Code {
		return $this->pageLanguage;
	}

	/** @inheritDoc */
	public function getPageLanguageDir(): string {
		return $this->pageLanguageDir;
	}

	/**
	 * @internal Used by DataAccess; not part of Parsoid's interface.
	 * @return ParserOptions
	 */
	public function getParserOptions(): ParserOptions {
		return $this->parserOptions;
	}

	/**
	 * Use ParserOptions::getTemplateCallback() to fetch the correct
	 * (usually latest) RevisionRecord for the given title.
	 *
	 * @param Title $title
	 * @return ?RevisionRecord
	 */
	public function fetchRevisionRecordOfTemplate( Title $title ): ?RevisionRecord {
		// See Parser::fetchTemplateAndTitle(), but stateless
		// (Parsoid will track dependencies, etc, itself.)
		// The callback defaults to Parser::statelessFetchTemplate()
		$templateCb = $this->parserOptions->getTemplateCallback();
		$stuff = $templateCb( $title, $this );
		return $stuff['revision-record'] ?? null;
	}

	private function getRevision(): ?RevisionRecord {
		return $this->revision;
	}

	/** @inheritDoc */
	public function getRevisionId(): ?int {
		$rev = $this->getRevision();
		return $rev ? $rev->getId() : null;
	}

	/** @inheritDoc */
	public function getParentRevisionId(): ?int {
		$rev = $this->getRevision();
		return $rev ? $rev->getParentId() : null;
	}

	/** @inheritDoc */
	public function getRevisionTimestamp(): ?string {
		$rev = $this->getRevision();
		return $rev ? $rev->getTimestamp() : null;
	}

	/** @inheritDoc */
	public function getRevisionUser(): ?string {
		$rev = $this->getRevision();
		$user = $rev ? $rev->getUser() : null;
		return $user ? $user->getName() : null;
	}

	/** @inheritDoc */
	public function getRevisionUserId(): ?int {
		$rev = $this->getRevision();
		$user = $rev ? $rev->getUser() : null;
		return $user ? $user->getId() : null;
	}

	/** @inheritDoc */
	public function getRevisionSha1(): ?string {
		$rev = $this->getRevision();
		if ( $rev ) {
			// This matches what the Parsoid/JS gets from the API
			// FIXME: Maybe we don't need to do this in the future?
			return \Wikimedia\base_convert( $rev->getSha1(), 36, 16, 40 );
		} else {
			return null;
		}
	}

	/** @inheritDoc */
	public function getRevisionSize(): ?int {
		$rev = $this->getRevision();
		return $rev ? $rev->getSize() : null;
	}

	/** @inheritDoc */
	public function getRevisionContent(): ?IPageContent {
		$rev = $this->getRevision();
		return $rev ? new PageContent( $rev ) : null;
	}

}
