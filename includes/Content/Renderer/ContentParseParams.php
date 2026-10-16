<?php
namespace MediaWiki\Content\Renderer;

use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;

/**
 * @internal
 * An object to hold parser params.
 */
class ContentParseParams {
	private readonly ParserOptions $parserOptions;

	public function __construct(
		private readonly PageReference $page,
		private readonly int|RevisionRecord|null $revision = null,
		?ParserOptions $parserOptions = null,
		private readonly bool $generateHtml = true,
		private readonly ?ParserOutput $previousOutput = null,
	) {
		$this->parserOptions = $parserOptions ?? ParserOptions::newFromAnon();
	}

	public function getPage(): PageReference {
		return $this->page;
	}

	public function getRevId(): ?int {
		return ( $this->revision instanceof RevisionRecord ) ?
			$this->revision->getId() : $this->revision;
	}

	public function getParserOptions(): ParserOptions {
		return $this->parserOptions;
	}

	public function getGenerateHtml(): bool {
		return $this->generateHtml;
	}

	public function getPreviousOutput(): ?ParserOutput {
		return $this->previousOutput;
	}

	public function getRevision(): ?RevisionRecord {
		return ( $this->revision instanceof RevisionRecord ) ? $this->revision : null;
	}
}
