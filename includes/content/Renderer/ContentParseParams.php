<?php
namespace MediaWiki\Content\Renderer;

use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

/**
 * @internal
 * An object to hold parser params.
 */
class ContentParseParams {
	/** @var PageReference */
	private $page;

	/** @var int|null */
	private $revId;

	/** @var ParserOptions */
	private $parserOptions;

	/** @var bool */
	private $generateHtml;

	/** @var ?ParserOutput */
	private $previousOutput;

	public function __construct(
		PageReference $page,
		?int $revId = null,
		?ParserOptions $parserOptions = null,
		bool $generateHtml = true,
		?ParserOutput $previousOutput = null
	) {
		$this->page = $page;
		$this->parserOptions = $parserOptions ?? ParserOptions::newFromAnon();
		$this->revId = $revId;
		$this->generateHtml = $generateHtml;
		$this->previousOutput = $previousOutput;
	}

	public function getPage(): PageReference {
		return $this->page;
	}

	public function getRevId(): ?int {
		return $this->revId;
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
}
