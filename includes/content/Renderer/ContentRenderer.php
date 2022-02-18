<?php
namespace MediaWiki\Content\Renderer;

use Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Page\PageReference;
use ParserOptions;
use ParserOutput;

/**
 * A service to render content.
 *
 * @since 1.38
 */
class ContentRenderer {
	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/**
	 * @param IContentHandlerFactory $contentHandlerFactory
	 */
	public function __construct( IContentHandlerFactory $contentHandlerFactory ) {
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	/**
	 * Returns a ParserOutput object containing information derived from this content.
	 *
	 * @param Content $content
	 * @param PageReference $page
	 * @param int|null $revId
	 * @param ParserOptions|null $parserOptions
	 * @param bool $generateHtml
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput(
		Content $content,
		PageReference $page,
		?int $revId = null,
		?ParserOptions $parserOptions = null,
		bool $generateHtml = true
	): ParserOutput {
		$contentHandler = $this->contentHandlerFactory->getContentHandler( $content->getModel() );
		$cpoParams = new ContentParseParams( $page, $revId, $parserOptions, $generateHtml );

		return $contentHandler->getParserOutput( $content, $cpoParams );
	}
}
