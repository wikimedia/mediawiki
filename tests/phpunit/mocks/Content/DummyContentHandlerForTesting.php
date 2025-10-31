<?php

namespace MediaWiki\Tests\Mocks\Content;

use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Parser\ParserOutput;

class DummyContentHandlerForTesting extends ContentHandler {

	/** @inheritDoc */
	public function __construct( $dataModel, $formats = [ DummyContentForTesting::MODEL_ID ] ) {
		parent::__construct( $dataModel, $formats );
	}

	/** @inheritDoc */
	protected function getContentClass() {
		return DummyContentForTesting::class;
	}

	/**
	 * @see ContentHandler::serializeContent
	 *
	 * @param Content $content
	 * @param string|null $format
	 *
	 * @return string
	 */
	public function serializeContent( Content $content, $format = null ) {
		return $content->serialize();
	}

	/**
	 * @see ContentHandler::unserializeContent
	 *
	 * @param string $blob
	 * @param string|null $format Unused.
	 *
	 * @return Content
	 */
	public function unserializeContent( $blob, $format = null ) {
		return new DummyContentForTesting( $blob );
	}

	/**
	 * Creates an empty Content object of the type supported by this ContentHandler.
	 * @return DummyContentForTesting
	 */
	public function makeEmptyContent() {
		return new DummyContentForTesting( '' );
	}

	public function generateHTMLOnEdit(): bool {
		return false;
	}

	/**
	 * @see ContentHandler::fillParserOutput()
	 * @param Content $content
	 * @param ContentParseParams $cpoParams
	 * @param ParserOutput &$output The output object to fill (reference).
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	) {
			'@phan-var DummyContentForTesting $content';
			$output = new ParserOutput( $content->getNativeData() );
	}
}
