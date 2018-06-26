<?php

class DummyContentHandlerForTesting extends ContentHandler {

	public function __construct( $dataModel, $formats = [ DummyContentForTesting::MODEL_ID ] ) {
		parent::__construct( $dataModel, $formats );
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
		$d = unserialize( $blob );

		return new DummyContentForTesting( $d );
	}

	/**
	 * Creates an empty Content object of the type supported by this ContentHandler.
	 * @return DummyContentForTesting
	 */
	public function makeEmptyContent() {
		return new DummyContentForTesting( '' );
	}
}
