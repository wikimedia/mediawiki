<?php

use MediaWiki\Content\Content;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Parser\ParserOutput;

class DummyNonTextContentHandler extends DummyContentHandlerForTesting {

	/** @inheritDoc */
	public function __construct( $dataModel ) {
		parent::__construct( $dataModel, [ "testing-nontext" ] );
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
		return new DummyNonTextContent( $blob );
	}

	/**
	 * Creates an empty Content object of the type supported by this ContentHandler.
	 * @return DummyNonTextContent
	 */
	public function makeEmptyContent() {
		return new DummyNonTextContent( '' );
	}

	/** @inheritDoc */
	public function supportsDirectApiEditing() {
		return true;
	}

	/**
	 * @see ContentHandler::fillParserOutput()
	 *
	 * @since 1.38
	 * @param Content $content
	 * @param ContentParseParams $cpoParams
	 * @param ParserOutput &$output The output object to fill (reference).
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	) {
			'@phan-var DummyNonTextContent $content';
			$output = new ParserOutput( $content->serialize() );
	}
}
