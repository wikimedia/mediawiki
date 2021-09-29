<?php
namespace MediaWiki\Content\Renderer;

use MediaWiki\Page\PageReference;
use ParserOptions;

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

	public function __construct(
		PageReference $page,
		int $revId = null,
		ParserOptions $parserOptions = null,
		bool $generateHtml = true
	) {
		$this->page = $page;
		$this->parserOptions = $parserOptions ?? ParserOptions::newCanonical( 'canonical' );
		$this->revId = $revId;
		$this->generateHtml = $generateHtml;
	}

	/**
	 *
	 * @return PageReference
	 */
	public function getPage(): PageReference {
		return $this->page;
	}

	/**
	 *
	 * @return int|null
	 */
	public function getRevId(): ?int {
		return $this->revId;
	}

	/**
	 *
	 * @return ParserOptions
	 */
	public function getParserOptions(): ParserOptions {
		return $this->parserOptions;
	}

	/**
	 *
	 * @return bool
	 */
	public function getGenerateHtml(): bool {
		return $this->generateHtml;
	}
}
